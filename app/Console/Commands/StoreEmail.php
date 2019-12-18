<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\FireWebhook;
use App\Mail;
use App\Webhook;
use Illuminate\Console\Command;
use ZBateson\MailMimeParser\MailMimeParser;

class StoreEmail extends Command
{
    /** @var string */
    protected $signature = 'email:store {filePath?}';

    /** @var string */
    protected $description = 'Store the email';

    public function handle(): void
    {
        $content = $this->read($this->argument('filePath'));
        $hash = md5(str_replace(' ', '', preg_replace('/^.+\n/', '', $content)));
        if (Mail::query()->where('hash', $hash)->exists()) {
            return;
        }
        /** @var Mail $mail */
        $mail = Mail::create([
            'content' => $content,
            'hash'    => $hash,
            'to'      => (new MailMimeParser())->parse($content)->getHeaderValue('to'),
        ]);

        $mail->users()->attach(
            Webhook::query()->where(['to' => $mail->to])->pluck('user_id')
        );

        Webhook::query()->where([
            'to'     => $mail->to,
            'paused' => false,
        ])->each(function (Webhook $webhook) {
            dispatch(new FireWebhook($webhook));
        });
    }

    protected function read(?string $filePath = null): string
    {
        $fh = fopen($filePath ?: 'php://stdin', 'r');
        $content = '';
        while (!feof($fh)) {
            $content .= fread($fh, 1024);
        }
        fclose($fh);

        return $content;
    }
}

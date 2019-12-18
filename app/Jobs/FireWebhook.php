<?php

namespace App\Jobs;

use AliSaleem\Curl\Request;
use App\Webhook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FireWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \App\Webhook */
    protected $webhook;

    public function __construct(Webhook $webhook)
    {
        $this->webhook = $webhook;
    }

    public function handle(): void
    {
        $this->webhook->touch();

        $response = (new Request($this->webhook->url))
            ->setOption(CURLOPT_TIMEOUT, 10)
            ->setBody([
                'data' => [
                    'mail_ids' => $this->webhook->user
                        ->unreadMails()
                        ->pluck('id'),
                ],
            ])
            ->addHeader('content-type', 'application/json')
            ->post();

        if ($response->getHttpCode() >= 400) {
            $this->webhook->update(['paused' => true]);
            dispatch(new SendWebhookDisabledNotification($this->webhook));
        }
    }
}

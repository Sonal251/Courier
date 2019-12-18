<?php

namespace App\Http\Controllers;

use App\Mail;
use Illuminate\Http\JsonResponse;

class MailController extends Controller
{
    public function list(): JsonResponse
    {
        return jsonResponse([
            'mail_ids' => auth()->user()
                ->unreadMails()
                ->pluck('id'),
        ]);
    }

    public function mail(int $mailId): string
    {
        return Mail::findOrFail($mailId)->content;
    }

    public function acknowledge(int $mailId): JsonResponse
    {
        if (!$mail = auth()->user()->mails()->where('id', $mailId)->first()) {
            return \jsonResponse(null, 404);
        }
        if ($mail->pivot->acknowledged) {
            return \jsonResponse(null, 410);
        }

        $acknowledged = now();
        auth()->user()->mails()->syncWithoutDetaching([$mail->id => ['acknowledged' => $acknowledged]]);

        return jsonResponse([
            'mail' => [
                'id'           => $mail->id,
                'acknowledged' => $acknowledged,
            ],
        ]);
    }
}

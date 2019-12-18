<?php

namespace App\Http\Controllers;

use App\Webhook;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->webhooks()->where('to', $request->to)->count()) {
            session()->flash('status', 'A forwarder for this email address already exists');

            return redirect()->back();
        }
        auth()->user()->webhooks()->save(new Webhook($request->all()));

        return redirect('home');
    }

    public function destroy(Webhook $webhook)
    {
        $webhook->delete();

        return redirect('home');
    }

    public function enable(Webhook $webhook)
    {
        $webhook->update(['paused' => false]);
        session()->flash('status', 'The ' . $webhook->to . ' forwarder has been re-enabled');

        return redirect('home');
    }
}

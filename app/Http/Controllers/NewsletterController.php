<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email', 'max:255']]);

        NewsletterSubscriber::firstOrCreate(['email' => $request->email]);

        return back()->with('newsletter_success', 'Terima kasih! Email Anda berhasil terdaftar. 🎉');
    }
}

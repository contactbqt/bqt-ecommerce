<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactQueryMail;

class ContactEmailController extends Controller
{
    public function sendContact(Request $request)
    {

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email',
            'phone'      => 'required|string|max:13',
            'message'    => 'required|string',
        ]);

        $toEmail = config('constants.admin_mail');

        try {
            Mail::to($toEmail)
                ->send((new ContactQueryMail($validated))
                    ->replyTo($validated['email'], $validated['first_name'] . ' ' . $validated['last_name'])
                );

            return redirect()->back()->with(['success' => true, 'message' => 'Your message has been sent successfully!']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['success' => false, 'message' => 'Failed to send message: ' . $e->getMessage()]);
        }
    }
}

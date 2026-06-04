<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactQueryMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $d = $this->data;

        $first = e($d['first_name'] ?? '');
        $last = e($d['last_name'] ?? '');
        $email = e($d['email'] ?? '');
        $phone = e($d['phone'] ?? '');
        $message = nl2br(e($d['message'] ?? ''));

        $html = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>New Contact Query</title>
            <style>
                body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f6f8fb; color: #333; margin: 0; padding: 0; }
                .container { max-width: 700px; margin: 40px auto; background-color: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
                .header { background-color: #3B1E08; color: #fff; text-align: center; padding: 20px; }
                .header h1 { margin: 0; font-size: 22px; }
                .content { padding: 24px 30px; }
                .section-heading { font-size: 18px; font-weight: 600; color: #3B1E08; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 2px solid #3B1E08; }
                table { width: 100%; border-collapse: collapse; }
                td { padding: 8px 5px; vertical-align: top; }
                td.label { width: 35%; font-weight: bold; color: #555; }
                td.value { width: 65%; }
                .footer { text-align: center; background-color: #f0f3f7; padding: 15px; font-size: 12px; color: #777; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'><h1>New Contact Query</h1></div>
                <div class='content'>
                    <p>Dear Admin,</p>
                    <p>A new contact enquiry has been submitted. Details below:</p>
                    <h3 class='section-heading'>Contact Details</h3>
                    <table>
                        <tr><td class='label'>Name:</td><td class='value'>{$first} {$last}</td></tr>
                        <tr><td class='label'>Email:</td><td class='value'>{$email}</td></tr>
                        <tr><td class='label'>Phone:</td><td class='value'>{$phone}</td></tr>
                    </table>

                    <h3 class='section-heading'>Message</h3>
                    <div>{$message}</div>
                </div>
                <div class='footer'>&copy; " . date('Y') . " Pathogenes Polyclinic & Diagnostics. All rights reserved.</div>
            </div>
        </body>
        </html>
        ";

        return $this->subject('New Contact Query - Pathogenes Diagnostics')
            ->html($html);
    }
}


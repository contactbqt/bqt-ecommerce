<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Listeners\RestoreCartOnLogin;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load custom helpers
        require_once app_path('helpers.php');

        Event::listen(
            Login::class,
            RestoreCartOnLogin::class
        );

        // Dynamically set mail configuration from settings table
        if (Schema::hasTable('settings')) {
            $mailSettings = Setting::whereIn('key', [
                'MAIL_MAILER',
                'MAIL_HOST',
                'MAIL_PORT',
                'MAIL_USERNAME',
                'MAIL_PASSWORD',
                'MAIL_ENCRYPTION',
                'MAIL_FROM_ADDRESS',
                'MAIL_FROM_NAME'
            ])->get()->pluck('value', 'key');

            if ($mailSettings->isNotEmpty()) {
                $config = [
                    'transport' => $mailSettings->get('MAIL_MAILER', config('mail.default')),
                    'host' => $mailSettings->get('MAIL_HOST', config('mail.mailers.smtp.host')),
                    'port' => $mailSettings->get('MAIL_PORT', config('mail.mailers.smtp.port')),
                    'encryption' => $mailSettings->get('MAIL_ENCRYPTION', config('mail.mailers.smtp.encryption')),
                    'username' => $mailSettings->get('MAIL_USERNAME', config('mail.mailers.smtp.username')),
                    'password' => $mailSettings->get('MAIL_PASSWORD', config('mail.mailers.smtp.password')),
                    'timeout' => null,
                    'local_domain' => env('MAIL_EHLO_DOMAIN'),
                ];

                Config::set('mail.mailers.smtp', array_merge(config('mail.mailers.smtp'), $config));
                Config::set('mail.default', $mailSettings->get('MAIL_MAILER', config('mail.default')));
                Config::set('mail.from.address', $mailSettings->get('MAIL_FROM_ADDRESS', config('mail.from.address')));
                Config::set('mail.from.name', $mailSettings->get('MAIL_FROM_NAME', config('mail.from.name')));
            }

            // Dynamically set social configuration from settings table
            $socialSettings = Setting::whereIn('key', [
                'GOOGLE_CLIENT_ID',
                'GOOGLE_SECRET',
                'GOOGLE_REDIRECT_URL',
                'FACEBOOK_APP_ID',
                'FACEBOOK_SECRET',
                'FACEBOOK_REDIRECT_URL',
                'SOCIAL_ENABLE'
            ])->get()->pluck('value', 'key');

            if ($socialSettings->isNotEmpty()) {
                // Google
                Config::set('services.google.client_id', $socialSettings->get('GOOGLE_CLIENT_ID', config('services.google.client_id')));
                Config::set('services.google.client_secret', $socialSettings->get('GOOGLE_SECRET', config('services.google.client_secret')));
                Config::set('services.google.redirect', $socialSettings->get('GOOGLE_REDIRECT_URL', config('services.google.redirect')));

                // Facebook
                Config::set('services.facebook.client_id', $socialSettings->get('FACEBOOK_APP_ID', config('services.facebook.client_id')));
                Config::set('services.facebook.client_secret', $socialSettings->get('FACEBOOK_SECRET', config('services.facebook.client_secret')));
                Config::set('services.facebook.redirect', $socialSettings->get('FACEBOOK_REDIRECT_URL', config('services.facebook.redirect')));
            }
        }
    }
}

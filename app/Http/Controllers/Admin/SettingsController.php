<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller
{
    /**
     * Show the settings page with current .env values.
     */
    public function index()
    {
        $settings = [
            'mail_mailer'       => env('MAIL_MAILER', 'log'),
            'mail_host'         => env('MAIL_HOST', ''),
            'mail_port'         => env('MAIL_PORT', '587'),
            'mail_username'     => env('MAIL_USERNAME', ''),
            'mail_password'     => env('MAIL_PASSWORD', ''),
            'mail_encryption'   => env('MAIL_ENCRYPTION', 'tls'),
            'mail_from_address' => env('MAIL_FROM_ADDRESS', ''),
            'mail_from_name'    => env('MAIL_FROM_NAME', 'UniVote'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Save mail settings to .env file.
     */
    public function update(Request $request)
    {
        $request->validate([
            'mail_mailer'       => ['required', 'in:smtp,log,mailgun,ses,postmark'],
            'mail_host'         => ['nullable', 'string', 'max:255'],
            'mail_port'         => ['nullable', 'integer', 'min:1', 'max:65535'],
            'mail_username'     => ['nullable', 'string', 'max:255'],
            'mail_password'     => ['nullable', 'string', 'max:255'],
            'mail_encryption'   => ['nullable', 'in:tls,ssl,'],
            'mail_from_address' => ['required', 'email'],
            'mail_from_name'    => ['required', 'string', 'max:255'],
        ]);

        $values = [
            'MAIL_MAILER'       => $request->mail_mailer,
            'MAIL_HOST'         => $request->mail_host ?? '',
            'MAIL_PORT'         => $request->mail_port ?? '587',
            'MAIL_USERNAME'     => $request->mail_username ?? '',
            'MAIL_PASSWORD'     => $request->mail_password ?? '',
            'MAIL_ENCRYPTION'   => $request->mail_encryption ?? 'tls',
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            'MAIL_FROM_NAME'    => '"' . $request->mail_from_name . '"',
        ];

        $this->updateEnvFile($values);

        // Clear config cache so new values take effect immediately
        Artisan::call('config:clear');

        return back()->with('success', 'Mail settings saved successfully.');
    }

    /**
     * Send a test email to verify settings work.
     */
    public function testMail(Request $request)
    {
        $request->validate([
            'test_email' => ['required', 'email'],
        ]);

        try {
            Mail::raw(
                'This is a test email from UniVote. Your mail configuration is working correctly!',
                function ($message) use ($request) {
                    $message->to($request->test_email)
                            ->subject('UniVote — Mail Configuration Test');
                }
            );

            return back()->with('success', "Test email sent to {$request->test_email}. Check your inbox.");
        } catch (\Exception $e) {
            return back()->with('error', 'Mail test failed: ' . $e->getMessage());
        }
    }

    /**
     * Write key=value pairs to the .env file safely.
     */
    private function updateEnvFile(array $values): void
    {
        $envPath    = base_path('.env');
        $envContent = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            // If key exists — replace it
            if (preg_match("/^{$key}=.*/m", $envContent)) {
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$value}",
                    $envContent
                );
            } else {
                // Key doesn't exist — append it
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
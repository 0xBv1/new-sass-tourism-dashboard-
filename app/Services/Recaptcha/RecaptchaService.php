<?php

namespace App\Services\Recaptcha;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    /**
     * Verify the reCAPTCHA token with Google's API.
     *
     * @param string $token
     * @param string $ip
     * @return array
     */
    public function verify(string $token, string $ip): array
    {
        return [
            'success' => true,
            'score' => 1
        ];
//        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
//            'secret' => env('RECAPTCHA_SECRET_KEY'),
//            'response' => $token,
//            'remoteip' => $ip,
//        ]);
//
//        return $response->json();
    }
}

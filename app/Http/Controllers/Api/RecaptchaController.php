<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class RecaptchaController extends Controller
{
    public function verify(Request $request)
    {
        $client = new Client();

        $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => '6LeiTbUqAAAAAD_ghmHLiRA8ASNN6Kb3-pHduBhy',
                'response' => $request->input('response'),
            ],
        ]);

        $body = json_decode($response->getBody());
        dd($body);
        if ($body->success) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}

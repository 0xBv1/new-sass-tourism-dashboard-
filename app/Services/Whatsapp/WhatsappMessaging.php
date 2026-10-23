<?php

namespace App\Services\Whatsapp;

use App\Exceptions\Whatsapp\FailedSendMessageException;
use App\Exceptions\Whatsapp\MissingConfigurationException;
use App\Exceptions\Whatsapp\MissingPhoneNumberException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Throwable;

class WhatsappMessaging
{
    private PendingRequest $http;

    protected mixed $version;
    protected mixed $phone_id;

    /**
     * @throws Throwable
     */
    public function __construct()
    {
        $this->validateConfiguration();

        $this->http = Http::baseUrl(config('services.whatsapp.url'))
            ->withHeaders([
                'Authorization' => 'Bearer ' . config('services.whatsapp.token')
            ]);

        $this->version = config('services.whatsapp.version');

        $this->phone_id = config('services.whatsapp.phone_id');
    }

    /**
     * @throws Throwable
     */
    public function validateConfiguration(): void
    {
        throw_if(!config('services.whatsapp.token'), new MissingConfigurationException('Access Token'));

        throw_if(!config('services.whatsapp.version'), new MissingConfigurationException('API Version'));

        throw_if(!config('services.whatsapp.phone_id'), new MissingConfigurationException('Phone ID'));
    }

    /**
     * @param string $to
     * @param string $template
     * @param array $placeholders
     * @param string $langCode
     * @return bool
     * @throws Throwable
     */
    public function send(string $to = "", string $template = '', array $placeholders = [], string $langCode = "en_US"): bool
    {
        throw_if(!$to, new MissingPhoneNumberException);

        throw_if(!$template, new MissingPhoneNumberException);
        $payload = [
            'messaging_product' => "whatsapp",
            'to' => $to,
            "type" => "template",
            'template' => [
                "name" => $template,
                'language' => [
                    'code' => $langCode
                ]
            ]
        ];
        if (!empty($placeholders)) {
            $payload['template']['components'] = $placeholders;
        }
        $response = $this->http->asJson()
            ->post("{$this->version}/{$this->phone_id}/messages", $payload);

        throw_if($response->failed(), new FailedSendMessageException($response->json('error')['message'] ?? 'Unknown'));

        return true;
    }

}

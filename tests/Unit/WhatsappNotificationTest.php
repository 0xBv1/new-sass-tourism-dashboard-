<?php

namespace Tests\Unit;

use App\Exceptions\Whatsapp\MissingPhoneNumberException;
use App\Services\Whatsapp\WhatsappMessaging;
use Tests\TestCase;

class WhatsappNotificationTest extends TestCase
{
    /**
     * @throws \Throwable
     * @throws MissingPhoneNumberException
     */
    public function test_send_whatsapp_message_successfully()
    {
        $whatsappMessingService = new WhatsappMessaging;
        $this->assertTrue($whatsappMessingService->send('+201092947418', 'order_shipped'));
    }

    /**
     * @throws \Throwable
     * @throws MissingPhoneNumberException
     */
    public function test_send_whatsapp_message_with_parameters_successfully()
    {
        $whatsappMessingService = new WhatsappMessaging;
        $placeholders = [
            [
                'type' => 'body',
                'parameters' => [
                    ['type' => 'text', 'text' => 'Ahmed Nasr Unit Test']
                ]
            ]
        ];
        $this->assertTrue($whatsappMessingService->send('+201092947418', 'new_tour', $placeholders));
    }
    /**
     * @throws \Throwable
     * @throws MissingPhoneNumberException
     */
    public function test_send_whatsapp_message_with_parameters_and_button_successfully()
    {
        $whatsappMessingService = new WhatsappMessaging;
        $placeholders = [
            [
                'type' => 'header',
                'parameters' => [
                    [
                        'type' => 'text',
                        'text' => 'Ahmed'
                    ]
                ]
            ],

            [
                "type" => "button",
                "sub_type" => "url",
                "index" => "0",
                "parameters" => [
                    [
                        "type" => "payload",
                        "payload" => 6
                    ]
                ]
            ]

        ];
        $this->assertTrue($whatsappMessingService->send('+201092947418', 'booking_assigned', $placeholders));
    }
}

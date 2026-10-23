<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class SendMessageCommand extends Command
{
    protected $signature = 'send:message';

    protected $description = 'Command description';

    /**
     * @throws TwilioException
     * @throws ConfigurationException
     */
    public function handle(): void
    {
        $sid = "AC34a1bd3a16f96cafe7d27c94d6795d7f";
        $token = "87ece11fc557598b57c3ebe3cc2e37d4";
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create("whatsapp:+201150225286", // to
                [
                    "from" => "whatsapp:+14155238886",
                    "body" => 'Hello Ahmed Nasr Laravel, You can check Twillio Package https://github.com/twilio/twilio-php'
                ]);
        dd($message);
    }
}

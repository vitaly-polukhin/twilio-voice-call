<?php

namespace App\Services;

use Twilio\Jwt\ClientToken;

class TwilioTokenGenerator
{
    private ClientToken $clientToken;
    public function __construct()
    {
        $this->clientToken = new ClientToken(env('TWILIO_SID'),env('TWILIO_TOKEN'));
        $this->clientToken->allowClientOutgoing(env('TWILIO_APP_SID'));
    }
    public function generateToken(string $clientName): string
    {
        $this->clientToken->allowClientIncoming($clientName);
        return $this->clientToken->generateToken(3600*12);
    }
}

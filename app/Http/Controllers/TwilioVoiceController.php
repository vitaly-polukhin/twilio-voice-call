<?php

namespace App\Http\Controllers;

use App\Services\TwilioTokenGenerator;
use App\Services\UserPhoneFinder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Twilio\TwiML\VoiceResponse;

class TwilioVoiceController extends Controller
{
    /**
     * Renders the view for initiating a Twilio voice call.
     *
     * Generates a Twilio token for the user and passes it to the view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index():View
    {
        $token=(new TwilioTokenGenerator())->generateToken('JohnDoe');
        return view('twilio_voice_call',compact('token'));
    }

    /**
     * Handles an incoming Twilio voice call.
     *
     * Creates a Twilio VoiceResponse object, dials the specified number, and records the call.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function voice(Request $request):JsonResponse
    {
        $response = new VoiceResponse();
        $dial = $response->dial('', [
            'callerId' => (new UserPhoneFinder())->getPhoneNumberByName($request->from),
            'record' => 'record-from-ringing-dual',
        ]);

        $dial->number($request->to);
        return response()->json(['message'=>'call started']);
    }
}

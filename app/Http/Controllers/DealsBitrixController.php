<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;

class DealsBitrixController extends Controller
{
    /**
     * @param Request $request
     * @return void
     * @throws GuzzleException
     */
    public function updateDeal(Request $request){
        $input = $request->toArray();
        if (!self::authorizeRequest($input['auth']['application_token'])){
            Log::info('Запрос с неизвестного приложения. input: ' . json_encode($input));
            return 'this action is unauthorized';
        }
        $validated = $request->validate(
            rules:[
                'data.FIELDS.ID'    => 'required|numeric',
                'event' => 'required|string',
            ]
        );
        $this->sendTelegram(serialize($validated));
    }
    private static function authorizeRequest(string $request_token): bool{
        $tokens = [
            'gu04g0rumcbps79d7vhxwbii0pywbidf' => true,
            'j8tkjnkkpii718woufh6p11seew1ntdc' => true,
        ];
        if (isset($tokens[$request_token])){
            return $tokens[$request_token];
        }
        return false;
    }

    private function sendTelegram(string $mess, int $chat_id = 233617089){
        $guzzle = new Client(
            config: [
                'base_uri' => 'https://api.telegram.org/bot5970971353:AAHyyXHLAMyC86oIiUREWrMNk8baHoYYZ4E/',
                'timeout'  => '10.0',
            ]
        );
        $response = $guzzle->post(
            uri:'sendMessage',
            options: [
                'method'    => 'POST',
                'query'      => [
                    'chat_id'   => $chat_id,
                    'text'      => $mess,
                ],
            ],
        );
    }
}

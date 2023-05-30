<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class DealsBitrixController extends Controller
{
    /**
     * @param Request $request
     * @return void
     * @throws GuzzleException
     */
    public function updateDeal(Request $request){
        $this->sendTelegram(serialize($request->toArray()));
//        $validated = $request->validate(
//            rules:[
//                'ID' => 'required|numeric',
//                'STAGE_ID' => 'required|string',
//            ]
//        );

    }
    /**
     * @param string $mess
     * @param int $chat_id
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
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

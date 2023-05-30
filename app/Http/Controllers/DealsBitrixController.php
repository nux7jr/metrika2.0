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
     * @var Client
     */
    private static Client $guzzle;
    public function __construct(){
        self::$guzzle = new Client(
            config: [
                'base_uri' => 'https://tiksan-group.bitrix24.ru/rest/1/t7wy1lx66dizck10/',
                'timeout'  => '10.0',
            ]
        );
    }
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
        $deal_info = $this->getDealById(intval($validated['data.FIELDS.ID']));
        $this->sendTelegram(serialize($deal_info));
    }

    /**
     * check input webhook token
     * @param string $request_token
     * @return bool
     */
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

    private function getDealById(int $id): array{
        $response = self::$guzzle->post(
            uri:'crm.deal.list.json',
            options: [
                'method'    => 'POST',
                'body'      => json_encode([
                    'order'     => ['DATE_CREATE'=>'ASC'],
                    'filter'    => ['ID'=>$id],
                    'select'    => [
                        'BEGINDATE',
                        'STAGE_ID',
                        'ID',
                        'CLOSEDATE',
                        'DATE_CREATE',
                        'DATE_MODIFY',
                        'CLOSED',
                        'CONTACT_ID',
                        'CATEGORY_ID',
                        'CURRENCY_ID',
                        'OPPORTUNITY',
                    ],
                ]),
            ],
        );
        return json_decode($response->getBody());
    }

    private function sendTelegram(string $mess, int $chat_id = 233617089){
        $response = self::$guzzle->post(
            uri:'https://api.telegram.org/bot5970971353:AAHyyXHLAMyC86oIiUREWrMNk8baHoYYZ4E/sendMessage',
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

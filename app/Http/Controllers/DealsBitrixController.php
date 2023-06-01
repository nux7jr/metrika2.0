<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\PostgresConnection;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

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
     * @return string
     * @throws GuzzleException
     * @throws Throwable
     */
    public function update(Request $request){
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
        $deal_info = $this->getDealById(intval($validated['data']['FIELDS']['ID']));
        if (empty($deal_info['result'])){
            Log::info('Пришел пустой ответ от Битрикс24. input: ' . json_encode($input));
            return 'result is empty';
        }
        $connection = DB::connection('pgsql');
        $deal = $connection->table('deals')->where('id','=',$validated['data']['FIELDS']['ID']);
        if (!$deal->exists()){
            $this->insertDeal($deal_info['result'][0], $connection);
            return 'deal was created';
        }
        $this->updateDeal($deal_info['result'][0], $connection, $deal);
        return 'deal was updated';
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

    /**
     * get array deal from Bitrix
     * @param int $id
     * @return array
     * @throws GuzzleException
     */
    public function getDealById(int $id): array{
        $response = self::$guzzle->post(
            uri:'crm.deal.list.json',
            options: [
                'method'    => 'POST',
                'query'      => ([
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
                        'UTM_SOURCE',
                        'UTM_MEDIUM',
                        'UTM_CAMPAIGN',
                        'UTM_TERM',
                        'UTM_CONTENT',
                        'UF_CRM_1681873184', // поле url
                        'UF_CRM_1651563289996', // поле Город
                        'UF_CRM_1676887184893', // поле Провал отопить мск
                        'UF_CRM_1603858489', // поле Провал воронка продаж
                        'UF_CRM_1658364680', // поле Провал авто
                        'UF_CRM_1663244279467', // поле Провал СТП
                        'UF_CRM_1662519844408', // поле Провал МСК холодные
                        'UF_CRM_1663671866564', // поле Провал отопить
                        'UF_CRM_1677737737113', // поле Провал отопить пром
                    ],
                ]),
            ],
        );
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * get Phone from Bitrix
     * @param int $id
     * @return array
     * @throws GuzzleException
     */
    private function getContactById(int $id): array{
        $response = self::$guzzle->post(
            uri:'crm.contact.list.json',
            options: [
                'method'    => 'POST',
                'query'      => ([
                    'order'     => ['DATE_CREATE'=>'ASC'],
                    'filter'    => ['ID'=>$id],
                    'select'    => [
                        'PHONE',
                    ],
                ]),
            ],
        );
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param array $deal
     * @param PostgresConnection $connection
     * @return void
     * @throws GuzzleException
     * @throws Throwable
     */
    public function insertDeal(array $deal, PostgresConnection $connection): void{
        try {
            $contact = $this->getContactById(intval($deal['CONTACT_ID']));

            $is_adv = false;
            !empty($deal['UTM_SOURCE'])     ? $deal['UTM_SOURCE'] !== 'TEST' ? $is_adv = true : '' : '';
            !empty($deal['UTM_MEDIUM'])     ? $deal['UTM_MEDIUM'] !== 'TEST' ? $is_adv = true : '' : '';
            !empty($deal['UTM_CAMPAIGN'])   ? $is_adv = true : '';
            !empty($deal['UTM_TERM'])       ? $is_adv = true : '';
            !empty($deal['UTM_CONTENT'])    ? $is_adv = true : '';

            $carbon = new Carbon($deal['DATE_CREATE']);
            $carbon->setTimezone(7);
            $date_create = $carbon->toDateTimeString();
            $carbon->setTimezone(3);
            $carbon->setDateTimeFrom($deal['DATE_MODIFY']);
            $carbon->setTimezone(7);
            $date_updated = $carbon->toDateTimeString();

            $phone = isset($contact['result'][0]['PHONE'][0]['VALUE'])? phoneFormatter($contact['result'][0]['PHONE'][0]['VALUE']) : null;

            $insert_data = [
                'id'            => $deal['ID'],
                'is_adv'        => $is_adv,
                'utm_source'    => $deal['UTM_SOURCE'] ?? '',
                'utm_medium'    => $deal['UTM_MEDIUM'] ?? '',
                'utm_campaign'  => $deal['UTM_CAMPAIGN'] ?? '',
                'utm_content'   => $deal['UTM_CONTENT'] ?? '',
                'utm_term'      => $deal['UTM_TERM'] ?? '',
                'url'           => $deal['UF_CRM_1681873184'] ?? '',
                'stage_now'     => $deal['STAGE_ID'],
                'income'        => floatval($deal['OPPORTUNITY']),
                'currency'      => $deal['CURRENCY_ID'],
                'phone'         => $phone,
                'created_at'    => $date_create,
                'updated_at'    => $date_updated,
                'city'          => $deal['UF_CRM_1651563289996'],
            ];
            Log::info('insert Data: ' . json_encode($insert_data));
            $connection->beginTransaction();
            $result = $connection->table('deals')->insert($insert_data);
            Log::info('deal was created.');
            $connection->commit();
        }catch (\Exception $error){
            $connection->rollBack();
            if (strpos('duplicate key value violates unique constraint "deals_pkey"') !== false){
                $this->sendTelegram('error create Deal. Message: ' . $error->getMessage());
            }
            Log::info('error create Deal. Message: ' . $error->getMessage());
        }

    }

    /**
     * @param array $deal
     * @param PostgresConnection $connection
     * @param Builder $builder
     * @return void
     * @throws GuzzleException
     * @throws Throwable
     */
    public function updateDeal(array $deal, PostgresConnection $connection, Builder $builder){
        try {
            $db_deal = $builder->first();
            $carbon = new Carbon($deal['DATE_MODIFY']);
            $carbon->setTimezone(7);
            $date_updated = $carbon->toDateTimeString();

            $data_updated = [
                'updated_at' => $date_updated,
            ];
            $db_deal->updated_at = $date_updated;
            $db_deal->currency !== $deal['CURRENCY_ID'] ? $data_updated['currency'] = $deal['CURRENCY_ID'] : '';
            (float)$db_deal->income !== floatval($deal['OPPORTUNITY']) ? $data_updated['income'] = floatval($deal['OPPORTUNITY']) : '';
            if($db_deal->stage_now !== $deal['STAGE_ID']){
                $stages = json_decode($db_deal->stage_changes, true);
                $datetime = Carbon::today()->toDateTimeString();
                $stages[] = [
                    'from' => $db_deal->stage_now,
                    'to' => $deal['STAGE_ID'],
                    'datetime' => $datetime,
                ];
                $data_updated['stage_changes'] = json_encode($stages);
                $data_updated['stage_now'] = $deal['STAGE_ID'];
            }

            if (!empty($deal['UF_CRM_1676887184893'])){
                $data_updated['reason_closed'] = getReasonClosedByName(field_code: 'UF_CRM_1676887184893', id: intval($deal['UF_CRM_1676887184893']));
            }
            if (!empty($deal['UF_CRM_1603858489'])){
                $data_updated['reason_closed'] = getReasonClosedByName(field_code: 'UF_CRM_1603858489', id: intval($deal['UF_CRM_1603858489'][0]));
            }
            if (!empty($deal['UF_CRM_1658364680'])){
                $data_updated['reason_closed'] = getReasonClosedByName(field_code: 'UF_CRM_1658364680', id: intval($deal['UF_CRM_1658364680'][0]));
            }
            if (!empty($deal['UF_CRM_1663244279467'])){
                $data_updated['reason_closed'] = getReasonClosedByName(field_code: 'UF_CRM_1663244279467', id: intval($deal['UF_CRM_1663244279467']));
            }
            if (!empty($deal['UF_CRM_1662519844408'])){
                $data_updated['reason_closed'] = getReasonClosedByName(field_code: 'UF_CRM_1662519844408', id: intval($deal['UF_CRM_1662519844408']));
            }
            if (!empty($deal['UF_CRM_1663671866564'])){
                $data_updated['reason_closed'] = getReasonClosedByName(field_code: 'UF_CRM_1663671866564', id: intval($deal['UF_CRM_1663671866564']));
            }
            if (!empty($deal['UF_CRM_1677737737113'])){
                $data_updated['reason_closed'] = getReasonClosedByName(field_code: 'UF_CRM_1677737737113', id: intval($deal['UF_CRM_1677737737113']));
            }

            Log::info('update Data: ' . json_encode($data_updated));
            foreach ($db_deal as $key => $val){
                if (!isset($data_updated[$key])){
                    $data_updated[$key] = $val;
                }
            }
            $connection->beginTransaction();
            $builder->update($data_updated);
            Log::info('deal was updated.');
            $connection->commit();
        }catch (\Exception $error){
            $connection->rollBack();
            $this->sendTelegram('error update Deal. Message: ' . $error->getMessage());
            Log::info('error update Deal. Message: ' . $error->getMessage());
        }
    }
    /**
     * @param string $mess
     * @param int $chat_id
     * @return voids
     * @throws GuzzleException
     */
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

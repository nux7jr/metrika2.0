<?php

namespace App\Http\Helpers\PipeFiles;

class ParseLeadsFileAndB24
{
    /**
     * @var Leads
     */
    private static Leads $leads;
    /**
     * @var array
     */
    private static array $leads_from_file;
    /**
     * @var array|string[]
     */
    private static array $settings = [
        'subdomen' => 'tiksan-group',
        'userId' => '1',
        'codewebhook' => 't7wy1lx66dizck10'
    ];
    public function __construct(){
        self::$leads = new Leads();
        self::$leads_from_file = self::$leads->getLeadsFromFile('01.11.2022',date('d.m.Y',strtotime('tomorrow')));

        $count = count(self::$leads_from_file);
        $arr = [];
        for ($i = 0; $i < $count; $i++){
            if (isset(self::$leads_from_file[$i]['PHONE'])){
                self::$leads_from_file[$i]['PHONE'] = self::phone_format(self::$leads_from_file[$i]['PHONE']);
                $arr[self::$leads_from_file[$i]['PHONE']] = self::$leads_from_file[$i];
            }
        }
        self::$leads_from_file = $arr;
        unset($arr);
        foreach (self::$leads_from_file as $phone => $lead){
            $method = 'crm.contact.list';
            $query_data = http_build_query([
                'order' => ["DATE_CREATE" => "ASC"],
                'filter' => ["PHONE" => $phone],
                'select' => ["ID" => ''],
            ]);
            $contact = json_decode(self::post($method, $query_data),true);
            self::$leads_from_file['CONTACT_IDs'];

        }
    }

    /**
     * @param $method
     * @param $queryData
     * @return bool|string
     */
    private static function post($method, $queryData): bool|string
    {

        $queryUrl = "https://" . self::$settings['subdomen'] . ".bitrix24.ru/rest/" . self::$settings['userId'] . "/" . self::$settings['codewebhook'] . "/" . $method . ".json";
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => $queryUrl,
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_POSTFIELDS => $queryData,
            )
        );
        $answer = curl_exec($ch);
        curl_close($ch);

        return $answer;
    }

    private static function phone_format($phone): array|string|null
    {
        $phone = trim($phone);

        $res = preg_replace(
            array(
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{3})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?(\d{3})[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{3})/',
                '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{3})[-|\s]?(\d{3})/',
            ),
            array(
                '+7 ($2) $3-$4-$5',
                '+7 ($2) $3-$4-$5',
                '+7 ($2) $3-$4-$5',
                '+7 ($2) $3-$4-$5',
                '+7 ($2) $3-$4',
                '+7 ($2) $3-$4',
            ),
            $phone
        );

        return $res;
    }
}

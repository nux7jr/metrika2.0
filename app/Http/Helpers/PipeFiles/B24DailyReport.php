<?php

namespace App\Http\Helpers\PipeFiles;

class B24DailyReport{

    private $codewebhook;
    private $subdomen;
    private $direction;
    private $date_start;
    private $date_end;
    private $user_id = 1;
    private $utmME = [
        'yandex', 'vk', 'vkontakte', 'mycom', 'telegram_ads'
    ];
    private $ufCrm1651563289996 = [ //списочное поле "Город (актуальный список)"
        'Красноярск'    => ['code'=>1040, 'needUtm'=>true],//для красноярска нужны только с utm'ками
        'Тюмень'        => ['code'=>978, 'needUtm'=>false],
        'Иркутск'       => ['code'=>1022, 'needUtm'=>false],
        'Владивосток'   => ['code'=>862, 'needUtm'=>false],
        'Пермь'         => ['code'=>844, 'needUtm'=>false],
        'Екатеринбург'  => ['code'=>1024, 'needUtm'=>false],
        'Барнаул'       => ['code'=>848, 'needUtm'=>false],
    ];
    public function __construct($settings){

        $this->direction = $settings['direction'];
        $this->subdomen = $settings['subdomen'];
        $this->codewebhook = $settings['codewebhook'];
        $this->user_id = $settings['userId'];
        $this->date_start = date('Y-m-d',strtotime($settings['date'])).'T00:00:00+07:00';
        $this->date_end = date('Y-m-d',strtotime($settings['date'])).'T23:59:59+07:00';

    }

    private function lead_list($cityCode, $utmIsset = false){
        //Зашиваем метод
        $method = 'crm.item.list';

        //Формируем параметры для создания лида в переменной $queryData
        $data = [
            'entityTypeId' => 2,
            'order' => ['id' => 'ASC'],
            'filter' => [
                'categoryId' => $this->direction,
                '>=createdTime' => $this->date_start,
                '<=createdTime' => $this->date_end,
                'ufCrm1651563289996' => $cityCode,
            ],
            'select' => ['id', 'title', 'utmSource', 'utmMedium', 'utmContent', 'utmTerm','ufCrm1651563289996', 'sourceId', 'searchContent', 'typeId','sourceDescription'],
        ];
        $result = [];
        do {
            $data['start'] = isset($answer->next) ? $answer->next : 0;
            // !$utmIsset ? $data['filter']['!=sourceDescription'] = '' : null;
            $queryData = http_build_query($data);
            $answer = json_decode($this->post($method, $queryData));

            foreach ($answer->result->items as $lead){
                if ($utmIsset){
                    $lead->utmSource ? $result[$this->search_phone($lead->searchContent)] = (array)$lead : null;
                }else{
                    $this->multineedle_stripos($lead->utmSource, $this->utmME) ||  $this->multineedle_stripos($lead->title, ['звонок', 'Звонок']) ? $result[$this->search_phone($lead->searchContent)] = (array)$lead : null;
                }

                if($cityCode == 1040){//если красноярск то проверяем на звонки
                    $this->multineedle_stripos($lead->title, ['звонок', 'Звонок']) ? $result[$this->search_phone($lead->searchContent)] = (array)$lead : null;
                }
            }
        }while(isset($answer->next));

        return $result;
    }

    public function getDataFromEtaji(){
        $result = [];
        foreach ($this->ufCrm1651563289996 as $city => $data){
            $result[$city] = $this->lead_list($data['code'], $data['needUtm']);
        }

        return $result;

    }

    private function lead_list_no_utm($cityCode){
        //Зашиваем метод
        $method = 'crm.item.list';

        //Формируем параметры для создания лида в переменной $queryData
        $data = [
            'entityTypeId' => 2,
            'order' => ['id' => 'ASC'],
            'filter' => [
                'categoryId' => $this->direction,
                '>=createdTime' => $this->date_start,
                '<=createdTime' => $this->date_end,
                'ufCrm1651563289996' => $cityCode,
            ],
            'select' => ['id', 'title', 'utmSource', 'utmMedium', 'utmContent', 'utmTerm','ufCrm1651563289996', 'sourceId', 'searchContent', 'typeId','sourceDescription'],
        ];
        $result = [];
        do {
            $data['start'] = isset($answer->next) ? $answer->next : 0;
            $queryData = http_build_query($data);
            $answer = json_decode($this->post($method, $queryData));

            foreach ($answer->result->items as $lead){
                $result[$this->search_phone($lead->searchContent)] = (array)$lead;
            }
        }while(isset($answer->next));

        return $result;
    }

    public function getDataFromEtajiNoUtm(){
        $result = [];
        foreach ($this->ufCrm1651563289996 as $city => $data){
            $result[$city] = $this->lead_list_no_utm($data['code']);
        }

        return $result;

    }

    public function getDataFromSTP(){

        $method = 'crm.item.list';

        //Формируем параметры для создания лида в переменной $queryData
        $data = [
            'entityTypeId' => 2,
            'order' => ['id' => 'ASC'],
            'filter' => [
                'categoryId' => $this->direction,
                '>=createdTime' => $this->date_start,
                '<=createdTime' => $this->date_end,
            ],
            'select' => ['id', 'title', 'utmSource', 'utmMedium', 'utmContent', 'utmTerm', 'sourceId', 'searchContent', 'typeId'],
        ];
        $result = [];
        do {
            $queryData = http_build_query($data);
            $answer = json_decode($this->post($method, $queryData));
            foreach ($answer->result->items as $lead){
                $result[] = (array)$lead;
            }
        }while(isset($answer->next));

        return $result;

    }

    public function getDataFromNFSNG(){

        $method = 'crm.item.list';

        //Формируем параметры для создания лида в переменной $queryData
        $data = [
            'entityTypeId' => 2,
            'order' => ['id' => 'ASC'],
            'filter' => [
                'categoryId' => $this->direction,
                '>=createdTime' => $this->date_start,
                '<=createdTime' => $this->date_end,
            ],
            'select' => ['id', 'title', 'utmSource', 'utmMedium', 'utmContent', 'utmTerm', 'sourceId', 'searchContent', 'typeId'],
        ];
        $result = [];
        do {
            $queryData = http_build_query($data);
            $answer = json_decode($this->post($method, $queryData));
            foreach ($answer->result->items as $lead){
                $result[] = (array)$lead;
            }
        }while(isset($answer->next));

        return $result;

    }

    public function getDataFromNFWorld(){

        $method = 'crm.item.list';

        //Формируем параметры для создания лида в переменной $queryData
        $data = [
            'entityTypeId' => 2,
            'order' => ['id' => 'ASC'],
            'filter' => [
                'categoryId' => $this->direction,
                '>=createdTime' => $this->date_start,
                '<=createdTime' => $this->date_end,
            ],
            'select' => ['id', 'title', 'utmSource', 'utmMedium', 'utmContent', 'utmTerm', 'sourceId', 'searchContent', 'typeId'],
        ];
        $result = [];
        do {
            $queryData = http_build_query($data);
            $answer = json_decode($this->post($method, $queryData));
            foreach ($answer->result->items as $lead){
                $result[] = (array)$lead;
            }
        }while(isset($answer->next));

        return $result;
    }

    private function getCalls(){
        $method = 'crm.item.list';

        //Формируем параметры для создания лида в переменной $queryData
        $data = [
            'entityTypeId' => 2,
            'order' => ['id' => 'ASC'],
            'filter' => [
                'categoryId' => $this->direction,
                '>=createdTime' => $this->date_start,
                '<=createdTime' => $this->date_end,
            ],
            'select' => ['id', 'title', 'utmSource', 'utmMedium', 'utmContent', 'utmTerm', 'sourceId', 'searchContent', 'typeId'],
        ];
        $result = [];
        do {
            $queryData = http_build_query($data);
            $answer = json_decode($this->post($method, $queryData));
            foreach ($answer->result->items as $lead){
                $result[] = (array)$lead;
            }
        }while(isset($answer->next));

        return $result;
    }

    private function post($method, $queryData)
    {

        $queryUrl = 'https://' . $this->subdomen . '.bitrix24.ru/rest/' . $this->user_id . '/' . $this->codewebhook . '/' . $method . '.json';
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

    public function setDirection($direction){
        $this->direction = $direction;
    }

    private function multineedle_stripos($haystack, $needles, $offset=0) {

        foreach($needles as $needle) {
            if (stripos($haystack, $needle, $offset)!==false) return true;
        }

        return false;
    }

    private function search_phone($text){
        $data = preg_replace ("/\s+/u"," ",$text);//убрали лишние разделители
        $tokens = preg_split("/\s/u",$data); //получили массив лексем
        $tokens = array_filter ($tokens, function ($item) {return !empty($item);} );//отфильтровали пустые лексемы
        $len = count($tokens);

        for ($i=0; $i<$len; $i++) { //цикл по лексемам
            if (isset($tokens[$i]) && $this->is_phone($tokens[$i])) {
                return $tokens[$i];
            }
        }
        return null;
    }
    private function is_phone ($s) { //проверка лексемы на соответствие шаблону
        return preg_match("/^(7)(\d{10})$/u",$s) ? 1 : 0;
    }
}

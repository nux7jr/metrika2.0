<?php

namespace App\Http\Helpers\PipeFiles;

class CountLeads{

    public $leads;

    private $warm_floors_xl_pipe = [
        // 'otoplenie-otoplenie.ru',
        'xl-pipe.ru',
        'xlpipe.kz',
        'xlpipe-belarus.by',
    ];
    private $warm_floors_daewoo = [
        'daewoo-enertec.com',
    ];
    private $engineering = [
        'отопить.рф/engineering',
        'xn--h1alaend4e.xn--p1ai/engineering'
    ];
    private $boilers = [
        'переко.рф',
        'xn--e1aapqbh.xn--p1ai',
        'defro-russia.ru',
        'kotel-koloss.ru',
        'отопить.рф',
        'xn--h1alaend4e.xn--p1ai'
    ];
    private $boilers_krsk = [
        'otoplenie-otoplenie.ru/message-5'
    ];
    private $boilers_msk = [
        'otoplenie-otoplenie.ru/kotly_msk'
    ];
    private $boilers_promkotel = [
        'kotel-koloss.ru/promkotel',
        'xn--h1alaend4e.xn--p1ai/promkotel',
        'отопить.рф/promkotel',
    ];

    private $auto = [
        'tiksanauto.ru/LP1'
    ];

    private $auto_main = [
        'tiksanauto.ru',
    ];

    private $test_leads = [
        'test', 'Test', 'TEST', 'Тест', 'тест', 'ТЕСТ', 'тсет'
    ];

    public function __construct($leads){
        $this->leads = $leads;
    }

    public function prepare_summary_report($noutm = false){

        $summary = [];
        foreach ($this->leads as $lead){
            $lead[3] = $this->replace_phone($lead[3]);//приводим телефон к единому виду
            if ($lead[1] === 'Красноярск'){
                if ($this->count_leads($lead[9], $this->warm_floors_xl_pipe, $noutm ? 'dealer' : 'with_utm') && !$this->is_test($lead[4].$lead[6], $this->test_leads)) {
                    $summary['Теплые полы']['xl-pipe']['Красноярск'][] = $lead;
                }
                if ($this->count_leads($lead[9], $this->warm_floors_daewoo, $noutm ? 'dealer' : 'with_utm') && !$this->is_test($lead[4].$lead[6], $this->test_leads)) {
                    $summary['Теплые полы']['daewoo']['Красноярск'][] = $lead;
                }
                if (($this->count_leads($lead[9], $this->boilers, 'dealer') || $this->count_leads($lead[9],$this->boilers_krsk, 'dealer')) &&
                    !$this->is_test($lead[4].$lead[6], $this->test_leads) &&
                    !$this->multineedle_stripos($lead[9],['promkotel']))
                {
                    if ($this->multineedle_stripos($lead[9], $this->engineering)){
                        $summary['Котлы Инжиниринг']['Красноярск'][] = $lead;
                    }else{
                        $summary['Котлы']['Красноярск'][] = $lead;
                    }
                }
                if ($this->count_leads($lead[9], $this->auto, $noutm ? 'dealer' : 'with_utm') && !$this->is_test($lead[4].$lead[6], $this->test_leads)) {
                    $summary['tiksan_auto'][0][] = $lead;
                }
                if (!$this->count_leads($lead[9], $this->auto, $noutm ? 'dealer' : 'with_utm')
                    && !$this->is_test($lead[4].$lead[6], $this->test_leads)
                    && $this->count_leads($lead[9], $this->auto_main, $noutm ? 'dealer' : 'with_utm')) {
                    $summary['tiksan_auto'][1][] = $lead;
                }
                if ($this->count_leads($lead[9], $this->boilers_promkotel, $noutm ? 'dealer' : 'with_utm') && !$this->is_test($lead[4].$lead[6], $this->test_leads)) {
                    $summary['Промкотлы']['Красноярск'][] = $lead;
                }
            }elseif ($lead[1] === 'Москва'){
                if ($this->count_leads($lead[9], $this->warm_floors_xl_pipe, $noutm ? 'dealer' : 'with_utm') && !$this->is_test($lead[4].$lead[6], $this->test_leads)) {
                    $summary['Теплые полы']['xl-pipe']['Москва'][] = $lead;
                }
                if ($this->count_leads($lead[9], $this->warm_floors_daewoo, $noutm ? 'dealer' : 'with_utm') && !$this->is_test($lead[4].$lead[6], $this->test_leads)) {
                    $summary['Теплые полы']['daewoo']['Москва'][] = $lead;
                }
                if (($this->count_leads($lead[9], $this->boilers, 'dealer') || $this->count_leads($lead[9],$this->boilers_msk, 'dealer')) &&
                    !$this->is_test($lead[4].$lead[6], $this->test_leads) &&
                    !$this->multineedle_stripos($lead[9],['promkotel']))
                {
                    $summary['Котлы']['Москва'][] = $lead;
                }
                if ($this->count_leads($lead[9], $this->auto, $noutm ? 'dealer' : 'with_utm') && !$this->is_test($lead[4].$lead[6], $this->test_leads)) {
                    $summary['tiksan_auto'][0][] = $lead;
                }
                if (!$this->count_leads($lead[9], $this->auto, $noutm ? 'dealer' : 'with_utm')
                    && !$this->is_test($lead[4].$lead[6], $this->test_leads)
                    && $this->count_leads($lead[9], $this->auto_main, $noutm ? 'dealer' : 'with_utm')) {
                    $summary['tiksan_auto'][1][] = $lead;
                }
            }else{
                if ($this->count_leads($lead[9], $this->warm_floors_xl_pipe, 'dealer') && !$this->is_test($lead[4].$lead[6], $this->test_leads)) {
                    $summary['Теплые полы']['xl-pipe']['Диллеры'][] = $lead;
                }
                if ($this->count_leads($lead[9], $this->warm_floors_daewoo, 'dealer') && !$this->is_test($lead[4].$lead[6], $this->test_leads)) {
                    $summary['Теплые полы']['daewoo']['Диллеры'][] = $lead;
                }
                if ($this->count_leads($lead[9], $this->auto, $noutm ? 'dealer' : 'with_utm') && !$this->is_test($lead[4].$lead[6], $this->test_leads)) {
                    $summary['tiksan_auto'][0][] = $lead;
                }
                if (!$this->count_leads($lead[9], $this->auto, $noutm ? 'dealer' : 'with_utm')
                    && !$this->is_test($lead[4].$lead[6], $this->test_leads)
                    && $this->count_leads($lead[9], $this->auto_main, $noutm ? 'dealer' : 'with_utm')) {
                    $summary['tiksan_auto'][1][] = $lead;
                }
                if ($this->count_leads($lead[9], $this->boilers_promkotel, $noutm ? 'dealer' : 'with_utm') && !$this->is_test($lead[4].$lead[6], $this->test_leads)) {
                    $summary['Промкотлы']['Красноярск'][] = $lead;
                }
            }

        }

        if (isset($summary['Теплые полы']['xl-pipe'])){
            foreach ($summary['Теплые полы']['xl-pipe'] as &$floorItem){
                $floorItem = $this->unique_multidim_array($floorItem, 3);
            }
        }

        if (isset($summary['Теплые полы']['daewoo'])){
            foreach ($summary['Теплые полы']['daewoo'] as &$floorItem){
                $floorItem = $this->unique_multidim_array($floorItem, 3);
            }
        }

        if (isset($summary['Котлы'])){
            foreach ($summary['Котлы'] as &$boilerItem){
                $boilerItem = $this->unique_multidim_array($boilerItem, 3);
            }
        }

        if (isset($summary['Промкотлы'])){
            foreach ($summary['Промкотлы']as &$promkotel){
                $promkotel = $this->unique_multidim_array($promkotel, 3);
            }
        }
        if (isset($summary['tiksan_auto'])){
            foreach ($summary['tiksan_auto'] as &$auto){
                $auto = $this->unique_multidim_array($auto, 3);
            }
        }
        if (isset($summary['Котлы Инжиниринг'])){
            foreach($summary['Котлы Инжиниринг'] as &$engineering){
                $engineering = $this->unique_multidim_array($engineering, 3);
            }
        }

        return $summary;

    }

    private function is_test($name, array $test){
        if($this->multineedle_stripos($name, $test)){
            return true;
        }

        return false;
    }

    private function count_leads($referer, $needle, $key = null){

        //для диллеров отдаем все заявки
        if ($key === 'dealer'){
            return $this->multineedle_stripos($referer,$needle);
        }

        //проверяем рекламная ли заявка
        if ($this->multineedle_stripos($referer, ['utm_source'])){
            return $this->multineedle_stripos($referer,$needle);
        }

        return false;
    }

    private function multineedle_stripos($haystack, $needles, $offset=0) {
        foreach($needles as $needle) {
            if (stripos($haystack, $needle, $offset)!==false) return true;
        }

        return false;
    }

    //Приводим телефоны к одному виду
    private function replace_phone($phone){

        try {
            if (!empty($phone)){
                $phone = str_replace(array('(', ' ', '-', ')', '+'), '', $phone);
                $phone[0] === '8' ? $phone[0] = '7' : null;
            }
        }catch (\ErrorException $error){
            if ($error->getMessage() == 'Uninitialized string offset 0'){
                return $phone;
            }
        }

        return $phone;
    }

    private function unique_multidim_array($array, $key) {
        $temp_array = array();
        $key_array = array();

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array) && strlen($val[$key])>4) {
                array_unshift($key_array,$val[$key]);
                array_unshift($temp_array, $val);
            }
        }

        return array_reverse($temp_array);
    }

}

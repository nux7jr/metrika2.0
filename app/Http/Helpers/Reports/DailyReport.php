<?php

namespace App\Http\Helpers\Reports;

use App\Http\Helpers\PipeFiles\B24DailyReport;
use App\Http\Helpers\PipeFiles\CountLeads;
use App\Http\Helpers\PipeFiles\GetLeads;
use App\Http\Helpers\PipeFiles\Leads;
use AXP\YaMetrika\Client;
use AXP\YaMetrika\YaMetrika;
use Exception;

class DailyReport
{
    private $path = Leads::pathFileWithLeads;

    private array $leads;
    private string $date;
    private string $YMDate;
    private CountLeads $countLeads;
    private B24DailyReport $bitrixAPI;
    private array $settings = [
        'subdomen' => 'tiksan-group',
        'userId' => '1',
        'codewebhook' => 't7wy1lx66dizck10',
        'direction' => '46',
        'date' => 'date in format d.m.Y'
    ];

    /**
     * @param string $date
     * @throws Exception
     */
    public function __construct(string $date)
    {
        $this->YMDate = date('Y-m-d',strtotime($date));
        $this->settings['date'] = $date;
        $this->date = date('d.m.y',strtotime($date));
        $leads = new GetLeads($this->path);
        $temp_leads = $leads->get_leads_by_date($date,$date);
        if (empty($temp_leads)){
            throw new Exception('В файле с лидами нет данных');
        }
        if (empty($temp_leads[$this->date])){
            throw new Exception('В файле с лидами нет данных на текущую дату: '.$this->date);
        }
        $this->leads = $temp_leads[$this->date];
        unset($temp_leads);
        $this->countLeads = new CountLeads($this->leads);
        $this->bitrixAPI = new B24DailyReport($this->settings);
    }

    public function getJSON(): string
    {
        $result = $this->countLeads->prepare_summary_report();
        $result['Малые этажи'] = $this->bitrixAPI->getDataFromEtaji();
        $this->bitrixAPI->setDirection(94);//вопронка СТП Развитие
        $result['Диллерство полы'][] = $this->bitrixAPI->getDataFromSTP();
        $this->bitrixAPI->setDirection(92);//Воронка NF Развитие
        $result['Нанофайбер франшиза СНГ'][] = $this->bitrixAPI->getDataFromNFSNG();
        $this->bitrixAPI->setDirection(112);//Воронка NF World
        $result['Нанофайбер франшиза Зарубежные страны'][] = $this->bitrixAPI->getDataFromNFWorld();

        $result_count = $this->getResultCount($result);
        $noutm_report = $this->countLeads->prepare_summary_report($noutm = true);
        $this->bitrixAPI->setDirection(46);//Воронка Продаж
        $noutm_report['Малые этажи'] = $this->bitrixAPI->getDataFromEtajiNoUtm();
        $noutm_report['Диллерство полы'] = $result['Диллерство полы'];
        $noutm_report['Нанофайбер франшиза СНГ'] = $result['Нанофайбер франшиза СНГ'];
        $noutm_report['Нанофайбер франшиза Зарубежные страны'] = $result['Нанофайбер франшиза Зарубежные страны'];
        $result_count_no_utm = $this->getResultCount($noutm_report);

        //        $resultJSON['header'] = $this->getHeaders();
        $resultJSON[0] = array_merge(['state' => 'Рекламные'], $result_count);
        $resultJSON[1] = array_merge(['state' => 'Все'], $result_count_no_utm);

//        $resultJSON[2] = [
//            'state' => 'Сайты',
//            'krsk_foolrs_xl_pipe' => 'xl-pipe все поддомены',
//        ];
//        $resultJSON[3] = [
//            'state' => 'Звонки',
//            'krsk_foolrs_xl_pipe' => $summaryCalls,
//        ];
        return json_encode($resultJSON);
    }

    private function getResultCount(array $report): array
    {
        return $result_count = [
                    'krsk_foolrs_xl_pipe' => count($report['Теплые полы']['xl-pipe']['Красноярск'] ?? []),
                    'krsk_foolrs_daewoo' => count($report['Теплые полы']['daewoo']['Красноярск'] ?? []),
                    'msk_foolrs_xl_pipe' => count($report['Теплые полы']['xl-pipe']['Москва'] ?? []),
                    'msk_foolrs_daewoo' => count($report['Теплые полы']['daewoo']['Москва'] ?? []),
                    'dealers_foolrs_xl_pipe' => count($report['Теплые полы']['xl-pipe']['Диллеры'] ?? []),
                    'dealers_foolrs_daewoo' => count($report['Теплые полы']['daewoo']['Диллеры'] ?? []),
                    'krsk_boilers' => count($report['Котлы']['Красноярск'] ?? []),
                    'krsk_promboilers' => count($report['Промкотлы']['Красноярск'] ?? []),
                    'msk_boilers' => count($report['Котлы']['Москва'] ?? []),
                    'dealers_franchisees' => count($report['Диллерство полы'][0] ?? []),
                    'nanofiber_franchisees_sng' => count($report['Нанофайбер франшиза СНГ'][0] ?? []),
                    'nanofiber_franchisees_world' => count($report['Нанофайбер франшиза Зарубежные страны'][0] ?? []),
                    'krsk_etaji' => count($report['Малые этажи']['Красноярск'] ?? []),
                    'dealers_etaji' => (count($report['Малые этажи']['Тюмень'] ?? []) + count($report['Малые этажи']['Иркутск'] ?? []) + count($report['Малые этажи']['Владивосток'] ?? []) + count($report['Малые этажи']['Пермь'] ?? []) + count($report['Малые этажи']['Екатеринбург'] ?? [])),
                    'tumen_etaji' => count($report['Малые этажи']['Тюмень'] ?? []),
                    'irkutsk_etaji' => count($report['Малые этажи']['Иркутск'] ?? []),
                    'vladivostok_etaji' => count($report['Малые этажи']['Владивосток'] ?? []),
                    'perm_etaji' => count($report['Малые этажи']['Пермь'] ?? []),
                    'ekb_etaji' => count($report['Малые этажи']['Екатеринбург'] ?? []),
                    'barnaul_etaji' => count($report['Малые этажи']['Барнаул'] ?? []),
                    'tiksan_auto' => count($report['tiksan_auto'][0] ?? []),
                    'tiksan_auto_main' => count($report['tiksan_auto'][1] ?? []),
                ];
    }

    private function getHeaders(): array
    {
        return $result_header = [
            'krsk_foolrs_xl_pipe' => 'Красноярск ТП xl-pipe',
            'krsk_foolrs_daewoo' => 'Красноярск ТП daewoo',
            'msk_foolrs_xl_pipe' => 'Москва ТП xl-pipe',
            'msk_foolrs_daewoo' => 'Москва ТП daewoo',
            'dealers_foolrs_xl_pipe' => 'Дилеры ТП xl-pipe',
            'dealers_foolrs_daewoo' => 'Дилеры ТП daewoo',
            'krsk_boilers' => 'Красноярск Котлы',
            'krsk_promboilers' => 'Красноярск промкотлы',
            'msk_boilers' => 'Москва Котлы',
            'dealers_franchisees' => 'Дилерство полы',
            'nanofiber_franchisees_sng' => 'NanoFiber Франшиза СНГ',
            'nanofiber_franchisees_world' => 'NanoFiber Франшиза Зарубежные страны',
            'krsk_etaji' => 'Малые этажи Красноярск',
            'dealers_etaji' => 'Малые этажи Регионы',
            'tumen_etaji' => 'Малые этажи Тюмень',
            'irkutsk_etaji' => 'Малые этажи Иркутск',
            'vladivostok_etaji' => 'Малые этажи Владивосток',
            'perm_etaji' => 'Малые этажи Пермь',
            'ekb_etaji' => 'Малые этажи Екатеринбург',
            'barnaul_etaji' => 'Малые этажи Барнаул',
            'tiksan_auto' => 'Тиксан авто только LP1',
            'tiksan_auto_main' => 'Тиксан авто Федеральный',
        ];
    }
}

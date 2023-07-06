<?php

namespace App\Http\Helpers\Reports;

class GetCSVSpreadsheets{

    private $url;
    private static array $partners_to_site = [
        'ИП Булаев' => 'Нижний Новгород',
        'ИП Сорокин Игорь Николаевич' => 'Санкт-Петербург..',
        'Проект-А' => ['Ижевск' => 'Ижевск', 'Пермь' => 'Пермь'],
        'ИП Дзугаева ' => 'Владикавказ',
        'ИП Иванова' => 'Южно-Сахалинск',
        'ООО Хамелеон' => 'Ярославль',
        'ИП Черноок' => 'Благовещенск',
        'ООО ТК Спектрум' => 'Екатеринбург.',
        'ИП Липовской' => 'Севастополь',
        'ИП Барашева/Сибстройцетнр' => 'Абакан',
        'ООО "ПЛЮС АЙ ТИ"' => 'Воронеж',
        'ИП Зайцев' => 'Архангельск',
        'ИП Палков А.В.' => 'Тверь',
        'ИП Жилкин' => 'Кызыл',
        'ИП Насыков' => ['Уфа' => 'Уфа', 'Оренбург' => 'Оренбург'],
        'ИП Мельник ВВ' => 'Самара.',
        'ИП Сейфотов' => 'Новороссийск',
        'Клевер Солушнс' => 'Якутск',
        'ООО Тандем 2000' => 'Улан-Удэ..',
        'ГК МАКСИМАЛ/ ИП Малыгин' => 'Кемерово',
        'ИП Рассадина' => 'Вологда',
        'Дэу Энертек' => ['Санкт-Петербург.' => 'Санкт-Петербург.', 'Петрозаводск' => 'Петрозаводск'],
        'Жилэлектросервис' => 'Иркутск.',
        'Акцент, ООО' => 'Сочи',
        'ООО Профилюкс' => 'Рыбинск',
        'ИП Османов Р.С.' => 'Симферополь',
        'ТД "Сибспецстрой"/Чуднов' => 'Новокузнецк',
        'Сантехсистема' => 'Челябинск',
        'ООО Хотлидер/ИП Бортко' => 'Мурманск',
        'ИП Сычев' => 'Липецк',
        'ИП Плотников А.О.' => 'Саратов',
        'Компания Франкосиб' => 'Новосибирск.',
        'СИБ ХАУС (Бывший "Цитрин")' => 'Новосибирск..',
        'ООО Век Комфорта' => 'Улан-Удэ.',
        'ИП Усманов' => 'Улан-Удэ...',
        'ИП Тарасова Л.Я.' => 'Псков',
        'ИП Кондауров СН' => 'Хабаровск',
        'Стройподрят' => 'Калининград',
        'ИП Комеров' => 'Чита',
    ];

    public function __construct(string $id_spreadsheet_file, string $format_export, int $id_list_spreadsheet, string $need_range)
    {
        $this->url = "https://docs.google.com/spreadsheets/d/{$id_spreadsheet_file}/export?format={$format_export}&id=$id_spreadsheet_file&gid={$id_list_spreadsheet}";
        if (!empty($need_range)){
            $this->url .= "&range={$need_range}";
        }
    }

    public function get_budget_from_google_spreadsheet_start_this_year(){

        $csv = file_get_contents($this->url);
        $csv = explode("\r\n", $csv);

        $csv = array_map('str_getcsv', $csv);
        $csv = array_reverse($csv);
        $date_start = strtotime('first day of January');
        $date_end = strtotime('today');

        $need_pay = [];
        foreach ($csv as $pay){
            $date = strtotime($pay[8]);
            if ($date === false){
                continue;
            }
            preg_match('/\d[\d|\s]+/ui',$pay[15], $matches);
            $sum_per_week = str_replace(' ','',$matches[0]);
            if ($date > $date_start && $date < $date_end && $sum_per_week[0] != null && isset(self::$partners_to_site[$pay[1]])) {
                {
                    if (!is_array(self::$partners_to_site[$pay[1]])){
                        if (!isset($need_pay[$city = self::$partners_to_site[$pay[1]]])) {
                            $need_pay[$city = self::$partners_to_site[$pay[1]]] = (int)$sum_per_week;
                        }
                    }
                    else{
                        if (!isset($need_pay[$city = self::$partners_to_site[$pay[1]][$pay[0]]])){
                            $need_pay[$city = self::$partners_to_site[$pay[1]][$pay[0]]] = (int)$sum_per_week;
                        }
                    }
                }
            }
            if ($date < $date_start){
                break;
            }
        }
        //у Санкт-Петербург... сейчас постоянный бюджет 25000р
        $need_pay['Санкт-Петербург...'] = 25000;

        return $need_pay;
    }

    /**
     * @return array
     */
    public static function getSiteToPartners(): array
    {
        $result = [];
        foreach (self::$partners_to_site as $partner => $city){
            if (is_array($city)){
                foreach ($city as $value){
                    $result[$value] = $partner;
                }
            }else{
                $result[$city] = $partner;
            }
        }
        return $result;
    }
}

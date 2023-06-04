<?php

namespace App\Http\Helpers\PipeFiles;

use Carbon\Carbon;

/**
 * this code need to rework
 */
class ReworkerLeads
{

    private $leads;

    public function __construct($leads)
    {
        $this->leads = $leads;
    }

    public function unique_leads_by_day()
    {
        return $this->get_unique($this->leads);
    }

    private function detect_encoding($string)
    {
        static $list = array('utf-8', 'windows-1251', 'KOI8-R', 'ISO-8859-5');
        foreach ($list as $item) {
            $sample = iconv($item, $item, $string);
            if (md5($sample) == md5($string))
                return $item;
        }
        return null;
    }

    private function get_unique($leads)
    {
        $leads_by_day_and_site = [];

        foreach ($leads as $date_lead => $date) {
            foreach ($date as $lead) {
                $arr_date_time = $this->str_to_arr_date_and_time($lead[0]);
                $rework_lead['DATE'] = Carbon::createFromTimestamp(strtotime($arr_date_time[0]))->isoFormat('DD/MM/YYYY');
                // $rework_lead['DATE'] = strtotime($arr_date_time[0]);
                $rework_lead['TIME'] = $arr_date_time[1];
                $rework_lead['CITY'] = !empty($lead[1]) ? $lead[1] : 'Без города';
                $rework_lead['PARTNER'] = !empty($lead[2]) ? $lead[2] : '-';
                $rework_lead['PHONE'] = $this->replace_phone($lead[3]);
                $rework_lead['EMAIL'] = !empty($lead[4]) ? $lead[4] : '-';
                $rework_lead['NAME'] = !empty($lead[6]) ? $lead[6] : '-';
                $rework_lead['TYPE'] = !empty($lead[7]) ? $lead[7] : '-';
                $rework_lead['COMMENT'] = !empty($lead[8]) ? $lead[8] : '-';
                if (isset($lead[9])) {
                    $arr_data_url = $this->parse_url_to_site_and_utm($lead[9]);
                    $rework_lead['REFERER'] = $lead[9];
                }
                $leads_by_day_and_site[$date_lead][$arr_data_url['SITE']][$rework_lead['PHONE']][] = array_merge($rework_lead, $arr_data_url);
            }
        }
        $leads_unique = [];
        $id = 0;
        foreach ($leads_by_day_and_site as &$date) {
            foreach ($date as &$site) {
                foreach ($site as $phone => &$leads) {
                    if (count($leads) > 1) {
                        $check_utm = false;
                        $last_lead = [];
                        foreach ($leads as $lead) {
                            if ($lead['UTM_SOURCE'] !== null) {
                                $check_utm = true;
                                $leads = $lead;
                                break;
                            }
                            $last_lead = $lead;
                        }
                        if (!$check_utm) {
                            $leads = $last_lead;
                        }
                    } else {
                        $leads = $leads[0];
                    }
                    $leads['ID'] = $id;
                    $id++;
                    $leads_unique[] = $leads;
                }
            }
        }

        return $leads_unique;
    }

    private function multineedle_stripos($haystack, $needles, $offset = 0)
    {
        foreach ($needles as $needle) {
            if (stripos($haystack, $needle, $offset) !== false) return true;
        }

        return false;
    }

    //Приводим телефоны к одному виду
    private function replace_phone($phone)
    {
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

    private function str_to_arr_date_and_time($input_date)
    {
        $datetime = explode(' ', $input_date);
        $split = explode('.', $datetime[0]);
        $datetime[0] = $split[0] . '.' . $split[1] . '.20' . $split[2];

        return $datetime;
    }

    private function unique_multidim_array($array, $key)
    {
        $temp_array = array();
        $key_array = array();

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array) && strlen($val[$key]) > 4) {
                array_unshift($key_array, $val[$key]);
                array_unshift($temp_array, $val);
            }
        }

        return array_reverse($temp_array);
    }

    private function parse_url_to_site_and_utm($url)
    {
        if ($url === null) {
            $result['UTM_SOURCE'] = 'Без UTM';
            $result['UTM_MEDIUM'] = 'Без UTM';
            $result['UTM_CAMPAIGN'] = 'Без UTM';
            $result['UTM_TERM'] = 'Без UTM';
            $result['UTM_CONTENT'] = 'Без UTM';
            $result['SITE'] = 'Сайт не указан';

            return $result;
        }
        $utm_metka_dirty = parse_url($url);

        if (isset($utm_metka_dirty['query'])) {
            parse_str($utm_metka_dirty['query'], $utm_metka);
            $utm_metka2 = str_replace("|", "-", $utm_metka);
        }


        $result['UTM_SOURCE'] = isset($utm_metka2['utm_source']) ? $utm_metka2['utm_source'] : 'Без UTM';
        $result['UTM_MEDIUM'] = isset($utm_metka2['utm_medium']) ? $utm_metka2['utm_medium'] : 'Без UTM';
        $result['UTM_CAMPAIGN'] = isset($utm_metka2['utm_campaign']) ? $utm_metka2['utm_campaign'] : 'Без UTM';
        $result['UTM_TERM'] = isset($utm_metka2['utm_term']) ? $utm_metka2['utm_term'] : 'Без UTM';
        $result['UTM_CONTENT'] = isset($utm_metka2['utm_content']) ? $utm_metka2['utm_content'] : 'Без UTM';

        if (!isset($utm_metka_dirty['host'])) {
            $result['SITE'] = 'Сайт не указан';
            return $result;
        }
        $dirty_host = explode('.', $utm_metka_dirty['host']);
        $dns = $dirty_host[count($dirty_host) - 1];
        if (count($dirty_host) - 2 !== -1) {
            $domain = $dirty_host[count($dirty_host) - 2];
        } else {
            $domain = '';
        }
        $result['SITE'] = $domain . '.' . $dns;
        if ($result['SITE'] === '.') {
            $result['SITE'] = 'Сайт не указан';
        } elseif ($result['SITE'] === 'xn--h1alaend4e.xn--p1ai') {
            $result['SITE'] = 'отопить.рф';
        } elseif ($result['SITE'] === 'xn--e1aapqbh.xn--p1ai') {
            $result['SITE'] = 'переко.рф';
        } elseif ($result['SITE'] === 'xn-----nlckhckc0addd6abk0ll.xn--90ais') {
            $result['SITE'] = 'купить-теплый-пол.бел';
        } elseif ($result['SITE'] == 'tiksanauto.ru') {
            $result['SITE'] = strpos($url, 'tiksanauto.ru/LP1') ? 'tiksanauto.ru\LP1' : 'tiksanauto.ru';
        }

        return $result;
    }
}

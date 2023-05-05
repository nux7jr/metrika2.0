<?php

namespace App\Http\Helpers\Reports;

use App\Http\Helpers\Reports\GetCSVSpreadsheets;

class WeekReport
{
    private  $connenct;

    public  function __construct(){
        $this->connenct = mysqli_connect('188.246.224.99', 'admin_ometrika', 'C3qUD6vtaw', 'admin_old_metrika', '3306');
        $this->connenct->set_charset('utf8');
    }
    public function getJSON($date_s,$date_to): string{
        $date_s = date('Y-m-d',strtotime($date_s));
        $date_to = date('Y-m-d',strtotime($date_to));

        $period_in_seconds = strtotime($date_to)-strtotime($date_s);//нужен для того чтобы узнать период больше недели или нет

        $budget = $this->getGoogleData();

        $date_plan = date('Y-m-01', strtotime($date_to . '-3 months'));//3 месяца назад от начала месяца
        $date_start_month = date('Y-m-01', strtotime($date_to));//первый день текущего месяца
        $query = "SELECT
            main.city,main.count_phone,main.cost_phone,main.count_phone_yandex,
            main.curency_phone,main.cost_phone_yandex,balance.`balance_yandex`, plan_lead, fact_lead, fact_lead_count
            FROM `adminpanel_report_week_new` balance JOIN
            (SELECT     `city`,
                    SUM(`count_phone`) AS `count_phone`,
                    SUM(`curency_full`)/SUM(`count_phone`) AS `cost_phone`,
                    SUM(`count_phone_yandex`) AS `count_phone_yandex`,
                    SUM(`curency_yandex`) AS `curency_phone`,
                    SUM(`curency_yandex`)/SUM(`count_phone_yandex`) AS `cost_phone_yandex`
            FROM `adminpanel_report_week_new`
            WHERE `date` >= '$date_s' AND `date` <= '$date_to'
            GROUP BY `city` ORDER BY `cost_phone_yandex` DESC) main ON main.city = balance.city
                JOIN
                    (SELECT plan.city,
                    SUM(plan.curency_yandex)/SUM(plan.count_phone_yandex) AS plan_lead,
                    fact.fact_lead, fact.count_phone_yandex AS fact_lead_count
                    FROM adminpanel_report_week_new plan JOIN
                        (
                            SELECT city,SUM(curency_yandex)/SUM(count_phone_yandex) AS fact_lead, SUM(count_phone_yandex) AS count_phone_yandex
                            FROM adminpanel_report_week_new
                            WHERE `date` >='$date_start_month' AND `date` <= '$date_to'
                            GROUP BY `city`
                        ) fact  ON plan.city = fact.city
                    WHERE plan.`date` >= '$date_plan' AND plan.`date` <= '$date_to'
                    GROUP BY plan.`city` ORDER BY `plan_lead` DESC) test ON main.city = test.city
            WHERE balance.`date` = '$date_to'
            ORDER BY main.`cost_phone_yandex` DESC";

        $result = $this->connenct->query($query);

        header('Content-Type: text/html; charset=utf-8');;

        $first = [];
        $counter = 0;
        while ($row = $result->fetch_row()) {
            $count = count($row);
            for ($i = 0; $i < $count; $i++){
                if ($i === 0){
                    $first[$counter]['City'] = $row[$i];
                }
                if ($i === 1){
                    $first[$counter]['Summary_leads'] = $row[$i];
                }
                if ($i === 2){
                    $first[$counter]['Summary_price_lead'] = $row[$i];
                }
                if ($i === 3){
                    $first[$counter]['Yandex|Count_leads'] = $row[$i];
                }
                if ($i === 4){
                    $first[$counter]['Yandex|Cost_period'] = $row[$i];
                }
                if ($i === 5){
                    $first[$counter]['Yandex|Price_lead'] = $row[$i];
                }
                if ($i === 6){
                    $first[$counter]['Yandex|Surplus_nds'] = $row[$i];
                }
                if ($i === 7){
                    $first[$counter]['Price_plan'] = $row[$i];
                }
                if ($i === 8){
                    $first[$counter]['Price_fact'] = $row[$i];
                }
                if ($i === 9){
                    $first[$counter]['Count_fact'] = $row[$i];
                }
            }
            $counter++;
        }
        $city_to_partner = GetCSVSpreadsheets::getSiteToPartners();
        foreach ($first as &$row) {
            $budget_month = 0;
            $plan_leads = 0;
            $fact_leads = 0;

            if (isset($budget[$row['City']])){
                $row['Drawback/Surplus'] = $row['Yandex|Cost_period'] > $budget[$row['City']] ? '-' . round($row['Yandex|Cost_period'] - $budget[$row['City']], 0) : round($budget[$row['City']] - $row['Yandex|Cost_period'], 0);
                $row['Budget_per_week'] = $budget[$row['City']];
                $budget_month = $budget[$row['City']] / 7 * 30;
            }else{
                $row['Drawback/Surplus'] = 0;
                $row['Budget_per_week'] = 0;
            }

            $plan_leads = $row['Price_fact'] != null ? (int)$row['Price_fact'] > 0 ? round($row['Price_fact'], 0) : $row['Price_fact'] : 0;
            $fact_leads = $row['Count_fact'];

            foreach ($row as &$item){
                $item = $item != null ? (int)$item > 0 ? (int)round($item, 0) : $item : 0;

            }

            $row['Count_plan'] =  $plan_leads != 0 ? !is_nan(round($budget_month / $plan_leads, 0)) ? !is_infinite(round($budget_month / $plan_leads, 0)) ? round($budget_month / $plan_leads, 0) : 0 : 0 : 0;
            isset($city_to_partner[$row['City']]) ? $row['City'] = $row['City'] . ' | ' . $city_to_partner[$row['City']] : '';
        }

        return json_encode($first);
    }

    private function getGoogleData(){
        //Делаем запрос в гугл таблицу для получение Трат на неделю с НДС
        $id_spreadSheet = '1VYZ7HGGodqhMhMMqITyH94uMr6HhTZBlB62thIKk3I4';
        $format = 'csv';
        $id_list_spreadsheet = 1991993190;
        $need_range = 'A:P';
        $Spread = new GetCSVSpreadsheets($id_spreadSheet, $format, $id_list_spreadsheet, $need_range);
        $budget = $Spread->get_budget_from_google_spreadsheet_start_this_year();

        return $budget;
    }
}

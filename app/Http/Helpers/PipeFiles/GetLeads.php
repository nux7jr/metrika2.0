<?php

namespace App\Http\Helpers\PipeFiles;

class GetLeads
{

    public $path;

    public function __construct($input_path)
    {
        $this->path = $input_path;
    }

    public function get_leads_by_date($date_start, $date_end)
    {

        if (!isset($this->path)) {
            return false;
        }

        $handle = fopen($this->path, 'r');

        $period = $this->get_period($date_start, $date_end);

        $leads = [];
        while (!feof($handle)) {
            $lead = trim(fgets($handle));
            $explode_lead = explode(';', $lead);
            $lead_date = explode(' ', $explode_lead[0]);
            foreach ($period as $day) {
                if ($day === $lead_date[0]) {
                    $leads[$day][] = $explode_lead;
                }
            }
        }

        fclose($handle);

        return $leads;
    }

    public function get_leads_yesterday()
    {
        if (!isset($this->path)) {
            return false;
        }

        $handle = fopen($this->path, 'r');

        $date_yesterday = date('d.m.y', strtotime('yesterday'));

        $yesterday_leads = [];
        while (!feof($handle)) {
            $lead = trim(fgets($handle));
            $explode_lead = explode(';', $lead);
            $lead_date = explode(' ', $explode_lead[0]);

            if ($date_yesterday === $lead_date[0]) {
                $yesterday_leads[] = $explode_lead;
            }
        }

        fclose($handle);

        return $yesterday_leads;
    }

    private function get_period($start, $end, $format = 'd.m.y')
    {

        $day = 86400;

        $start = str_replace("'", '', $start);
        $end = str_replace("'", '', $end);

        $start = strtotime($start . ' -1 days');
        $end = strtotime($end . ' +1 days');
        $nums = round(($end - $start) / $day);
        $days = array();
        for ($i = 1; $i < $nums; $i++) {
            $days[] = date($format, ($start + ($i * $day)));
        }

        return $days;
    }
}

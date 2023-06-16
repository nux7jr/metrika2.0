<?php

namespace App\Http\Helpers\PipeFiles;

class GetLeads
{
    /**
     * @var string|null
     */
    public ?string $path;

    public function __construct($input_path)
    {
        $this->path = $input_path;
    }

    /**
     * @param $date_start
     * @param $date_end
     * @return bool|array
     */
    public function get_leads_by_date($date_start, $date_end): bool|array
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

    /**
     * @return bool|array
     */
    public function get_leads_yesterday(): bool|array
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

    /**
     * @param $start
     * @param $end
     * @param $format
     * @return array
     */
    private function get_period($start, $end, $format = 'd.m.y'): array
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

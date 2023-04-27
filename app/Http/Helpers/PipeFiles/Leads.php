<?php

namespace App\Http\Helpers\PipeFiles;

class Leads
{
    private const pathFileWithLeads = 'C:/OSPanel/alldealers_leads.txt';


    public function protect($rows, $date_on, $date_off)
    {
        // $answer = $this->getLead($rows, $date_on, $date_off);
        $answer = $this->getLeadsFromFile($date_on, $date_off);
        return $answer;
    }


    public function getLeadsFromFile($date_start, $date_end)
    {
        $getLeads = new GetLeads(self::pathFileWithLeads);
        $leads = $getLeads->get_leads_by_date($date_start, $date_end);
        $rework = new ReworkerLeads($leads);

        return $rework->unique_leads_by_day();
    }
}

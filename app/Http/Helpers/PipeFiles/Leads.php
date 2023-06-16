<?php

namespace App\Http\Helpers\PipeFiles;

class Leads
{
    public const pathFileWithLeads = '/home/admin/web/centr-polov.ru/public_html/upload/bd/alldealers_leads.txt';

    /**
     * @param $date_start
     * @param $date_end
     * @return array
     */
    public function getLeadsFromFile($date_start, $date_end) : array
    {
        $getLeads = new GetLeads(self::pathFileWithLeads);
        $leads = $getLeads->get_leads_by_date($date_start, $date_end);
        $rework = new ReworkerLeads($leads);

        return $rework->unique_leads_by_day();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Helpers\PipeFiles\GetLeads;
use App\Http\Helpers\Reports\DailyReport;
use App\Http\Helpers\Reports\WeekReport;
use Illuminate\Http\Request;
use App\Http\Helpers\PipeFiles\Leads;

class DataGridController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $date_on = empty($request->input('date_on')) ? date('YYYY-MM-DD', strtotime('today -7 days')) : $request->input('date_on');
            $date_off = empty($request->input('date_off')) ? date('YYYY-MM-DD', strtotime('today')) : $request->input('date_off');


            $date_on = $date_on;
            $date_off = $date_off;

            $lead = new Leads();
            $arr = $lead->protect("", $date_on, $date_off);
            $arr_to_json = [];
            foreach ($arr as $key => $item) {
                foreach ($item as $key1 => $item1) {
                    if (mb_detect_encoding($item1) != 'utf-8') {
                        $item1 = mb_convert_encoding($item1, 'utf-8');
                    }
                    $arr_to_json[$key][$key1] = $item1;
                }
            }


            $json = json_encode($arr_to_json, JSON_UNESCAPED_UNICODE);
            echo ($json);
        } catch (\Exception $error) {
            var_dump($error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function week(Request $request)
    {
        try {
            $date_on = empty($request->input('date_on')) ? date('YYYY-MM-DD', strtotime('today -7 days')) : $request->input('date_on');
            $date_off = empty($request->input('date_off')) ? date('YYYY-MM-DD', strtotime('today')) : $request->input('date_off');

            $Reporter = new WeekReport();
            return $Reporter->getJSON($date_on, $date_off);
        } catch (\Exception $error) {
            var_dump($error->getMessage());
        }
        return false;
    }

    public function daily(Request $request)
    {
        try {
            $date_on = empty($request->input('date_on')) ? date('d.m.Y', strtotime('yesterday')) : date('d.m.Y', strtotime($request->input('date_on')));


            $Reporter =  new DailyReport($date_on);
            return $Reporter->getJSON();
        } catch (\Exception $error) {
            var_dump($error->getMessage());
        }
        return false;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

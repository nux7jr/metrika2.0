<?php

namespace App\Http\Controllers;

use App\Http\Helpers\PipeFiles\GetLeads;
use App\Http\Helpers\PipeFiles\ParseLeadsFileAndB24;
use App\Http\Helpers\Reports\DailyReport;
use App\Http\Helpers\Reports\WeekReport;
use App\Http\Helpers\Structures\DealStages;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Helpers\PipeFiles\Leads;
use Illuminate\Support\Facades\DB;

class DataGridController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $carbon = Carbon::tomorrow();
            $date_off = empty($request->input('date_off')) ?
                $carbon->toDateTimeString() :
                $carbon->from($request->input('date_off'))->toDateTimeString();
            $date_on = empty($request->input('date_on')) ?
                $carbon->setDateTime(2020,7, 9,0,0)->toDateTimeString() :
                $carbon->from($request->input('date_on'))->toDateTimeString();
            $direction = $request->input('direction') !== null ?'C'.$request->input('direction').':%' : 'C116:%';
            $connection = DB::connection('pgsql');
            $deals = $connection->table('deals')->where([
                ['is_adv', '=', true],
                ['created_at', '>=', $date_on],
                ['updated_at', '<=', $date_off],
                ['url', '!=', null],
                ['stage_now', 'like', $direction],
            ])->get()->toArray();
            $count = count($deals);
            for ($i = 0; $i < $count; $i++){
                unset($deals[$i]->stage_changes);
                $normalized = explode(':',$deals[$i]->stage_now);
                if (!isset($normalized[1])){
                    $deals[$i]->stage_now = DealStages::getStageName(null, $normalized[0]);
                    $deals[$i]->direction = DealStages::getDirectionName($normalized[0]);
                    if ($deals[$i]->direction === false){
                        unset($deals[$i]);
                        continue;
                    }
                    foreach (DealStages::getStagesByDirection(null) as $key => $stage_name){
                        $deals[$i]->$stage_name = intval($normalized[0] === $key);
                    }
                }
                if (isset($normalized[1])){
                    $deals[$i]->stage_now = DealStages::getStageName($normalized[0], $normalized[1]);
                    $deals[$i]->direction = DealStages::getDirectionName($normalized[0]);
                    if ($deals[$i]->direction === false){
                        unset($deals[$i]);
                        continue;
                    }
                    foreach (DealStages::getStagesByDirection($normalized[0]) as $key => $stage_name){
                        $deals[$i]->$stage_name = intval($normalized[0] === $key);
                    }
                }
            }
            return json_encode(array_values($deals));
        }catch (\Exception $error){
            return $error->getMessage();
        }
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
            return (json_encode(['error'=>$error->getMessage()]));
        }
    }

    public function daily(Request $request)
    {
        try {
            $date_on = empty($request->input('date_on')) ? date('d.m.Y', strtotime('yesterday')) : date('d.m.Y', strtotime($request->input('date_on')));

            $Reporter =  new DailyReport($date_on);
            return $Reporter->getJSON();
        } catch (\Exception $error) {
            return (json_encode(['error'=>$error->getMessage()]));
        }
    }

    public function getCities(Request $request){
        if (!$request->user()->hasRole(['admin', 'super-admin'])){
            return '{"error":"access denied"}';
        }

        $all_cities = City::all()->toArray();
        foreach ($all_cities as &$city){
            $city = $city['name'];
        }
        return json_encode($all_cities);
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

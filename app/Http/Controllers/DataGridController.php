<?php

namespace App\Http\Controllers;

use App\Http\Helpers\PipeFiles\GetLeads;
use App\Http\Helpers\PipeFiles\ParseLeadsFileAndB24;
use App\Http\Helpers\Reports\DailyReport;
use App\Http\Helpers\Reports\WeekReport;
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
    public function index()
    {
        try {
            $deals = new ParseLeadsFileAndB24();
            $connection = DB::connection('pgsql');
            foreach ($deals::$deals as $deal){
                foreach ($deal as &$item){
                    if (!mb_check_encoding($item, 'utf8')){
                        $item = mb_convert_encoding($item, 'utf8');
                    }
                }
                $connection->reconnectIfMissingConnection();
                if ($connection->table('deals')->where('id','=',$deal['ID'])->exists()){
                    continue;
                }
                $is_adv = false;
                if (!empty($deal['UTM_SOURCE'])){
                    $deal['UTM_SOURCE'] !== 'TEST' && $deal['UTM_SOURCE'] !== 'Без UTM' ? $is_adv = true : '';
                }
                if (!empty($deal['UTM_MEDIUM'])){
                    $deal['UTM_MEDIUM'] !== 'TEST' && $deal['UTM_MEDIUM'] !== 'Без UTM' ? $is_adv = true : '';
                }
                if (!empty($deal['UTM_CAMPAIGN'])){
                    $deal['UTM_CAMPAIGN'] !== 'Без UTM' ? $is_adv = true : '';
                }
                if (!empty($deal['UTM_TERM'])){
                    $deal['UTM_TERM'] !== 'Без UTM' ? $is_adv = true : '';
                }
                if (!empty($deal['UTM_CONTENT'])){
                    $deal['UTM_CONTENT'] !== 'Без UTM' ? $is_adv = true : '';
                }
                $parsed = parse_url($deal['REFERER']);
                $parsed_send = '';
                !empty($parsed['host']) ? $parsed_send .= $parsed['host'] : '';
                !empty($parsed['path']) ? $parsed_send .= $parsed['path'] : '';
                $carbon = new Carbon($deal['DATE_CREATE']);
                $carbon->timezone(7);
                $date_create = $carbon->toDateTimeString();
                $carbon->timezone(3);
                $carbon->setDateTimeFrom($deal['DATE_MODIFY']);
                $carbon->timezone(7);
                $date_updated = $carbon->toDateTimeString();
                $connection->table('deals')->insert([
                    'id'            => $deal['ID'],
                    'is_adv'        => $is_adv,
                    'utm_source'    => $deal['UTM_SOURCE'] ?? '',
                    'utm_medium'    => $deal['UTM_MEDIUM'] ?? '',
                    'utm_campaign'  => $deal['UTM_CAMPAIGN'] ?? '',
                    'utm_content'   => $deal['UTM_CONTENT'] ?? '',
                    'utm_term'      => $deal['UTM_TERM'] ?? '',
                    'url'           => $parsed_send,
                    'stage_now'     => $deal['STAGE_ID'],
                    'income'        => floatval($deal['OPPORTUNITY']),
                    'currency'      => $deal['CURRENCY_ID'],
                    'phone'         => phoneFormatter($deal['PHONE']),
                    'created_at'    => $date_create,
                    'updated_at'    => $date_updated,
                ]);
            }
        }catch (\Exception $error){
            echo $error->getMessage();
        } finally {
            $stop = 1;
        }

        $stop = 1;
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

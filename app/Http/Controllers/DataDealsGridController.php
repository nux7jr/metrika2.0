<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Http\Helpers\Structures\DealStages;
use Illuminate\Support\Facades\DB;

class DataDealsGridController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = array();
            $directions = array(); array_push($directions, 'Все воронки');
            $stages = array();
            $utm = array();
            $columns = array( 
                //'0' => 'direction', 
                '0' => 'utm', '1' => 'utm_value', );
            $reason_closed = array();

            $data = array();
            $cleardata = array();

            $carbon = Carbon::tomorrow(); 

            $date_on = $request->input('date_on');
            $date_off = $request->input('date_off');
            $direction = $request->input('direction');
            if($direction != 'Все воронки'){ $direction = DealStages::getDirectionCode($direction); }else{ $direction = ''; }
            $utm_filters = $request->input('filter');
            $connection = DB::connection('pgsql');
            $setting = [ 
                ['is_adv', '=', true],
                ['created_at', '>=', $date_on],
                ['created_at', '<=', $date_off], 
                ['url', '!=', null],
            ];     
            if($direction){
                $setting[] = ['stage_now', 'like', $direction  . ':%'];
            }   
            $deals = $connection->table('deals')->where($setting)->get()->toArray();
            $count = count($deals);
            for ($i = 0; $i < $count; $i++){
                //unset($deals[$i]->stage_changes);
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
                    $deals[$i]->direction_code = $normalized[0];
                    $deals[$i]->direction = DealStages::getDirectionName($normalized[0]);
                    if ($deals[$i]->direction === false){
                        unset($deals[$i]);
                        continue;
                    }
                    foreach (DealStages::getStagesByDirection($normalized[0]) as $key => $stage_name){
                        $deals[$i]->$stage_name = intval($normalized[0] === $key);
                    }
                }
                if(!in_array($deals[$i]->direction, $directions, true)){
                    array_push($directions, $deals[$i]->direction);
                    unset($deals[$i]->direction_code);
                }
                if(!in_array($deals[$i]->stage_now, $stages, true)){
                    array_push($stages, $deals[$i]->stage_now);
                }
                if($deals[$i]->reason_closed){
                    if($utm_filters === 'all'){
                        $data['derty']['reason_closed'][$deals[$i]->reason_closed][] = $deals[$i]->reason_closed;
                    }
                    if($utm_filters === 'utm_source'){
                        if($deals[$i]->utm_medium){
                            $data['derty']['reason_closed'][$deals[$i]->reason_closed][] = $deals[$i]->reason_closed;
                        }
                    }
                    if($utm_filters === 'utm_medium'){
                        if($deals[$i]->utm_medium){
                            $data['derty']['reason_closed'][$deals[$i]->reason_closed][] = $deals[$i]->reason_closed;
                        }
                    } 
                    if($utm_filters === 'utm_campaign'){
                        if($deals[$i]->utm_campaign){
                            $data['derty']['reason_closed'][$deals[$i]->reason_closed][] = $deals[$i]->reason_closed;
                        }
                    }
                    if($utm_filters === 'utm_content'){
                        if($deals[$i]->utm_content){
                            $data['derty']['reason_closed'][$deals[$i]->reason_closed][] = $deals[$i]->reason_closed;
                        }
                    }
                    if($utm_filters === 'utm_term'){
                        if($deals[$i]->utm_term){
                            $data['derty']['reason_closed'][$deals[$i]->reason_closed][] = $deals[$i]->reason_closed;
                        }
                    }                    
                    
                }

                if($utm_filters === 'all'){
                    if($deals[$i]->utm_source){
                        $data['derty']['deals'][$deals[$i]->utm_source]['utm'] = 'utm_source';
                        $data['derty']['deals'][$deals[$i]->utm_source]['utm_value'] = $deals[$i]->utm_source;
                        if(!in_array($deals[$i]->stage_now, $columns, true)){ array_push($columns, ucfirst($deals[$i]->stage_now)); }
                        $data['derty']['deals'][$deals[$i]->utm_source][$deals[$i]->stage_now][] = $deals[$i]->stage_now;
                    }
                    if($deals[$i]->utm_medium){
                        $data['derty']['deals'][$deals[$i]->utm_medium]['utm'] = 'utm_medium';
                        $data['derty']['deals'][$deals[$i]->utm_medium]['utm_value'] = $deals[$i]->utm_medium;
                        if(!in_array($deals[$i]->stage_now, $columns, true)){ array_push($columns, ucfirst($deals[$i]->stage_now)); }
                        $data['derty']['deals'][$deals[$i]->utm_medium][$deals[$i]->stage_now][] = $deals[$i]->stage_now;
                    } 
                    if($deals[$i]->utm_campaign){
                        $data['derty']['deals'][$deals[$i]->utm_campaign]['utm'] = 'utm_campaign';
                        $data['derty']['deals'][$deals[$i]->utm_campaign]['utm_value'] = $deals[$i]->utm_campaign;
                        if(!in_array($deals[$i]->stage_now, $columns, true)){ array_push($columns, ucfirst($deals[$i]->stage_now)); }
                        $data['derty']['deals'][$deals[$i]->utm_campaign][$deals[$i]->stage_now][] = $deals[$i]->stage_now;
                    } 
                    if($deals[$i]->utm_content){
                        $data['derty']['deals'][$deals[$i]->utm_content]['utm'] = 'utm_content';
                        $data['derty']['deals'][$deals[$i]->utm_content]['utm_value'] = $deals[$i]->utm_content;
                        if(!in_array($deals[$i]->stage_now, $columns, true)){ array_push($columns, ucfirst($deals[$i]->stage_now)); }
                        $data['derty']['deals'][$deals[$i]->utm_content][$deals[$i]->stage_now][] = $deals[$i]->stage_now;
                    } 
                    if($deals[$i]->utm_term){
                        $data['derty']['deals'][$deals[$i]->utm_term]['utm'] = 'utm_term';
                        $data['derty']['deals'][$deals[$i]->utm_term]['utm_value'] = $deals[$i]->utm_term;
                        if(!in_array($deals[$i]->stage_now, $columns, true)){ array_push($columns, ucfirst($deals[$i]->stage_now)); }
                        $data['derty']['deals'][$deals[$i]->utm_term][$deals[$i]->stage_now][] = $deals[$i]->stage_now;
                    }                                                                                  
                }
                if($utm_filters === 'utm_source'){
                    if($deals[$i]->utm_source){
                        $data['derty']['deals'][$deals[$i]->utm_source]['utm'] = 'utm_source';
                        $data['derty']['deals'][$deals[$i]->utm_source]['utm_value'] = $deals[$i]->utm_source;
                        if(!in_array($deals[$i]->stage_now, $columns, true)){ array_push($columns, ucfirst($deals[$i]->stage_now)); }
                        $data['derty']['deals'][$deals[$i]->utm_source][$deals[$i]->stage_now][] = $deals[$i]->stage_now;
                    }
                }
                if($utm_filters === 'utm_medium'){
                    if($deals[$i]->utm_medium){
                        $data['derty']['deals'][$deals[$i]->utm_medium]['utm'] = 'utm_medium';
                        $data['derty']['deals'][$deals[$i]->utm_medium]['utm_value'] = $deals[$i]->utm_medium;
                        if(!in_array($deals[$i]->stage_now, $columns, true)){ array_push($columns, ucfirst($deals[$i]->stage_now)); }
                        $data['derty']['deals'][$deals[$i]->utm_medium][$deals[$i]->stage_now][] = $deals[$i]->stage_now;
                    } 
                }
                if($utm_filters === 'utm_campaign'){
                    if($deals[$i]->utm_campaign){
                        $data['derty']['deals'][$deals[$i]->utm_campaign]['utm'] = 'utm_campaign';
                        $data['derty']['deals'][$deals[$i]->utm_campaign]['utm_value'] = $deals[$i]->utm_campaign;
                        if(!in_array($deals[$i]->stage_now, $columns, true)){ array_push($columns, ucfirst($deals[$i]->stage_now)); }
                        $data['derty']['deals'][$deals[$i]->utm_campaign][$deals[$i]->stage_now][] = $deals[$i]->stage_now;
                    } 
                }
                if($utm_filters === 'utm_content'){
                    if($deals[$i]->utm_content){
                        $data['derty']['deals'][$deals[$i]->utm_content]['utm'] = 'utm_content';
                        $data['derty']['deals'][$deals[$i]->utm_content]['utm_value'] = $deals[$i]->utm_content;
                        if(!in_array($deals[$i]->stage_now, $columns, true)){ array_push($columns, ucfirst($deals[$i]->stage_now)); }
                        $data['derty']['deals'][$deals[$i]->utm_content][$deals[$i]->stage_now][] = $deals[$i]->stage_now;
                    } 
                }                
                if($utm_filters === 'utm_term'){
                    if($deals[$i]->utm_term){
                        $data['derty']['deals'][$deals[$i]->utm_term]['utm'] = 'utm_term';
                        $data['derty']['deals'][$deals[$i]->utm_term]['utm_value'] = $deals[$i]->utm_term;
                        if(!in_array($deals[$i]->stage_now, $columns, true)){ array_push($columns, ucfirst($deals[$i]->stage_now)); }
                        $data['derty']['deals'][$deals[$i]->utm_term][$deals[$i]->stage_now][] = $deals[$i]->stage_now;
                    }   
                }              
            } 

            if(isset($data['derty']['reason_closed'])){
                foreach ($data['derty']['reason_closed'] as $reason_closed_key => $reason_closed_value) {
                    array_push($reason_closed, array("name" => $reason_closed_key, "count" => count($reason_closed_value)));
                } 
            }
            foreach ($data['derty']['deals'] as $deals_key => $deals_value) {
                foreach ($deals_value as $d_key => $d_value) {
                    if(is_array($d_value)){
                        $data['derty']['deals'][$deals_key][ucfirst($d_key)] = count($d_value);
                    }
                }
            }

            //Собираются воронки
            $cleardata['directions'] = $directions;  
            //Собираются отдельно колонки 
            $cleardata['columns'] = $columns;    
            //Собираются отдельно причины закрытия
            $cleardata['reason_closed'] = $reason_closed;
            //Собираются данные по utm для отображения 
            $cleardata['utms'] = array_values($data['derty']['deals']);
            //Тут все deals в полном объеме за период с воронки
            $cleardata['deals'] = array_values($deals);
            
            //dd($cleardata['utms']);
            //dd($data['derty']['deals']);

            $answer = json_encode($cleardata);
            return $answer;
        }catch (\Exception $error){
            return $error->getMessage();
        }
    }

    private function debug($text){
        $ch = curl_init();
        curl_setopt_array(
         $ch,
         array(
             CURLOPT_URL => 'https://api.telegram.org/bot1722025778:AAFHt293q98F_juc7rwr1AtfLHNnwbelqHU/sendMessage',
             CURLOPT_POST => TRUE,
             CURLOPT_RETURNTRANSFER => TRUE,
             CURLOPT_TIMEOUT => 10,
             CURLOPT_POSTFIELDS => array(
                 'chat_id' => 1078062967,
                 'text' => $text,
             ),
         )
        );
        curl_exec($ch);
    } 
}

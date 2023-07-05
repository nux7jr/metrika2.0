<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Iterators\YandexMetrika\SiteCounterAndGoals;
use App\Models\MetrikaGoalCallsSites;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use AXP\YaMetrika\Client;
use AXP\YaMetrika\YaMetrika;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class YandexMetrikaController extends Controller
{
    private static SiteCounterAndGoals $siteCounterAndGoals;
    private array $ymClientParams;
    public function __construct(){
        self::$siteCounterAndGoals = new SiteCounterAndGoals();
    }

    /**
     * @param array{date_start:Carbon,date_end:Carbon} $params
     * @return void
     * @throws Exception
     */
    public function preparingAndSendData(array $params){
        $this->ymClientParams = $this->normalizeInputParams($params);
        foreach ($this->getClientParams() as $counter){
            $rawData = $this->getYMetrikaRawData($counter);
            $normalizedData = $this->normalizeDataYandex($rawData);
            $this->pushGoalsCountsToDB($normalizedData);
        }
    }
    private function normalizeDataYandex(array|null $rawData):array{
        if (empty($rawData)){
            return [];
        }
        $result = [];
        foreach ($rawData as $siteClickCount) {
            $siteWithPath = $this->getParsedUrl($siteClickCount['dimensions'][0]['name']);
            $result[$this->getSiteValueFromRuleOrMain(($this->ymClientParams['rules'] ?? []), $siteWithPath)] = $siteClickCount['metrics'][0] ?? 0;
        }
        return $result;
    }
    private function getParsedUrl(string $url):string{
        $url = parse_url($url);
        return $url['host'] . $url['path'];
    }
    private function getSiteValueFromRuleOrMain(array $rules, string $url):string{
        foreach ($rules as $rule){
            if (str_contains($url, $rule)){
                return $rule;
            }
        }
        return $this->ymClientParams['site'];
    }
    /**
     * @param array{
     *     metrics:integer,
     *     dimensions:string,
     *     goal_id:integer,
     *     group:string,
     *     ym_counter:integer
     *     } $counter
     * @return array
     */
    private function getYMetrikaRawData(array $counter):array{
        $ymClient = new Client(env('METRIKA_YANDEX_TOKEN'), $counter['ym_counter']);
        $metrika = new YaMetrika($ymClient);
        $callsMetric = $metrika->customQuery([
            'date1'         => $this->ymClientParams['date1'],
            'date2'         => $this->ymClientParams['date2'],
            'metrics'       => $counter['metrics'],
            'dimensions'    => $counter['dimensions'],
            'goal_id'       => $counter['goal_id'],
            'group'         => $counter['group'],
        ]);
        return $callsMetric->rawData()['data'];
    }
    /**
     * @param array{date_start:Carbon,date_end:Carbon} $params
     * @return array
     * @throws Exception
     */
    private function normalizeInputParams(array $params):array{
        $validated =[];
        if ($params['date_start'] instanceof Carbon){
            $validated['date_start'] = $params['date_start']->format('Y-m-d');
        }
        if (!$params['date_start'] instanceof Carbon){
            $validated['date_start'] = null;
        }
        if ($params['date_end'] instanceof Carbon){
            $validated['date_end'] = $params['date_end']->format('Y-m-d');
        }
        if (!$params['date_end'] instanceof Carbon){
            $validated['date_end'] = null;
        }
        return [
            'date1' => $validated['date_start'],
            'date2' => $validated['date_end'],
        ];
    }
    private function pushGoalsCountsToDB(array $data):void{
        foreach ($data as $site => $value){
            try{
                DB::beginTransaction();
                $metrikaGoalCallsSites              = new MetrikaGoalCallsSites();
                $metrikaGoalCallsSites->site_full   = $site;
                $metrikaGoalCallsSites->calls_count = $value;
                $metrikaGoalCallsSites->counter     = $this->ymClientParams['ym_counter'];
                $metrikaGoalCallsSites->goal        = $this->ymClientParams['goal_id'];
                $metrikaGoalCallsSites->date        = $this->ymClientParams['date1'];
                if (!DB::table('metrika_goals_call_sites')->where([['site_full',$site],['date',$this->ymClientParams['date1']]])->exists()){
                    $metrikaGoalCallsSites->save();
                }
                DB::commit();
            }catch (Exception $error){
                \Log::error($error->getMessage());
                DB::rollBack();
            }
        }
    }
    private function getClientParams():iterable
    {
        while (self::$siteCounterAndGoals->valid()){
            $clientParams = [
                'ym_counter'    => self::$siteCounterAndGoals->currentCounter(),
                'metrics'       => 'ym:s:goal'. self::$siteCounterAndGoals->currentGoalId() .'visits',
                'dimensions'    => 'ym:s:startURL',
                'goal_id'       => self::$siteCounterAndGoals->currentGoalId(),
                'group'         => 'day',
            ];
            $this->ymClientParams['site']       = self::$siteCounterAndGoals->currentSite();
            $this->ymClientParams['rules']      = self::$siteCounterAndGoals->currentRules();
            $this->ymClientParams['ym_counter'] = $clientParams['ym_counter'];
            $this->ymClientParams['goal_id']    = $clientParams['goal_id'];
            self::$siteCounterAndGoals->next();
            yield $clientParams;
        }
    }
}

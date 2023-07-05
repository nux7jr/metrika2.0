<?php

namespace App\Http\Helpers\Iterators\YandexMetrika;

use Iterator;

class SiteCounterAndGoals implements Iterator {
    private int $position = 0;
    private array $siteCounterAndGoals = [
        0 => [
            'site'                  => 'xl-pipe.ru',
            'ym_counter'            => 47578009,
            'ym_goal_id'            => 265457930,
            'rules_result_array'    => [
                'xl-pipe.ru/quiz',
            ],
        ],
        1 => [
            'site'                  => 'tiksanauto.ru',
            'ym_counter'            => 90130047,
            'ym_goal_id'            => 265333952,
            'rules_result_array'    => [
                'tiksanauto.ru/LP1',
                'tiksanauto.ru/premium',
            ],
        ],
        2 => [
            'site'                  => 'kotel-koloss.ru',
            'ym_counter'            => 52377523,
            'ym_goal_id'            => 63365923,
            'rules_result_array'    => [],
        ],
        3 => [
            'site'                  => 'malie-etaji.com',
            'ym_counter'            => 67008322,
            'ym_goal_id'            => 154915018,
            'rules_result_array'    => [
                'malie-etaji.ru/quiz',
                'malie-etaji.com/vladivostok_korobka',
                'malie-etaji.com/tyumen_korobka',
                'malie-etaji.com/tyumen_domokomplekt',
                'malie-etaji.com/perm_korobka',
                'malie-etaji.com/kvartira_na_zemle',
                'malie-etaji.com/irkutsk_korobka',
                'malie-etaji.com/ekaterinburg_korobka',
                'malie-etaji.com/barnaul_korobka',
                'malie-etaji.com/domokomplekt',
                'malie-etaji.com/bereznyaki-1',
            ],
        ],
        4 => [
            'site'                  => 'malie-etaji.ru',
            'ym_counter'            => 67008322,
            'ym_goal_id'            => 301359288,
            'rules_result_array'    => [],
        ],

//        2 => [
//            'site'                  => 'отопить.рф',
//            'ym_counter'            => 56190457,
//            'ym_goal_id'            => 292276396,
//            'rules_result_array'    => [
//                'отопить.рф/quiz_new',
//            ],
//        ],
    ];
    public function __construct() {
        $this->position = 0;
    }
    public function rewind(): void {
        $this->position = 0;
    }
    public function current():array{
        return $this->siteCounterAndGoals[$this->position];
    }
    public function key():int{
        return $this->position;
    }
    public function next(): void {
        ++$this->position;
    }
    public function valid(): bool {
        return isset($this->siteCounterAndGoals[$this->position]);
    }
    public function currentCounter():int{
        return  $this->siteCounterAndGoals[$this->position]['ym_counter'];
    }
    public function currentSite():string{
        return  $this->siteCounterAndGoals[$this->position]['site'];
    }
    public function currentGoalId():int{
        return  $this->siteCounterAndGoals[$this->position]['ym_goal_id'];
    }
    public function currentRules():array{
        return $this->siteCounterAndGoals[$this->position]['rules_result_array'];
    }
}

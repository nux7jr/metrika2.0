<?php

namespace App\Http\Helpers\Iterators\YandexMetrika;

use Iterator;

class SiteCounterAndGoals implements Iterator {
    private $position = 0;
    private array $siteCounterAndGoals = [
        0 => [
            'site'          => 'xl-pipe.ru',
            'ym_counter'    => 47578009,
            'ym_goal_id'    => 265457930,
        ],
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
        return isset($this->array[$this->position]);
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
}

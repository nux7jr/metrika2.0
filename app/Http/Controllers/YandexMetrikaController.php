<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Iterators\YandexMetrika\SiteCounterAndGoals;
use Carbon\Carbon;
use Illuminate\Http\Request;
use AXP\YaMetrika\Client;
use AXP\YaMetrika\YaMetrika;
class YandexMetrikaController extends Controller
{
    private static SiteCounterAndGoals $siteCounterAndGoals;
    private array $ymClientParams;

    /**
     * @param array{date_start:Carbon,date_end:Carbon} $params
     * @return void
     */
    public function preparingAndSendData(array $params){
        $this->ymClientParams = $this->normalizeParams($params);
    }
    private function normalizeParams(array $params):array{
        $validated = validator([
            'date_start' => 'Ca'
        ]);
    }
    private function pushGoalsCountsToDB():bool{

        return false;
    }
    private static function getClientParams():array
    {
        return [

        ];
    }
}

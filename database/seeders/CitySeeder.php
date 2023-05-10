<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use stdClass;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        foreach ($this->getCitiesBitrix() as $city){
            DB::table('city')->insert([
                'name' => $city->NAME,
                'active' => $city->ACTIVE === 'Y',
                'created_at' => Carbon::now()
            ]);
        }
    }

    /**
     * Connect Bitrix DB and get info.
     * @return array<int, string, stdClass>
     */
    private function getCitiesBitrix(): array{
        $connection = DB::connection('remote_bitrix');
        $rows = $connection->table('b_iblock_element')->where('IBLOCK_ID', 90)->get()->toArray();

        return $rows;
    }
}

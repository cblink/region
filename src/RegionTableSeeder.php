<?php
namespace Myischen\Region;

use Myischen\Region\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('areas')->truncate();

        $region = new Region();

        $provinces = $region->getRegionsWithCode();

        foreach ($provinces as $province) {
            $provinceId = DB::table('areas')->insertGetId([
                'name' => $province['name'],
                'code' => $province['code'],
                'type' => Region::PROVINCE,
            ]);
            foreach($province['children'] as $city ){
                $cityId = DB::table('areas')->insertGetId([
                    'name' => $city['name'],
                    'parent_id' => $provinceId,
                    'code' => $city['code'],
                    'type' =>Region::CITY,
                ]);
                foreach($city['children'] as $area ){
                    $areaId = DB::table('areas')->insertGetId([
                        'name' => $area['name'],
                        'parent_id' => $cityId,
                        'code' => $area['code'],
                        'type' =>Region::AREA,
                    ]);

                    $street = array_map(function ($area) use ($areaId) {
                        return ['name' => $area['name'], 'code' => $area['code'], 'parent_id' => $areaId, 'type' => Region::STREET];
                    }, $area['children']);

                    DB::table('areas')->insert($street);

                }

            }

        }
    }
}

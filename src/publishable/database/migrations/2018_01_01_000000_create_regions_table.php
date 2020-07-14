<?php

use Myischen\Region\Region;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Lybc\PhpGB2260\GB2260;

class CreateRegionsTable extends Migration
{

    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable()->index();
            $table->string('name');
            $table->unsignedTinyInteger('type');
            $table->unsignedInteger('code');
        });

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

    public function down()
    {
        Schema::dropIfExists('areas');
    }

}

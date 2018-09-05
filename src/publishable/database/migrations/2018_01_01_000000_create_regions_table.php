<?php

use Cblink\Region\Region;
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
                'name' => $province['title'],
                'code' => $province['ad_code'],
                'type' => Region::PROVINCE,
            ]);
            foreach ($province['child'] as $city) {
                $cityId = DB::table('areas')->insertGetId([
                    'name' => $city['title'],
                    'parent_id' => $provinceId,
                    'code' => $city['ad_code'],
                    'type' => Region::CITY,
                ]);
                $areas = array_map(function ($area) use ($cityId) {
                    return ['name' => $area['title'], 'code' => $area['ad_code'], 'parent_id' => $cityId, 'type' => Region::AREA];
                }, $city['child']);
                DB::table('areas')->insert($areas);
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('areas');
    }

}
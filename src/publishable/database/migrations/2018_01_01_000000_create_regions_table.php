<?php

use Cblink\Region\Region;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionsTable extends Migration
{

    public function up()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('province_id')->index();
            $table->string('name');
        });

        Schema::create('areas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('city_id')->index();
            $table->string('name');
        });
        
        $region = new Region();

        $provinces = $region->getRegions();

        foreach ($provinces as $province) {
            $provinceId = DB::table('provinces')->insertGetId(['name' => $province['name']]);
            foreach ($province['city'] as $city) {
                $cityId = DB::table('cities')->insertGetId(['name' => $province['name'], 'province_id' => $provinceId]);
                $areas = array_map(function ($area) use ($cityId) {
                    return ['name' => $area, 'city_id' => $cityId];
                }, $city['area']);
                DB::table('areas')->insert($areas);
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('areas');
    }
    
}
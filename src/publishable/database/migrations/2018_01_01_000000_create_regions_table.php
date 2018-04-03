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
        Schema::create('provinces', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->index();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('province_id')->index();
            $table->string('name');
            $table->string('code')->index();
        });

        Schema::create('areas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('city_id')->index();
            $table->string('name');
            $table->string('code')->index();
        });

        // 存储行政编码
        $this->fillRegionsWithCode();

        // 不存储行政编码
        // $this->fillRegions();
    }

    public function fillRegions()
    {
        $region = new Region();

        $provinces = $region->getRegions();

        foreach ($provinces as $province) {
            $provinceId = DB::table('provinces')->insertGetId(['name' => $province['name']]);
            foreach ($province['city'] as $city) {
                $cityId = DB::table('cities')->insertGetId(['name' => $city['name'], 'province_id' => $provinceId]);
                $areas = array_map(function ($area) use ($cityId) {
                    return ['name' => $area, 'city_id' => $cityId];
                }, $city['area']);
                DB::table('areas')->insert($areas);
            }
        }
    }

    public function fillRegionsWithCode()
    {
        $region = new Region();

        $codes = $region->getRegionsWithCode();

        foreach ($codes as $provinceCode => $name) {
            $code = GB2260::areaCode($provinceCode);
            if ($code->isProvince()) {
                $provinceId = DB::table('provinces')->insertGetId(['name' => $code->getProvince(), 'code' => $provinceCode]);
                foreach ($code->getCity() as $cityCode => $city) {
                    $code = GB2260::areaCode($cityCode);
                    $cityId = DB::table('cities')->insertGetId(['name' => $city['name'], 'province_id' => $provinceId, 'code' => $cityCode]);
                    $areas = collect($code->getDistrict())->map(function ($name, $code) use ($cityId) {
                        return ['name' => $name, 'code' => $code, 'city_id' => $cityId];
                    })->all();
                    DB::table('areas')->insert($areas);
                }
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
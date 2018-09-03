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
        $this->migrateWithCode();

//        $this->migrateWithoutCode();
    }

    protected function migrateWithoutCode()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->nullable()->index();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('province_id')->index();
            $table->string('name');
            $table->string('code')->nullable()->index();
        });

        Schema::create('areas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('city_id')->index();
            $table->string('name');
            $table->string('code')->nullable()->index();
        });

        // 存储行政编码 数据源有问题，极力不建议使用
//        $this->fillRegionsWithCode();

        $this->fillRegionsWithoutCode();
    }

    protected function migrateWithCode()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable()->index();
            $table->string('name');
            $table->unsignedInteger('code');
        });

        $region = new Region();

        $provinces = $region->getRegionsWithCode();

        foreach ($provinces as $province) {
            $provinceId = DB::table('areas')->insertGetId([
                'name' => $province['title'],
                'code' => $province['ad_code'],
            ]);
            foreach ($province['child'] as $city) {
                $cityId = DB::table('areas')->insertGetId([
                    'name' => $city['title'],
                    'parent_id' => $provinceId,
                    'code' => $city['ad_code'],
                ]);
                $areas = array_map(function ($area) use ($cityId) {
                    return ['name' => $area, 'parent_id' => $cityId];
                }, $city['child']);
                DB::table('areas')->insert($areas);
            }
        }
    }

    public function fillRegionsWithoutCode()
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

    public function down()
    {
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('areas');
    }
    
}
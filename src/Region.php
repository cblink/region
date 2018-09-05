<?php


namespace Cblink\Region;


class Region
{

    const PROVINCE = 1;
    const CITY = 2;
    const AREA = 3;

    /**
     * 获取区域数组
     *
     * @return mixed
     */
    public function getRegions()
    {
        $raw = file_get_contents(__DIR__.'/../data/data.json');

        return json_decode($raw, true);
    }

    /**
     * 获取区域数组
     *
     * @return mixed
     */
    public function getRegionsWithCode()
    {
        $raw = file_get_contents(__DIR__.'/../data/data-with-code.json');

        return json_decode($raw, true);
    }

}
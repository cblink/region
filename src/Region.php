<?php


namespace Cblink\Region;


class Region
{

    /**
     * 获取区域数组
     *
     * @return mixed
     */
    public function getRegions()
    {
        $raw = file_get_contents(__DIR__.'../data/data.json');

        return json_decode($raw, true);
    }

    /**
     * 获取区域数组
     *
     * @return mixed
     */
    public function getRegionsWithCode()
    {
        $raw = file_get_contents(__DIR__.'../data/data-with-code.json');

        return json_decode($raw, true);
    }

}
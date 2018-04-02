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
        $raw = file_get_contents(__DIR__.'/data.json');

        return json_decode($raw, true);
    }

}
<?php

namespace Myischen\Region;


class Region
{

    const PROVINCE = 1;
    const CITY = 2;
    const AREA = 3;
    const STREET = 4;


    public function allProvinces()
    {
        return $this->query(self::PROVINCE);
    }

    public function allCities()
    {
        return $this->query(self::CITY);
    }

    public function allAreas()
    {
        return $this->query(self::AREA);
    }
    public function allStreets()
    {
        return $this->query(self::STREET);
    }

    public function nestFromChild($id)
    {
        return Area::with('parent.parent.parent')->where('id', $id)->get();
    }

    public function nest($id = null)
    {
        return Area::with('children.children.children')->when($id, function ($query, $id) {
            $query->where('id', $id);
        })->get();
    }

    public function nestFromChildByCode($code)
    {
        return Area::with('parent.parent.parent')->where('code', $code)->get();
    }

    public function nestByCode($code = null)
    {
        return Area::with('children.children.children')->when($code, function ($query, $code) {
            $query->where('code', $code);
        })->get();
    }

    protected function query($type)
    {
        return Area::where('type', $type)->get();
    }

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

<?php


namespace Myischen\Region;


use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    public function parent()
    {
        return $this->belongsTo(Area::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Area::class, 'parent_id', 'id');
    }
}
<?php


namespace Cblink\Region;


use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this::setTable(config('region.table'));
    }

    public function parent()
    {
        return $this->belongsTo(Area::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Area::class, 'parent_id', 'id');
    }
}
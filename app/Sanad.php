<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sanad extends Model
{
    protected $table="sanads";
    protected $fillable=[
        "number",
        "description",
        "total",
        "total_cost",
        "supporter_percent" ,
        "supporter_id",
        "user_id",
        "status",
        "type"
    ];
    public function supporter()
    {
        return $this->hasOne('App\User', 'id', 'supporter_id');
    }
}

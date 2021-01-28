<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Help extends Model
{
    protected $fillable = [
        'name',
        'link',
        'group_id'
    ];

    public function group(){
        $this->belongsTo('App\Group','id','group_id');
    }
}

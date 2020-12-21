<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function collection(){
        return $this->hasOne('App\Collection', 'id', 'collections_id');
    }

    public function classrooms(){
        return $this->hasMany("App\ClassRoom", "products_id", "id");
    }
}

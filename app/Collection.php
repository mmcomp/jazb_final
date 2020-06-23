<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    public function parent(){
        return $this->hasOne('App\Collection', 'id', 'parent_id');
    }

    public function parents(){
        $parents = "";
        $ths = $this;
        $parent = $ths->parent()->first();
        while($parent){
            if($parents!="")
                $parents = $parent->name . "->" . $parents;
            else
                $parents = $parent->name;
            $ths = $parent;
            $parent = $ths->parent()->first();
        }
        return $parents;
    }
}

<?php
namespace App\Utils;
use Illuminate\Support\Facades\DB;

class SearchStudent {

    public static function search($studentBuilder, $name)
    {
        
        return $studentBuilder->where(DB::raw("CONCAT(IFNULL(first_name, ''), IFNULL(CONCAT(' ', last_name), ''))"), 'like', '%' . $name . '%');
    }
}

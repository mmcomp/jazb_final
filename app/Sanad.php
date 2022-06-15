<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sanad extends Model
{
    protected $table="sanads";
    protected $appends = [
        'total_price',
        'total_debtor',
        'total_creditor',
        'total_total_cost'
    ];
    
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
    public function getTotalPriceAttribute()
    {
        return $this->total;
    }
    public function getTotalTotalCostAttribute()
    {
        return $this->total_cost;
    }
    public function getTotalDebtorAttribute()
    {
        if($this->type>0)
            return $this->total;
        return 0;    
    }
    public function getTotalCreditorAttribute()
    {
        if($this->type<0)
            return $this->total;
        return 0;   
       // return $this->total;
    }
    public function supporter()
    {
        return $this->hasOne('App\User', 'id', 'supporter_id');
    }
}

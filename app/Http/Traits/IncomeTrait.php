<?php
namespace App\Http\Traits;
use App\Commission;
use App\Purchase;
use Illuminate\Support\Facades\Auth;

trait IncomeTrait {
    public function computeMonthIncome($purchases,$wage,$default_wage,$getPurchases)
    {
        $sum = 0;
        //$purchases = ($first_day != null && $last_day != null) ? Purchase::where('is_deleted',false)->where('supporters_id',Auth::user()->id)
        //->where('created_at','>=',$first_day)->where('created_at','<=',$last_day) : Purchase::where('is_deleted',false)->where('supporters_id',Auth::user()->id) ;
        $products_in_purchases = $purchases->distinct('products_id')->pluck('products_id');
        $commissionRelations = Commission::where('is_deleted',false)->where('users_id',Auth::user()->id)->whereIn('products_id',$products_in_purchases)->get();
        foreach($commissionRelations as $item){
            $wage[$item->products_id] = $item->commission;
         }
         foreach($getPurchases as $item){
             if(isset($wage[$item->products_id])){
                 $sum += ($wage[$item->products_id])/100 *$item->price;
             }else{
                 $sum += ($default_wage/100 * $item->price);
             }
        }
        return [$sum,$wage];
    }
}

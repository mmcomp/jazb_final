<?php
namespace App\Utils;
use App\Commission;
use App\User;
use Illuminate\Support\Facades\Auth;

class CommissionPurchaseRelation {
    public static function computeMonthIncome($purchases,$getPurchases)
    {
        $sum = 0;
        $wage = [];
        $user = User::where('is_deleted',false)->where('id',Auth::user()->id)->first();
        $default_wage = $user->default_commision;
        $products_in_purchases = $purchases->select('products_id','created_at','id','students_id','price')->distinct('products_id')->pluck('products_id');
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
        return [$sum,$wage,$default_wage];
    }
}

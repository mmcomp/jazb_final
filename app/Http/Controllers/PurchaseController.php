<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Product;
use App\Purchase;
use App\Student;

class PurchaseController extends Controller
{
    //---------------------API------------------------------------
    public function apiAddPurchases(Request $request){
        $purchases = $request->input('purchases', []);
        $ids = [];
        $fails = [];
        foreach($purchases as $purchase){
            if(!isset($purchase['woo_id']) || !isset($purchase['phone']) || !isset($purchase['price'])){
                $fails[] = $purchase;
                continue;
            }
            $purchaseObject = new Purchase;
            $product = Product::where('woo_id', $purchase['woo_id'])->first();
            $student = Student::where('phone', $purchase['phone'])->first();
            if($product == null || $student == null){
                $fails[] = $purchase;
                continue;
            }
            $purchaseObject->products_id = $product->id;
            $purchaseObject->students_id = $student->id;
            $purchaseObject->price = isset($purchase['price'])?$purchase['price']:0;
            $purchaseObject->users_id = 0;
            try{
                $purchaseObject->save();
                $ids[] = $purchaseObject->id;
            }catch(Exception $e){
                $fails[] = $purchase;
            }
        }
        return [
            "added_ids" => $ids,
            "fails" => $fails
        ];
    }

    //---------------------API------------------------------------
    public function apiAddStudents(Request $request){
        $students = $request->input('students', []);
        $ids = [];
        $fails = [];
        foreach($students as $student){
            if(!isset($student['phone']) || !isset($student['last_name'])){
                $fails[] = $student;
                continue;
            }
            $studentObject = new Student;
            foreach($student as $key=>$value){
                $studentObject->$key = $value;
            }
            try{
                $studentObject->save();
                $ids[] = $studentObject->id;
            }catch(Exception $e){
                $fails[] = $student;
            }
        }
        return [
            "added_ids" => $ids,
            "fails" => $fails
        ];
    }
}

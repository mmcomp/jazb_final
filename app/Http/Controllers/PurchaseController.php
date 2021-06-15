<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Product;
use App\Purchase;
use App\Student;
use App\StudentClassRoom;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Utils\Sms;
use Log;
use Morilog\Jalali\CalendarUtils;
use Illuminate\Support\Facades\Gate;

use Carbon\Carbon;

use Exception;

class PurchaseController extends Controller
{
    public static function jalaliToGregorian($pdate)
    {
        $pdate = explode('/', SupporterController::persianToEnglishDigits($pdate));
        $date = "";
        if (count($pdate) == 3) {
            $y = (int)$pdate[0];
            $m = (int)$pdate[1];
            $d = (int)$pdate[2];
            if ($d > $y) {
                $tmp = $d;
                $d = $y;
                $y = $tmp;
            }
            $y = (($y < 1000) ? $y + 1300 : $y);
            $gregorian = CalendarUtils::toGregorian($y, $m, $d);
            $gregorian = $gregorian[0] . "-" . $gregorian[1] . "-" . $gregorian[2];
        }
        return $gregorian;
    }
    public function index()
    {
        $types = ["site_successed" => "سایت","site_failed" => "انصرافی","manual" => "حضوری","manual_failed" => "کنسل"];
        $from_date = null;
        $to_date = null;
        $products = Product::where('is_deleted',false)->get();
        $students = Student::where('is_deleted', false)->where('banned', false)->get();
        return view('purchases.index', [
            'types' => $types,
            'products' => $products,
            'students' => $students,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }
    public function indexPost(Request $request)
    {
        $type = null;
        $btn = '';
        $product_part_1 = null;
        $product_part_2 = null;
        $product_part_3 = null;
        $product_part_4 = null;
        $product_part_5 = null;
        $req =  $request->all();
        if (!isset($req['start'])) {
            $req['start'] = 0;
            $req['length'] = 10;
            $req['draw'] = 1;
        }
        $purchases = Purchase::where('purchases.is_deleted', false)
            ->with('user')
            ->with('student')
            ->with('product');
        //filter
        if ($request->input('theId') != null) {
            $theId = (int)$request->input('theId');
            $purchases = $purchases->where('id', $theId);
        }
        if ($request->input('place') != null) {
            $place = $request->input("place");
            switch ($place) {
                case "site_successed":
                    $purchases = $purchases->where('purchases.type', 'site_successed');
                    break;
                case "site_failed":
                    $purchases = $purchases->where('purchases.type', 'site_failed');
                    break;
                case "manual":
                    $purchases = $purchases->where('purchases.type', 'manual');
                    break;
                case "manual_failed":
                    $purchases = $purchases->where('type','manual_failed');
                    break;
            }
        }
        if ($request->input('name') != null) {
            $name = trim($request->input('name'));
            $student_ids = Student::where('is_deleted', false)
            ->where('banned', false)
            ->where('archived', false)
            ->where(DB::raw("CONCAT(IFNULL(first_name, ''), IFNULL(CONCAT(' ', last_name), ''))"), 'like', '%' . $name . '%')
            ->pluck('id');
            $purchases = $purchases->whereIn('purchases.students_id', $student_ids);
        }
        if ($request->input('phone') != null) {
            $phone = $request->input('phone');
            $student_ids = Student::where('phone', 'like', '%' . $phone . '%')
                ->where('is_deleted', false)
                ->where('banned', false)
                ->where('archived', false)
                ->pluck('id');
            $purchases = $purchases->whereIn('purchases.students_id', $student_ids);
        }
        if($request->input('from_date') && $request->input('to_date') && $request->input('from_date') == $request->input('to_date')){
            $purchases = $purchases->where('created_at', '>=', date("Y-m-d 00:00:00"))->where('created_at','<=',date("Y-m-d 23:59:59"));
        } else {
            if ($request->input('from_date')) {
                $from_date = $this->jalaliToGregorian($request->input('from_date'));
                $purchases = $purchases->where('created_at', '>=', $from_date);
            }
            if ($request->input('to_date')) {
                $to_date = $this->jalaliToGregorian($request->input('to_date'));
                $purchases = $purchases->where('created_at', '<=', $to_date);
            }
        }
        if($request->input('products_id') != null){
            $products_id = (int)$request->input('products_id');
            $purchases = $purchases->where('purchases.products_id', $products_id);
        }
        if ($request->input('factor_number') != null) {
            $factor_number = (int)$request->input('factor_number');
            $purchases = $purchases->where('purchases.factor_number', $factor_number);
        }
        if ($request->input('price') != null) {
            $price = (int)$request->input('price');
            $purchases = $purchases->where('purchases.price', $price);
        }
        if ($request->input('description') != null) {
            $description = $request->input('description');
            $purchases = $purchases->where('purchases.description', $description);
        }
        //end filter
        $allPurchases = $purchases->get();
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc

        if ($columnName != 'row' && $columnName != "end" && $columnName != "saloon") {
            $purchases = $purchases->orderBy($columnName, $columnSortOrder)
                ->select('purchases.*')
                ->skip($req['start'])
                ->take($req['length'])
                ->get();
        } else if($columnName == "saloon"){
            $purchases = $purchases
            ->join('students','purchases.students_id','=','students.id')
            ->orderBy('students.saloon', $columnSortOrder)
            ->select('purchases.*','students.*')
            ->skip($req['start'])
            ->take($req['length'])
            ->get();
        } else {
            $purchases = $purchases->select('purchases.*')
                ->skip($req['start'])
                ->take($req['length'])
                ->get();
        }
        $data = [];
        if ($purchases) {
            foreach ($purchases as $index => $item) {
                $id = $item->id;
                if ($item->type == 'manual') {
                    $type = "حضوری";
                    $btn =  '<div class="d-flex justify-content-between"><a class="btn btn-primary btn-sm mr-1" href="#" onclick="openManualModal('.$id.')">ویرایش</a>
                    <a class="btn btn-danger btn-sm text-white" onclick="IfConfirmDestroy('. $id .')">حذف</a></div>';
                } else if ($item->type == "site_successed") {
                    $type = "سایت";
                   $btn =  "<a class='btn btn-primary btn-sm' href='#' onclick='openSiteModal(".$id.")'>ویرایش</a>";
                } else if ($item->type == "site_failed") {
                    $type = "انصرافی";
                    $btn = '';
                }  else if ($item->type == "manual_failed") {
                    $type = "کنسل";
                    $btn = '';
                }
                $product_part_1 = ($item->product && $item->product->collection && $item->product->collection->parent) ? $item->product->collection->parent->name : '';
                $product_part_2 = ($item->product && $item->product->collection && $item->product->collection->parent) ? '->' : '';
                $product_part_3 = ($item->product && $item->product->collection) ? $item->product->collection->name : '';
                $product_part_4 = ($item->product && $item->product->collection) ? '->' : '';
                $product_part_5 =  $item->product ? $item->product->name : '-';

                $data[] = [
                    "row" => $req['start'] + $index + 1,
                    "id" => $id,
                    "type" => $type,
                    "students_id" => $item->student ? $item->student->first_name . ' ' . $item->student->last_name . ' [' . $item->student->phone . ']' : '-',
                    "factor_number" => $item->factor_number,
                    "products_id" => $product_part_1 . $product_part_2 . $product_part_3 . $product_part_4 . $product_part_5,
                    "price" => number_format($item->price),
                    "description" => $item->description,
                    "saloon" => ($item->student && $item->student->saloon != null) ? $item->student->saloon : '-',
                    "created_at" => ($item->created_at) ? jdate($item->created_at)->format("Y/m/d") : jdate()->format("Y/m/d"),
                    "end" => $btn
                ];
            }
        }
        $result = [
            "draw" => $req['draw'],
            "data" => $data,
            "request" => $request->all(),
            "recordsTotal" => count($allPurchases),
            "recordsFiltered" => count($allPurchases),
        ];

        return $result;
    }

    public function create(Request $request)
    {
        $purchase = new Purchase();
        $students = Student::where('is_deleted', false)->where('archived', false)->where('banned', false)->get();
        $products = Product::where('is_deleted', false)->with('collection')->orderBy('name')->get();
        foreach ($products as $index => $product) {
            $products[$index]->parents = "-";
            if ($product->collection) {
                $parents = $product->collection->parents();
                $name = ($parents != '') ? $parents . "->" . $product->collection->name : $product->collection->name;
                $products[$index]->parents = $name;
            }
        }
        if ($request->getMethod() == 'GET') {
            return view('purchases.create', [
                'purchase' => $purchase,
                'students' => $students,
                'products' => $products
            ]);
        }

        $student = Student::find($request->input('students_id'));
        $product = Product::find($request->input('products_id'));
        $supporter = User::find($student->supporters_id);

        $purchase->students_id = $request->input('students_id');
        $purchase->supporters_id = $student->supporters_id;
        $purchase->users_id = Auth::user()->id;
        $purchase->products_id = $request->input('products_id');
        $purchase->description = $request->input('description');
        $purchase->price = $request->input('price');
        $purchase->factor_number = $request->input('factor_number');
        $purchase->type = 'manual';
        $purchase->save();
        if ($student) {
            if ($student->supporters_id) {
                $student->own_purchases = $student->purchases()->where('supporters_id', $student->supporters_id)
                    ->where('is_deleted', false)->where('type', '!=', 'site_failed')->where('type','!=','manual_failed')->count();
            }
            $student->other_purchases = $student->purchases()->where(function ($query) use ($student) {
                if ($student->supporters_id) $query->where('supporters_id', '!=', $student->supporters_id)->orWhere('supporters_id', 0);
            })->where('is_deleted', false)->where('type', '!=', 'site_failed')->where('type','!=','manual_failed')->count();
            $student->today_purchases = $student->purchases()->where('created_at', '>=', date("Y-m-d 00:00:00"))->where('is_deleted', false)->where('type', '!=', 'site_failed')->where('type','!=','manual_failed')->count();
            $student->save();
        }
        $request->session()->flash("msg_success", "پرداخت با موفقیت افزوده شد.");

        if ($supporter && $supporter->mobile) {
            $msg = "کاربر گرامی {$supporter->first_name} {$supporter->last_name}\n";
            $msg .= "محصول {$product->name} توسط  {$student->first_name} {$student->last_name} به مبلغ {$purchase->price} خریداری شد.\nعارف";
            Sms::send($supporter->mobile, $msg);
        }

        return redirect()->route('purchases');
    }

    public function openSiteEditModal(Request $request)
    {

        $purchase = Purchase::where('id', $request->input('id'))->where('is_deleted', false)->first();
        if($purchase != null){
            $result = [
                "data" => [
                    "price" => $purchase->price,
                    "description" => $purchase->description,
                ],
                "error" => null
            ];
        } else {
            $result = [
                "data" => [
                    "price" => 0,
                    "description" => null,
                ],
                "error" => "شناسه این خرید پیدا نشد!"
            ];
        }
       
        return $result;
    }
    public function applySiteEditModal(Request $request)
    {

        $purchase = Purchase::where('id', $request->input('id'))->where('is_deleted', false)->first();
        if($purchase != null){
            $purchase->price = $request->input('price');
            $purchase->description = $request->input('description');
            $purchase->save();
            $result = [
                "data" => null,
                "error" => null
            ];
        } else {
            $result = [
                "data" => null,
                "error" => "شناسه این خرید پیدا نشد!"
            ];  
        }
       
        return $result;
    }
    public function openManualEditModal(Request $request)
    {
      
        $types = ["manual" => "حضوری","manual_failed" => "کنسل"];
        $purchase = Purchase::where('id', $request->input('id'))->where('is_deleted', false)->first();
        if($purchase != null){
            $result = [
                "data" => [
                    "factor_number" => $purchase->factor_number,
                    "students_id" => $purchase->students_id,
                    "products_id" => $purchase->products_id,
                    "description" => $purchase->description,
                    "price" => $purchase->price,
                    "types" => $types,
                    "type" => $purchase->type,
                ],
                "error" => null
            ];
        } else {
            $result = [
                "data" => [
                    "factor_number" => null,
                    "students_id" => null,
                    "products_id" => null,
                    "description" => null,
                    "price" => null,
                    "types" => null,
                    "type" => null,
                ],
                "error" => "شناسه این خرید پیدا نشد!"
            ];
        }
        return $result;
    }
    public function applyManualEditModal(Request $request)
    {

        $purchase = Purchase::where('id', $request->input('id'))->where('is_deleted', false)->first();
        if($purchase != null){
            $student = Student::find($request->input('students_id'));
            $purchase->students_id = $request->input('students_id');
            $purchase->supporters_id = $student->supporters_id;
            $purchase->users_id = Auth::user()->id;
            $purchase->products_id = $request->input('products_id');
            $purchase->description = $request->input('description');
            $purchase->price = $request->input('price');
            $purchase->factor_number = $request->input('factor_number');
            if(!Gate::allows('supervisor') && Gate::allows('parameters')){
                $purchase->type = $request->input('type');
            }
            $purchase->save();
            if ($student) {
                $student->today_purchases = $student->purchases()->where('created_at', '>=', date("Y-m-d 00:00:00"))->where('is_deleted', false)->where('type', '!=', 'site_failed')->where('type','!=','manual_failed')->count();
    
                if ($purchase->created_at < date("Y-m-d 00:00:00")) {
                    $student->today_purchases = $student->today_purchases > 0 ? $student->today_purchases - 1 : $student->today_purchases;
                }
                $student->save();
            }
            $result = [
                "data" => null,
                "error" => null
            ];
        } else {
            $result = [
                "data" => null,
                "error" => "شناسه این خرید پیدا نشد!"
            ];
        }
        return $result;
    }

    public function delete(Request $request)
    {

        $id = $request->input('id');
        $purchase = Purchase::where('id', $id)->where('is_deleted', false)->first();
        $student = Student::where('id', $purchase->students_id)->first();
        if ($purchase == null) {
            $result = [
                "data" => null,
                "error" => "شناسه این خرید پیدا نشد!"
            ];
        }
        if ($student) {
            $student->today_purchases = $student->purchases()->where('created_at', '>=', date("Y-m-d 00:00:00"))->where('is_deleted', false)->where('type', '!=', 'site_failed')->where('type','!=','manual_failed')->count();

            if ($student->supporters_id) {
                if ($student->own_purchases > 0) {
                    $student->own_purchases -= 1;
                } else {
                    $student->own_purchases = 0;
                }
            } else {
                if ($student->other_purchases > 0) {
                    $student->other_purchases -= 1;
                } else {
                    $student->other_purchases = 0;
                }
            }
            if ($purchase->created_at >= date("Y-m-d 00:00:00")) {
                if ($student->today_purchases > 0) {
                    $student->today_purchases -= 1;
                } else {
                    $student->today_purchases  = 0;
                }
            }
            $student->save();
        }

        $purchase->is_deleted = true;
        $purchase->save();
        $result = [
            "data" => null,
            "error" => null
        ];
        return $result;
        // $request->session()->flash("msg_success", "پرداخت با موفقیت حذف شد.");
        // return redirect()->route('purchases');
    }

    //---------------------API------------------------------------
    public function apiDeletePurchases(Request $request)
    {
        $purchases = $request->input('purchases', []);
        $ids = [];
        $fails = [];
        foreach ($purchases as $purchase) {
            if (!isset($purchase['factor_number'])) {
                $fails[] = $purchase;
                continue;
            }
            $purchaseObject = Purchase::where("factor_number", $purchase['factor_number'])->where('is_deleted', false)->where('type', '!=', 'manual')->first();
            $student = Student::where('id', $purchaseObject->students_id)->first();
            if ($student) {
                $student->today_purchases = $student->purchases()->where('created_at', '>=', date("Y-m-d 00:00:00"))->where('is_deleted', false)->where('type', '!=', 'site_failed')->where('type','!=','manual_failed')->count();

                if ($student->supporters_id) {
                    if ($student->own_purchases > 0) {
                        $student->own_purchases -= 1;
                    } else {
                        $student->own_purchases = 0;
                    }
                } else {
                    if ($student->other_purchases > 0) {
                        $student->other_purchases -= 1;
                    } else {
                        $student->other_purchases = 0;
                    }
                }
                if ($purchaseObject->created_at >= date("Y-m-d 00:00:00")) {
                    if ($student->today_purchases > 0) {
                        $student->today_purchases -= 1;
                    } else {
                        $student->today_purchases  = 0;
                    }
                }
                try {
                    $student->save();
                } catch (Exception $e) {
                    $fails[] = $student;
                }
            }
            if (!$purchaseObject) {
                $fails[] = $purchase;
                continue;
            }
            $purchaseObject->is_deleted = true;
            $purchaseObject->save();

            try {
                $purchaseObject->save();
                $ids[] = $purchaseObject->factor_number;
            } catch (Exception $e) {
                $fails[] = $purchase;
            }
        }
        return [
            "deleted_ids" => $ids,
            "fails" => $fails
        ];
    }

    public function apiUnDeletePurchases(Request $request)
    {
        $purchases = $request->input('purchases', []);
        $ids = [];
        $fails = [];
        foreach ($purchases as $purchase) {

            if (!isset($purchase['factor_number'])) {
                $fails[] = $purchase;
                continue;
            }
            $purchaseObject = Purchase::where("factor_number", $purchase['factor_number'])->where('is_deleted', true)->where('type', '!=', 'manual')->first();
            if (!$purchaseObject) {
                $fails[] = $purchase;
                continue;
            }
            $student = Student::where('id', $purchaseObject->students_id)->first();
            if ($student) {
                $student->today_purchases = $student->purchases()->where('created_at', '>=', date("Y-m-d 00:00:00"))->where('is_deleted', false)->where('type', '!=', 'site_failed')->where('type','!=','manual_failed')->count();

                if ($student->supporters_id) {
                    $student->own_purchases += 1;
                } else {
                    $student->other_purchases += 1;
                }
                if ($purchaseObject->created_at >= date("Y-m-d 00:00:00")) {
                    $student->today_purchases += 1;
                }
                try {
                    $student->save();
                } catch (Exception $e) {
                    $fails[] = $student;
                }
            }

            $purchaseObject->is_deleted = false;
            $purchaseObject->save();

            try {
                $purchaseObject->save();
                $ids[] = $purchaseObject->factor_number;
            } catch (Exception $e) {
                $fails[] = $purchase;
            }
        }
        return [
            "deleted_ids" => $ids,
            "fails" => $fails
        ];
    }

    public function apiAddPurchases(Request $request)
    {
        $purchases = $request->input('purchases', []);
        $ids = [];
        $fails = [];
        foreach ($purchases as $purchase) {

            if (!isset($purchase['woo_id']) || !isset($purchase['phone']) || !isset($purchase['price']) || !isset($purchase['factor_number'])) {
                $fails[] = $purchase;
                Log::info("Fail 1 " . $purchase['factor_number']);
                continue;
            }

            $product = Product::where('woo_id', $purchase['woo_id'])->with('classrooms')->first();
            $student = Student::where('phone', $purchase['phone'])->first();
            if ($product == null || $student == null) {

                $fails[] = $purchase;
                Log::info("Fail 2 " . $purchase['factor_number'] . " " . $purchase['woo_id']);
                continue;
            }

            $isInsert = false;
            $purchaseObject = Purchase::where("factor_number", $purchase['factor_number'])->where("products_id", $product->id)->first();
            if (!$purchaseObject) {
                $purchaseObject = new Purchase;
                $isInsert = true;
            }

            foreach ($purchase as $key => $value) {
                if ($key != 'woo_id' && $key != 'phone')
                    $purchaseObject->$key = $value;
            }
            $purchaseObject->products_id = $product->id;
            $purchaseObject->students_id = $student->id;
            $purchaseObject->supporters_id = $student->supporters_id;
            $purchaseObject->price = isset($purchase['price']) ? $purchase['price'] : 0;
            $purchaseObject->users_id = 0;
            $purchaseSaved = false;
            $supporter = User::find($student->supporters_id);
            try {
                $purchaseObject->save();
                $ids[] = [
                    "factor_number" => $purchaseObject->factor_number,
                    "woo_id" => $product->woo_id
                ];
                $purchaseSaved = true;

                if ($supporter->mobile && $isInsert) {
                    $msg = "کاربر گرامی {$supporter->first_name} {$supporter->last_name}\n";
                    $msg .= "محصول {$product->name} توسط  {$student->first_name} {$student->last_name} به مبلغ {$purchase->price} خریداری شد.\nعارف";
                    Sms::send($supporter->mobile, $msg);
                }
            } catch (Exception $e) {
                $fails[] = $purchase;
                Log::info("Fail 3" . $purchase['factor_number']);
            }
            if ($student) {
                if ($student->supporters_id) {
                    $student->own_purchases = $student->purchases()->where('supporters_id', $student->supporters_id)
                        ->where('is_deleted', false)->where('type', '!=', 'site_failed')->where('type','!=','manual_failed')->count();
                }
                $student->other_purchases = $student->purchases()->where(function ($query) use ($student) {
                    if ($student->supporters_id) $query->where('supporters_id', '!=', $student->supporters_id)->orWhere('supporters_id', 0);;
                })
                    ->where('is_deleted', false)->where('type', '!=', 'site_failed')->where('type','!=','manual_failed')
                    ->count();
                $student->today_purchases = $student->purchases()->where('created_at', '>=', date("Y-m-d 00:00:00"))->where('is_deleted', false)->where('type', '!=', 'site_failed')->where('type','!=','manual_failed')->count();
                try {
                    $student->save();
                } catch (Exception $e) {
                    // dd($e);
                    // $fails[] = $student;
                }
            }
            if ($purchaseSaved) {
                if ($product->classrooms) {
                    foreach ($product->classrooms as $classroom) {
                        $studentClassRoom = StudentClassRoom::where('students_id', $student->id)->where("class_rooms_id", $classroom->id)->first();
                        if ($studentClassRoom == null) {
                            $studentClassRoom = new StudentClassRoom();
                            $studentClassRoom->students_id = $student->id;
                            $studentClassRoom->class_rooms_id = $classroom->id;
                            $studentClassRoom->users_id = -1;
                            try {
                                $studentClassRoom->save();
                            } catch (Exception $e) {
                                // dd('classroom '.$e);
                            }
                        }
                    }
                }
            }
        }
        return [
            "added_ids" => $ids,
            "fails" => $fails
        ];
    }
    /**
     * get students using select2 with ajax
     *
     *
     * @return \Illuminate\Http\Response
     */
    //---------------------AJAX-----------------------------------
    public function getStudents(Request $request)
    {

        $search = trim($request->search);
        if ($search == '') {
            $students = Student::orderby('id', 'desc')->select('id', 'first_name', 'last_name', 'phone')->where(
                'is_deleted',
                false
            )->where('banned', false)->where('archived', false)->get();
        } else {
            $students = Student::select('id', 'first_name', 'last_name', 'phone',DB::raw("CONCAT(first_name,' ',last_name)"))->where(
                'is_deleted',
                false
            )->where('banned', false)->where('archived', false)->where(function ($query) use ($search) {
                $query->where(DB::raw("CONCAT(first_name,' ',last_name)"),'like','%'.$search.'%')->orWhere('phone','like','%'.$search.'%');
            })->orderby('id','desc')->get();
        }
        $response = array();
        foreach ($students as $student) {
            $response[] = array(
                "id" => $student->id,
                "text" => $student->first_name . ' ' . $student->last_name . '-' . $student->phone
            );
        }
        $response[] = [
            "id" => 0,
            "text" => "-"
        ];
        return $response;
    }
 /**
     * get products using select2 with ajax
     *
     *
     * @return \Illuminate\Http\Response
     */
    //---------------------AJAX-----------------------------------
    public function getProducts(Request $request)
    {

        $search = trim($request->search);
        if ($search == '') {
            $products = Product::orderby('id', 'desc')->where('is_deleted',false)->get();
        } else {
            $products = Product::where('is_deleted',false)->where('name', 'like', '%'.$search.'%')->orderby('id','desc')->get();
        }
        $response = array();
        foreach ($products as $product) {
            $response[] = array(
                "id" => $product->id,
                "text" => (($product->parents!='') ? $product->parents . '->':'') . $product->name 
            );
        }
        $response[] = [
            "id" => 0,
            "text" => "-"
        ];
        return $response;
    }
    //---------------------API------------------------------------
    public function apiAddStudents(Request $request)
    {
        $students = $request->input('students', []);
        $ids = [];
        $fails = [];
        foreach ($students as $student) {
            if (!isset($student['phone']) || !isset($student['last_name'])) {
                $fails[] = $student;
                continue;
            }
            $studentObject = new Student;
            foreach ($student as $key => $value) {
                $studentObject->$key = $value;
            }
            try {
                $studentObject->save();
                $ids[] = $studentObject->phone;
            } catch (Exception $e) {
                $fails[] = $student;
            }
        }
        return [
            "added_ids" => $ids,
            "fails" => $fails
        ];
    }
    public function test()
    {
        $col_ones = DB::table('new_students')
            ->select('phone')
            ->get();
        $students = [];
        $ids = [];
        foreach ($col_ones as $item) {
            $item->phone = '0' . substr($item->phone, 0, -3);
            $students[] = Student::where('is_deleted', false)->where('phone', $item->phone)->first();
        }
        for ($i = 0; $i < count($students); $i++) {
            if ($students[$i]) {
                $ids[$i] = $students[$i]->id;
            }
        }
        foreach ($ids as $id) {
            $purchase = new Purchase;
            $purchase->products_id = 425;
            $purchase->users_id = 0;
            $purchase->type = 'manual';
            $purchase->description = 'همایش ۱۰ بهمن';
            $purchase->students_id = $id;
            $purchase->price = 0;
            $purchase->save();
        }
    }
}

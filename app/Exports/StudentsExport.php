<?php

namespace App\Exports;

use App\Student;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DateTime;

use Morilog\Jalali\CalendarUtils;

class StudentsExport implements FromCollection,WithHeadings
{
    protected $students_select,$from_date,$to_date,$education_level,$major,$supporters_id;

    function __construct($students_select,$supporters_id,$major,$education_level,$from_date=null,$to_date=null) {
        $this->students_select = $students_select;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->education_level = $education_level;
        $this->major = $major;
        $this->supporters_id = $supporters_id;
    }
    public function headings():array
    {
       $array = [
           'کد',
           'نام',
           'نام خانوادگی',
           'رتبه',
           'کد مشاور',
           'عنوان شغل والدین',
           'تلفن خانه',
           'تلفن پدر',
           'تلفن مادر',
           'تلفن',
           'مدرسه',
           'تاریخ ایجاد',
           'تاریخ به روزرسانی',
           'معرفی شده توسط',
           'تلفن دانش آموز',
           'کد منبع',
           'کد پشتیبان',
           'حذف شده',
           'کد افراد',
           'کد بازاریاب',
           'معدل',
           'رمز عبور',
           'مشاهده شده',
           'رشته',
           'مقطع تحصیلی',
           'کد استان',
           'از طریق سایت',
           'توصیف',
           'مشاهده شده توسط پشتیبان',
           'سالن',
           'تاریخ شروع پشتیبان',
           'لغو شده',
           'کد شهر',
           'آرشیو شده',
           'مشاورهای بیرونی',
           'تعداد خریدها بعد از تخصیص به پشتیبان',
           'تعداد خریدها قبل از تخصیص به پشتیبان',
           'تعداد خریدهای امروز'
       ];
       return $array;
    }
    public static function persianToEnglishDigits($pnumber)
    {
        $number = str_replace('۰', '0', $pnumber);
        $number = str_replace('۱', '1', $number);
        $number = str_replace('۲', '2', $number);
        $number = str_replace('۳', '3', $number);
        $number = str_replace('۴', '4', $number);
        $number = str_replace('۵', '5', $number);
        $number = str_replace('۶', '6', $number);
        $number = str_replace('۷', '7', $number);
        $number = str_replace('۸', '8', $number);
        $number = str_replace('۹', '9', $number);
        return $number;
    }
    public static function jalaliToGregorian($pdate)
    {
        $pdate = explode('/', StudentsExport::persianToEnglishDigits($pdate));
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
    public static function gregorianToJalali($Edate){
        $Edate = explode('-', $Edate);
        $date = "";
        if (count($Edate) == 3) {
            $y = (int)$Edate[0];
            $m = (int)$Edate[1];
            $d = (int)$Edate[2];
            if ($d > $y) {
                $tmp = $d;
                $d = $y;
                $y = $tmp;
            }
            $y = (($y < 1000) ? $y + 1300 : $y);
            $jalali = CalendarUtils::toJalali($y, $m, $d);
            $jalali = $jalali[0] . "/" . $jalali[1] . "/" . $jalali[2];
        }
        return $jalali;

    }
    public function changeIntTypeToString($consultants_id,$is_deleted,$own_purchases,$other_purchases,$today_purchases,$marketers_id,$viewed,$archived,$supporter_seen){
        $array = [$consultants_id,
                  $is_deleted,
                  $own_purchases,
                  $other_purchases,
                  $today_purchases,
                  $marketers_id,
                  $viewed,
                  $archived,
                  $supporter_seen];
        for($i = 0; $i < count($array); $i++){
            $array[$i] = strval($array[$i]);
        }
        return $array;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $students = Student::where('is_deleted',false);
        if($this->students_select != null){
            if ($this->students_select == 'archive_students') {
                $students = $students->where('archived', true);
            } elseif ($this->students_select == 'black_students') {
                $students = $students->where('banned', true);
            }
        }
        if ($this->supporters_id != null) {
            $supporters_id = (int)$this->supporters_id;
            $students = $students->where('supporters_id', $supporters_id);
        }
        if ($this->education_level != null) {
            $egucation_level = strval($this->education_level);
            $students = $students->where('egucation_level', $egucation_level);
        }
        if ($this->major != null) {
            $major = $this->major;
            $students = $students->where('major', $major);
        }
        if ($this->from_date != null) {
            $from_date = $this->jalaliToGregorian($this->from_date);
            if ($from_date != '') $students = $students->where('created_at', '>=', $from_date);
        }
        if ($this->to_date != null) {
            $to_date = $this->jalaliToGregorian($this->to_date);
            if ($to_date != '') $students = $students->where('created_at', '<=', $to_date);
        }
        $students = $students->get();
        foreach($students as $student){
            $array = $this->changeIntTypeToString($student->consultants_id,$student->is_deleted,$student->own_purchases,$student->other_purchases,$student->today_purchases,$student->marketers_id,$student->viewed,$student->archived,$student->supporter_seen);
            $student->consultants_id = $array[0];
            $student->is_deleted = $array[1];
            $student->own_purchases = $array[2];
            $student->other_purchases = $array[3];
            $student->today_purchases = $array[4];
            $student->marketers_id = $array[5];
            $student->viewed = $array[6];
            $student->archived = $array[7];
            $student->supporter_seen = $array[8];
            switch ($student->major) {
                case 'experimental':
                    $student->major = 'تجربی';
                    break;
                case 'mathematics':
                    $student->major = 'ریاضی';
                    break;
                case 'other':
                    $student->major = 'دیگر';
                    break;
                case 'humanities':
                    $student->major = 'انسانی';
                    break;
                case 'art':
                    $student->major = 'هنر';
                    break;
            }
            $student->created_at = $this->gregorianToJalali($student->created_at);
            $student->updated_at = $this->gregorianToJalali($student->updated_at);
        }
        return $students;
    }
}

<?php

namespace App\Imports;

use App\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;



class StudentsImport implements ToModel, WithChunkReading, ShouldQueue
{
    use Importable;
    public $fails = [];

    public function chunkSize(): int
    {
        return 1000;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $majors = [
            'ریاضی' => 'mathematics',
            'تجربی' => 'experimental',
            'انسانی' => 'humanities',
            'هنر' => 'art',
            'غیره' => 'other'
        ];

        $educationLevels = ['6','7','8','9','10','11','12','13','14'];
        $educationLevelsInPersian = [
            'ششم' => '6',
            'شش' => '6',
            'هفتم' => '7',
            'هفت' => '7',
            'هشتم' => '8',
            'هشت' => '8',
            'نهم' => '9',
            'نه' => '9',
            'دهم' => '10',
            'ده' => '10',
            'یازدهم' => '11',
            'یازده' => '11',
            'دوازدهم' => '12',
            'دوازده' => '12',
            'فارغ التحصیل' => '13',
            'دانشجو' => '14'
        ];
        if((int)$row[0]==0){
            return null;
        }

        $educationLevel = $row[3];
        if(!isset($educationLevels[$educationLevel]) && isset($educationLevelsInPersian[$educationLevel])){
            $educationLevel = $educationLevelsInPersian[$educationLevel];
        }else if(!isset($educationLevels[$educationLevel]) && !isset($educationLevelsInPersian[$educationLevel])){
            $educationLevel = null;
        }

        $student = Student::where('phone', ((strpos($row[0], '0')!==0)?'0':'') . $row[0])->first();

        if($student===null) {
            Log::info("success:" . ((strpos($row[0], '0')!==0)?'0':'') . $row[0]);
            return new Student([
                'phone' => ((strpos($row[0], '0')!==0)?'0':'') . $row[0],
                'first_name' => $row[1],
                'last_name' => $row[2],
                'egucation_level' => $educationLevel,
                'parents_job_title' => $row[4],
                'home_phone' => $row[5],
                'father_phone' => $row[6],
                'mother_phone' => $row[7],
                'school' => $row[8],
                'average' => $row[9],
                'major' => ($row[10]!='' && strtoupper($row[10])!='NULL' && isset($majors[$row[10]]))?$majors[$row[10]]: null,
                'introducing' => $row[11],
                'student_phone' => $row[12],
                'cities_id' => (int)$row[13],
                'sources_id' => $row[14],
                'supporters_id' => $row[15]
            ]);
        }else {
            Log::info("fail:" . $student->phone);
            $this->fails[] = $student->phone;
        }

        return $student;
    }

    public function getFails()
    {
        return $this->fails;
    }
}

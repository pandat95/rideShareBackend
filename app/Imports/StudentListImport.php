<?php
namespace App\Imports;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Student;

class StudentListImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $firstName = $row['first_name'];
            $lastName = $row['last_name'];
            $stuId = $row['stu_id'];
            $gender = $row['gender'];
            $phoneNumber = $row['phone_number'];
            $address = $row['address'];

            // Create a new student record in the 'students' table
            $student = new Student();
            $student->first_name = $firstName;
            $student->last_name = $lastName;
            $student->stu_id = $stuId;
            $student->gender = $gender;
            $student->phone_number = $phoneNumber;
            $student->address = $address;
            $student->password = bcrypt($request->input('password'));
            $student->save();
        }
    }
}

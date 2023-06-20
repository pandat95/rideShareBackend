<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Imports\UserListImport;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentListImport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

use Laravel\Passport\ClientRepository;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\AuthorizationServer;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;





class RegisterController extends Controller
{
    
    public function register(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        // Retrieve the email and password from the request
        $email = $validatedData['email'];
        $password = $validatedData['password'];
        $hashedPassword = Hash::make($password);
        $student = $this->findMatchedStudent($email, $hashedPassword);
        if ($student) {
            $this->storeStudentData($student,$email, $hashedPassword);
            return response()->json(['message' => 'Registration successful']);
        } else {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }
    }
    private function findMatchedStudent($email, $hashedPassword)
    {
        $filePath = storage_path('app\public\uploads\StudentList.xlsx');
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();        
        for ($row = 2; $row <= $highestRow; $row++) {
            $studentEmail = $worksheet->getCell('A' . $row)->getValue();
            $studentPassword = $worksheet->getCell('B' . $row)->getValue();           
            if ($studentEmail == $email && Hash::check($studentPassword, $hashedPassword)) {
                $student = [
                    'first_name' => $worksheet->getCell('C' . $row)->getValue(),
                    'last_name' => $worksheet->getCell('D' . $row)->getValue(),
                    'stu_id' => $worksheet->getCell('E' . $row)->getValue(),
                    'gender' => $worksheet->getCell('F' . $row)->getValue(),
                    'phone' => $worksheet->getCell('G' . $row)->getValue(),
                    'address' => $worksheet->getCell('H' . $row)->getValue(),
                ];   
                return $student;
            }
        }       
        return null;
    }
    private $authorizationServer;
    private $clientRepository;
    private $tokenRepository;

    public function __construct(
        AuthorizationServer $authorizationServer,
        ClientRepository $clientRepository,
        TokenRepository $tokenRepository
    ) {
        $this->authorizationServer = $authorizationServer;
        $this->clientRepository = $clientRepository;
        $this->tokenRepository = $tokenRepository;
    }
    private function storeStudentData($student, $email, $hashedPassword)
    {
        $studentModel = new Student();
        $studentModel->email = $email;
        $studentModel->first_name = $student['first_name'];
        $studentModel->last_name = $student['last_name'];
        $studentModel->stu_id = $student['stu_id'];
        $studentModel->gender = $student['gender'];
        $studentModel->phone = $student['phone'];
        $studentModel->password = $hashedPassword;
        $studentModel->address = $student['address'];
        $studentModel->remember_token = Str::random(10);
        // Generate an access token for the registered user
        // Generate an access token for the registered user
    //     $client = $this->clientRepository->personalAccessClient();
    //     $token = $this->tokenRepository->create([
    //     'user_id' => $studentModel->stu_id,
    //      'client_id' => $client->id,
    //      'name' => 'Personal Access Token',
    //      'scopes' => [],
    //      'revoked' => false,
    //      'id'=>Str::random(80),
         
    //  ]);
 
     // Save the access token in the api_token column
     $studentModel->api_token = Str::random(80);
     $studentModel->save();
     
    
        
    
        //$studentModel->sendPasswordResetNotification($token);
    }


    }



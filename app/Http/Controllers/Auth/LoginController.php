<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Retrieve the student from the database using the email
        $student = Auth::guard('student')->getProvider()->retrieveByCredentials($credentials);

        if ($student && Hash::check($credentials['password'], $student->getAuthPassword())) {
            //$token = $student->api_token;
            $firstname=$student->first_name;
            $lastname=$student->last_name;
            $phoneNumber=$student->phone;
            $e_mail=$student->email;
            $token = $student->createToken('')->accessToken;

            return response()->json(['student' => $token,'first_name'=>$firstname,'last_name'=>$lastname,'phone'=>$phoneNumber,'email'=>$e_mail],200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        return response()->json(['message' => 'Logout successful']);
    }
}




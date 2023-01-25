<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Traits\ApiResponder;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ApiResponder;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function recaptcha(Request $request)
    {
        $this->validate($request,[
                'email' => 'required|string|email|max:255',
                'g-recaptcha-response' => 'required|recaptcha'
        ]);

        $secret = Config::get('recaptcha.secret_key');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");


        curl_setopt($ch, CURLOPT_POST, 1);


        curl_setopt($ch, CURLOPT_POSTFIELDS,

            "secret=".$secret."&response=".$request['g-recaptcha-response']);




        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);


        $responseData = json_decode($result , TRUE);

        curl_close ($ch);



        if($responseData['success'] == false){

            return $this->errorResponse('Robot olmadığınızı təsdiq edin.',Response::HTTP_BAD_REQUEST);

        }
        return $this->successResponse('Təsdiqləndi!',Response::HTTP_OK);
    }

    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:255'
        ]);

        if (!$token = Auth::attempt($request->only(['email', 'password'])))
            return $this->errorResponse(trans("responses.username_incorrect"));

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }


    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function guard()
    {
        return Auth::guard();
    }
}

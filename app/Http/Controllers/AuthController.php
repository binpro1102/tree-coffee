<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;

class AuthController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth:api', ['except' => ['login', 'register',]]);

    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */


    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            if (!$token = auth()->attempt($validator->validated())) {
                return $this->responseCommon(401, "email hoặc mật khẩu không đúng.", []);
            }
            return $this->createNewToken($token);

        } catch (\Exception $e) {
            return $this->responseCommon(500, "Lỗi hệ thống. vui Lòng thử lại sau.", null);
        }

    }





    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {

        try {


            $validator = Validator::make($request->all(), [
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|confirmed|min:6',
                'address' => 'required',
                'phone_number' => 'required|min:11|numeric'
            ]);

            $user = User::create(
                array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password), 'role' => 'MEMBER']
                )
            );
            // $token = auth()->login($user);
            $token = auth()->attempt($validator->validated());


            return $this->responseCommon(201, "đăng ký thành công", $this->createNewToken($token));


        } catch (\Exception $e) {
            return $this->responseCommon(500, "Lỗi hệ thống. vui Lòng thử lại sau.", null);
        }
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return $this->responseCommon(200, "đăng xuất thành công.", []);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

}

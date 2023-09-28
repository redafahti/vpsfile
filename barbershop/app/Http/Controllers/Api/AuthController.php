<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Customer;
use App\Models\Code;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Helper;
use App\Mail\NotifyMail;

class AuthController extends Controller
{
          /**
        * @OA\Post(
        * path="/api/v1/register",
        * operationId="Register",
        * tags={"Register"},
        * summary="User Register",
        * description="User Register here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name","email", "password", "password_confirmation"},
        *               @OA\Property(property="name", type="text"),
        *               @OA\Property(property="email", type="text"),
        *               @OA\Property(property="password", type="password"),
        *               @OA\Property(property="password_confirmation", type="password")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Register Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Register Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
        public function register(Request $request)
        {
            $response = [];
    
            $checkname = User::where('email', request('email'))->first();
    
            if ($checkname === null) {
        
                if ($request->hasFile('avatar')) {
    
                    $url = url('/');
                    $file = $request->file('avatar');
                    $name = time() . '.' . $file->getClientOriginalExtension();
                    $move = $file->move(public_path('users/'), $name);
                    $imgurl = $url . '/public/users/' . $name;

                    if ($move == true) {
                        $data = [
                            'username' => $request->input('firstname').' '.$request->input('lastname'),
                            'firstname' => $request->input('firstname'),
                            'lastname' => $request->input('lastname'),
                            'email' => $request->input('email'),
                            'password' => Hash::make($request->input('password')),
                            'mobile' => $request->input('mobile'),
                            'profile_picture' => $imgurl,
                            'qrcode' => '000000000',
                            'firebase_id' => $request->input('firebase_id'),
                        ];
    
                        $user = User::create($data);
                        $token =  $user->createToken('authToken')->accessToken;
    
                        if ($user == true) {

                            return response()->json([
                                'token' => $token,
                                'user' => $user
                            ], 200);

                        } else {
                            return response()->json([
                                'message' => 'Error in the database while creating the brand information',
                            ], 500);
                        }
                    } else {
                        $response['success'] = false;
                        $response['messages'] = 'Image not Uploaded';
                    }
                } else {
                    return response()->json([
                        'message' => 'Please select your profile picture',
                    ], 401);
                }
            } else {
                return response()->json([
                    'message' => 'Email address already exists',
                ], 401);
            }
    
        }
    /**
     * @OA\Post(
     * path="/api/v1/oauth/login",
     * operationId="authLogin",
     * tags={"OAuth"},
     * summary="Oauth Login",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="email"),
     *               @OA\Property(property="password", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful Response",
     *          @OA\JsonContent(
     *             example={
     *               "token": {
     *                      "token": "token",
     *               },
     *               "user": {
     *                  "id": 0,
     *                  "code": "UID-00000",
     *                  "firstname": "firstname",
     *                  "lastname": "lastname",
     *                  "email": "mail@mail.com",
     *                  "phone": "000000000",
     *                  "image_profile": "http://host/img/00000.jpeg",
     *                  "qrcode": "00000000000",
     *                  "customer_name": "customer_name",
     *                  "status": "Active",
     *              }
     *            }
     * 
     *            
     *          )
     *       ),
     *      @OA\Response(
     *          response=401, 
     *          description="Failed to authenticate",
     *          @OA\JsonContent(
     *             example={
     *                 "message": "Error message",
     *             }
     *          )
     *      ),
     * 
     *      @OA\Response(
     *          response=404, 
     *          description="Not Found",
     *          @OA\JsonContent(
     *             example={
     *                 "message": "The route {link} could not be found.",
     *             }
     *          )
     *      ),
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ],[
            'password.required' => 'Le champ Mot de passe est requis',
            'email.required' => 'Le champ Email est requis',
            'email.email' => 'Email ne constitue pas une adresse email valide'
        ]);

        $data = User::where('email', request('email'))->get();
        $checkemail = $data->count();

        if ($checkemail == 1) {

            foreach ($data as $user) {
                $hashedPassword =  $user->password;
            }

            if (Hash::check(request('password'), $hashedPassword)) {

                if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {

                    $user = User::find(Auth::user()->id);
                    $user_token['token'] = $user->createToken('appToken')->accessToken;

                    return response()->json([
                        'token' => $user_token,
                        'user' => $user
                    ], 200);
                } else {

                    return response()->json([
                        'message' => 'Failed to authenticate',
                    ], 401);
                }
            } else {
                return response()->json([
                    'message' => 'Password not match',
                ], 401);
            }
        } else {

            return response()->json([
                'message' => 'User email not exist',
            ], 401);
        }
    }

    /**
     * @OA\Post(
     * path="/api/v1/oauth/logout",
     * operationId="authLogout",
     * tags={"OAuth"},
     * summary="Oauth Logout",
     *      @OA\Response(
     *          response=200,
     *          description="Successful Response",
     *          @OA\JsonContent(
     *             example={
     *                 "message": "Successfully logged out",
     *             }
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *             example={
     *                 "message": "Unauthenticated",
     *             })
     *       ),
     *      @OA\Response(
     *          response=404, 
     *          description="Not Found",
     *          @OA\JsonContent(
     *             example={
     *                 "message": "The route {link} could not be found.",
     *             }
     *          )
     *      ),
     * )
     */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}

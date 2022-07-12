<?php

namespace App\Http\Controllers\Api\V1;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{

    /**
     *
     * @OA\Post (
     *      path="/register",
     *      operationId="UserRegister",
     *      summary="Registering user",
     *      tags={"Actions of Register"},
     * @OA\RequestBody(
     *     request="UserRegisterRequest",
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             ref="#/components/schemas/UserData"
     *         )
     *     )
     * ),
     *
     *	    @OA\Response(
     *          response=422,
     *          description="UnprocessibleContent",
     *          @OA\JsonContent(
     *     ref="#/components/schemas/ValidationErrors"
     *      )
     *      ),
     *	    @OA\Response(
     *          response=201,
     *          description="User`s Data registered",
     *     @OA\JsonContent(
     *     ref="#/components/schemas/UserRegisteredData"
     * )
     *      ),
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $user=  User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'is_active'=>true

        ]);
        $token=$user->createToken('myToken')->plainTextToken;
        Auth::login($user);
        return $this->responseCreated(JsonResource::make(['user'=>new UserResource($user),'userToken'=>$token]));
    }

    /**
     *
     * @OA\Post (
     *      path="/login",
     *      operationId="UserLogin",
     *      summary="Authentificating user",
     *      tags={"Actions of Register"},
     * @OA\RequestBody(
     *     request="UserLoginRequest",
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             ref="#/components/schemas/UserLoginCredentials"
     *         )
     *     )
     * ),
     *
     *	    @OA\Response(
     *          response=422,
     *          description="UnprocessibleContent",
     *          @OA\JsonContent(
     *     ref="#/components/schemas/ValidationErrors"
     *      )
     *      ),
     *	    @OA\Response(
     *          response=200,
     *          description="User Authentificated Successfully",
     *     @OA\JsonContent(
     *     ref="#/components/schemas/UserRegisteredData"
     * )
     *      ),
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $user=User::where('email',$request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            $errors=['password'=>'Wrong Password'];
            $message='Provided Credentials Are Incorrect';
            return $this->responseInvalidData($message,$errors);
        }else{
            $token=$user->createToken("my-token")->plainTextToken;
            $user->is_active=true;
            $user->save();
            \auth()->login($user);
            return $this->responseOk(JsonResource::make(['token'=>$token,'user'=>new UserResource($user)]));
        }
    }
    /**
     *
     * @OA\Get (
     *      path="/user",
     *      operationId="GetLoggedINUser",
     *      summary="Get Logged in User",
     *      tags={"Actions of Register"},
     *    @OA\SecurityScheme(
     *      securityScheme="bearerAuth",
     *      in="header",
     *      name="Authorization",
     *      type="http",
     *      scheme="Bearer"
     * ),
     *
     *	  @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      @OA\JsonContent(
     *     example={"message":"Unauthinticated"},
     *       @OA\Schema(
     *        type="object",
     *        @OA\Property(
     *        property="message",
     *        type="string",
     *        example="Unauthinticated",
     *              )
     *           )
     *      )
     * ),
     *	    @OA\Response(
     *          response=200,
     *          description=" Authentificated User",
     *     @OA\JsonContent(
     *     ref="#/components/schemas/UserDetails"
     * )
     *      ),
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getLoggedInUser(Request $request)
    {
        return $this->responseOk(JsonResource::make(['user'=>$request->user()]));
    }

    /**
     *
     * @OA\Get (
     *      path="/checkToken",
     *      operationId="CheckTokenIsValid",
     *      summary="Chack if the token is valid",
     *      tags={"Actions of Register"},
     *	  @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      @OA\JsonContent(
     *     example={"message":"Unauthinticated"},
     *       @OA\Schema(
     *        type="object",
     *        @OA\Property(
     *        property="message",
     *        type="string",
     *        example="Unauthinticated"
     *              )
     *           )
     *      )
     * ),
     *	    @OA\Response(
     *          response=200,
     *          description=" Token Is Valid",
     *
     *     @OA\JsonContent(
     *     example=false,
     *      @OA\Schema(
     *
     *          type="boolean",
     *          )
     * )
     *      ),
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkToken(Request $request)
    {
        if (\auth()->id()==$request->user()->id) {
           return true;
        } else {
            return false;
        }

    }
    /**
     *
     * @OA\Post (
     *      path="/logout",
     *      operationId="Logout",
     *      summary="Logout",
     *      tags={"Actions of Register"},
     *	  @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      @OA\JsonContent(
     *     example={"message":"Unauthinticated"},
     *       @OA\Schema(
     *        type="object",
     *        @OA\Property(
     *        property="message",
     *        type="string",
     *        example="Unauthinticated"
     *              )
     *           )
     *      )
     * ),
     *	    @OA\Response(
     *          response=200,
     *          description="Successfully Loged Out",
     *
     *     @OA\JsonContent(
     *     example=false,
     *      @OA\Schema(
     *
     *          type="boolean",
     *          )
     * )
     *      ),
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        $user=$request->user();
        $user->is_active=false;
        $user->save();
        return true;
    }

}

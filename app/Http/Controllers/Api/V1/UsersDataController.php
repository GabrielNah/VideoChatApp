<?php


namespace App\Http\Controllers\Api\V1;


use App\Events\SendMessageEvent;
use App\Http\Controllers\BaseController;
use App\Http\Requests\GetChatMessagesrequest;
use App\Http\Requests\MessageAddRequest;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
use App\Models\ChatMessages;
use App\Models\chatModel;
use App\Models\User;
use Illuminate\Http\Request;

class UsersDataController extends BaseController
{
    /**
     *
     * @OA\Get (
     *      path="/users",
     *      operationId="GetAllUser",
     *      summary="Get All Users",
     *      tags={"Chat`s Routes"},
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
     *          description=" All Users",
     *     @OA\JsonContent(
     *     example="[{id:5,name:Examplename,email:example@mail.ru,is_active:1}]",
     *     @OA\Schema(
     *     type="array",
     *     @OA\Items(
     *     ref="#/components/schemas/UserDataResponse"
     *          )
     *        )

     *      )
     *    )
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAllUsers(Request $request)
    {
        return $this->responseOk(UserResource::collection(User::where('id','!=',$request->user()->id)->get()));
    }


    /**
     *
     * @OA\Post (
     *      path="/chat",
     *      operationId="Get All messages In Chat",
     *      summary="GetMessagesFromCHat",
     *      tags={"Chat`s Routes"},
     * @OA\RequestBody(
     *     request="Chat messages",
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             ref="#/components/schemas/GetMessages"
     *         )
     *     )
     * ),
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
     *          response=422,
     *          description="UnprocessibleContent",
     *          @OA\JsonContent(
     *     ref="#/components/schemas/ValidationErrors"
     *      )
     *      ),
     *	    @OA\Response(
     *          response=200,
     *          description="All Messages Of 2 users",
     *     @OA\JsonContent(
     *     ref="#/components/schemas/UserRegisteredData"
     * )
     *      ),
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */




    public function getChatMessages(GetChatMessagesrequest $request)
    {
        $messages= ChatMessages::where('to',$request->to)->where('from',$request->from)
            ->orWhere('to',$request->from)->where('from',$request->to)->orderBy('created_at')->get();

        return $this->responseOk(MessageResource::collection($messages));
    }


    /**
     *
     * @OA\Post (
     *      path="/message",
     *      operationId="SendMessageInChat",
     *      summary="SendMessageInChat",
     *      tags={"Chat`s Routes"},
     * @OA\RequestBody(
     *     request="Chat messages",
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             ref="#/components/schemas/GetMessages"
     *         )
     *     )
     * ),
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
     *          response=422,
     *          description="UnprocessibleContent",
     *          @OA\JsonContent(
     *     ref="#/components/schemas/ValidationErrors"
     *      )
     *      ),
     *	    @OA\Response(
     *          response=201,
     *          description="Message Sent",
     *     @OA\JsonContent(
     *     ref="#/components/schemas/GetMessages"
     * )
     *      ),
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */





    public function appendInChat(MessageAddRequest $request)
    {
           $message= ChatMessages::create([
                'to'=>$request->to,
                'from'=>$request->from,
                'message'=>$request->message,
            ]);
            event(new SendMessageEvent($message));
            return $this->responseCreated(new MessageResource($message));


    }
}

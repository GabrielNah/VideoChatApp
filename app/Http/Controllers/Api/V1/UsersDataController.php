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
use Illuminate\Http\Resources\Json\JsonResource;
use mysql_xdevapi\Exception;

class UsersDataController extends BaseController
{
    public function getAllUsers(Request $request)
    {
        return $this->responseOk(UserResource::collection(User::where('id','!=',$request->user()->id)->get()));
    }

    public function getChatMessages(GetChatMessagesrequest $request)
    {
        $messages= ChatMessages::where('to',$request->to)->where('from',$request->from)
            ->orWhere('to',$request->from)->where('from',$request->to)->orderBy('created_at')->get();

        return $this->responseOk(MessageResource::collection($messages));
    }

    public function appendInChat(MessageAddRequest $request)
    {
           $message= ChatMessages::create([
                'to'=>$request->to,
                'from'=>$request->from,
                'message'=>$request->message,
            ]);
            event(new SendMessageEvent($message));
            return true;


    }
}

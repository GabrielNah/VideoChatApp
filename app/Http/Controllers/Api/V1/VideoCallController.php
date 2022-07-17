<?php


namespace App\Http\Controllers\Api\V1;


use App\Events\videoCallRequestEvent;
use App\Http\Controllers\BaseController;
use App\Http\Requests\sendVideoCallRequest;

class VideoCallController extends BaseController
{
    public function sendVideoCallrequest(sendVideoCallRequest $request)
    {
        event(new videoCallRequestEvent($request->to,$request->from));
    }
}

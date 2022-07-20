<?php


namespace App\Http\Controllers\Api\V1;

use App\Classes\AgoraDynamicKey\RtcTokenBuilder;
use App\Classes\AgoraDynamicKey\AgoraTokenGenerator;
use App\Events\videoCallRequestEvent;
use App\Http\Controllers\BaseController;
use App\Http\Requests\AgoraTokeGeneratingRequest;
use App\Http\Requests\sendVideoCallRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoCallController extends BaseController
{

    /**
     *
     * @OA\Post (
     *      path="/agoraToken",
     *      operationId="get token for videoCall",
     *      summary="GetTokenForVideoCall",
     *      tags={"Calls`s Routes"},
     * @OA\RequestBody(
     *     request="Call Request",
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             ref="#/components/schemas/SendCallRequest"
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
     *          description="Call request was sent",
      *        @OA\JsonContent(
     *     example={"token":"d2342d3fg4326f346b45h7568j67867k789mk7g56v44"},
     *      @OA\Schema(
     *          type="object",
     *          @OA\Property(
     *     property="token",
     *     type="string"
     *          )
     *      )
     *      )
     *   ),
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function token(AgoraTokeGeneratingRequest $request)
    {

        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = $request->channelName;
        $user = "";
        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);

        return $this->responseOk(JsonResource::make(['token'=>$token]));
    }
}

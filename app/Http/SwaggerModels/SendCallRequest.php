<?php


namespace App\Http\SwaggerModels;
/**
 * @OA\Schema(
 *     example={"channelName":"MyChannel"},
 *     type="object"
 * )
 *
 */

class SendCallRequest
{
    /**
     * @OA\Property(
     *     type="from",
     *     property="integer",
     *     example="1"
     * )
     */
    public $channelName;


}

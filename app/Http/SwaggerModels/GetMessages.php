<?php


namespace App\Http\SwaggerModels;
/**
 * @OA\Schema(
 *     example={"from":"1","to":"115","message":"message"},
 *     type="object"
 * )
 *
 */

class GetMessages
{
    /**
     * @OA\Property(
     *     type="from",
     *     property="integer",
     *     example="1"
     * )
     */
    public $from;
    /**
     * @OA\Property(
     *     type="integer",
     *     property="to",
     *     example="2"
     * )
     */
    public $to;
    /**
     * @OA\Property(
     *     type="string",
     *     property="message",
     *     example="This is Example Message"
     * )
     */
    public $message;
}

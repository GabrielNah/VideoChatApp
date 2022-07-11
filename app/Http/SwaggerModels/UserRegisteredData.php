<?php


namespace App\Http\SwaggerModels;
/**
 * @OA\Schema(
 *     required={"user", "token"},
 *     example={"user":{"id":5,"name":"Examplename","email":"example@mail.ru"},"token":"kzhfkjsdhfjkhsdjfh"},
 * )
 */

class UserRegisteredData
{
    /**
     * @OA\Property(
     *     type="object",
     *     property="user",
     *     @OA\Schema(
     *     ref="#/components/schemas/UserDataResponse"
     * )
     * )
     */
    public $user;
    /**
     * @OA\Property(
     *     type="string",
     *     property="token",
     *     example="15|428a546f3244e39a8e9007d4c"
     * )
     */
    public $token;
}

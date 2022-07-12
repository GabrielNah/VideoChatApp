<?php


namespace App\Http\SwaggerModels;
/**
 * @OA\Schema(
 *     required={"user", "token"},
 *     example={"user":{"id":5,"name":"Examplename","email":"example@mail.ru","is_active":true}},
 * )
 */

class UserDetails
{    /**
     * @OA\Property(
     *     type="object",
     *     property="user",
     *     @OA\Schema(
     *     ref="#/components/schemas/UserDataResponse"
     * )
     * )
     */
    public $user;
}

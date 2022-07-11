<?php


namespace App\Http\SwaggerModels;

/**
 * @OA\Schema(
 *     required={"email","password"},
 *     example={"email":"SomeEmail@mail.ru","password":"SomePassword"},
 *     type="object"
 * )
 *
 */
class UserLoginCredentials
{
    /**
     * @OA\Property(
     *     type="string",
     *     property="email",
     *     example="exampleEmail@mail.ru"
     * )
     */
    public $email;
    /**
     * @OA\Property(
     *     type="string",
     *     property="password",
     *     example="examplePassword"
     * )
     */
    public $password;
}

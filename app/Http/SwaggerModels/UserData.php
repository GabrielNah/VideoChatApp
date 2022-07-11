<?php


namespace App\Http\SwaggerModels;
/**
 * @OA\Schema(
 *     required={"name","email","password","password_confirmation"},
 *     example={"name":"SomeNAme","email":"SomeEmail","password":"SomePassword","password_confirmation":"SomePassword"},
 *     type="object"
 * )
 *
 */

class UserData
{
    /**
     * @OA\Property(
     *     type="string",
     *     property="name",
     *     example="exampleName"
     * )
     */
    public $name;
    /**
     * @OA\Property(
     *     type="string",
     *     property="email",
     *     example="exampleEmail@gamil.com"
     * )
     */
    public $emil;
    /**
     * @OA\Property(
     *     type="string",
     *     property="password",
     *     example="examplePassword"
     * )
     */
    public $password;
    /**
     * @OA\Property(
     *     type="string",
     *     property="password_confirmation",
     *     example="examplePassword"
     * )
     */
    public $password_confirmation;

}

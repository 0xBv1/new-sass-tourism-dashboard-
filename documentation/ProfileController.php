<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use App\Traits\Response\HasApiResponse;


class ProfileController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Get(
     *     path="/api/profile/me",
     *     summary="My Profile",
     *     tags={"Profile"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function me()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/profile/logout",
     *     summary="Logout",
     *     tags={"Profile"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function logout()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/profile/change/image",
     *     summary="Change Profile Image",
     *     tags={"Profile"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="image",
     *                     type="file"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function changeProfileImage()
    {
    }

    /**
     * @OA\Patch(
     *     path="/api/profile",
     *     summary="Update Profile",
     *     tags={"Profile"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                      example="Ahmed Nasr"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     example="P@ssw0rd!!"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string",
     *                     example="P@ssw0rd!!"
     *                 ),
     *                 @OA\Property(
     *                     property="birthdate",
     *                     type="string",
     *                     example="2000-01-01"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string",
     *                     example="01150225286"
     *                 ),
     *                 @OA\Property(
     *                     property="nationality",
     *                     type="string",
     *                     example="Egypt"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function update()
    {
    }
}

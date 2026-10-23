<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use App\Traits\Response\HasApiResponse;

class ContactRequestController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Post(
     *     path="/api/contact-requests",
     *     summary="Create Contact Us Message",
     *     tags={"ContactRequest"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="country",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="subject",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                 ),
     *                 example={"name": "Robbert Resindel", "email": "robbert.resindel@gmail.com", "phone": "+2011111111111", "country": "Poland", "subject": "Tour Inquiry", "message": "What about traveling to turkey"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *     )
     * )
     */
    public function store()
    {
    }
}

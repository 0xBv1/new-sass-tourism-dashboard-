<?php

namespace Documentation;

/**
 * @OA\Schema(
 *  schema="Seo",
 *  title="Seo Schema",
 *                     @OA\Property(
 *                         property="meta_title",
 *                         type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="meta_description",
 *                         type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="meta_keywords",
 *                         type="string",
 *                         example="Lorem,Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="og_title",
 *                         type="string",
 *                        example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="og_description",
 *                        type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                    @OA\Property(
 *                          property="viewport",
 *                         type="string",
 *                          example="X"
 *                      ),
 *                      @OA\Property(
 *                          property="roboots",
 *                         type="string",
 *                          example="X"
 *                      ),
 *                     @OA\Property(
 *                         property="og_image",
 *                         type="string",
 *                         example="htttp://baseUrl/storage/image.png"
 *                     ),
 * )
 */
class SeoController extends Controller
{

}

<?php

namespace Documentation;


/**
 * @OA\Schema(
 *  schema="CartItem",
 *  title="Cart Item Schema",
 *                     @OA\Property(
 *                         property="tour",
 *                         type="object",
 *                         oneOf={@OA\Schema(ref="#/components/schemas/Tour")},
 *                     ),
 *                     @OA\Property(
 *                         property="options",
 *                         type="array",
 *                         @OA\Items(type="object",oneOf={@OA\Schema(ref="#/components/schemas/TourOption")},)
 *                     ),
 *                     @OA\Property(
 *                         property="adults",
 *                         type="number",
 *                         example="2"
 *                     ),
 *                     @OA\Property(
 *                         property="children",
 *                         type="number",
 *                         example="1"
 *                     ),
 *                     @OA\Property(
 *                         property="infants",
 *                         type="number",
 *                         example="0"
 *                     ),
 *                     @OA\Property(
 *                         property="start_date",
 *                         type="string",
 *                         example="2023-05-30T00:00:00.000000Z"
 *                     ),
 * )
 */
class CartItemController
{

}

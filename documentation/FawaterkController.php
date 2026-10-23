<?php

namespace Documentation;

class FawaterkController
{
    /**
     * @OA\Get (
     *     path="/api/payments/fawaterk/methods",
     *     summary="List Available Methods For fawaterk",
     *     tags={"Payment"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function methods()
    {
    }
}

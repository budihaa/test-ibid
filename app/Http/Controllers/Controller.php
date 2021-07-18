<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
}

/**
 * @OA\Info(
 *   title="Test IBID API",
 *   version="1.0",
 *   @OA\Contact(
 *     email="budiharyono4@gmail.com",
 *     name="Budi Haryono"
 *   )
 * )
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * ),
 */

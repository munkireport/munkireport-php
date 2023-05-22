<?php

namespace App\Http;

use OpenApi\Annotations as OA;


/**
 * @OA\OpenApi(
 *     security={{"cookieAuth": {}}}
 * )
 *
 * @OA\Info(
 *  title="MunkiReport PHP",
 *  version="6.0.0"
 * )
 *
 * @OA\SecurityScheme(
 *  securityScheme="cookieAuth",
 *  type="apiKey",
 *  in="cookie",
 *  name="munkireport_session",
 * )
 */
class OpenApi
{

}

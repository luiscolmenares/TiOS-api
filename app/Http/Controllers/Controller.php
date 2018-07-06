<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @SWG\Swagger(
 *     schemes={https"},
 *     host="api.tiosplatform.com",
 *     basePath="/api",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="TiOS API",
 *         description="Welcolme to TiOS Platfor API documentation.",
 *         termsOfService="",
 *         @SWG\Contact(
 *             email="luis@tiosplatform.com"
 *         ),
 *         @SWG\License(
 *             name="Private License",
 *             url="URL to the license"
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="For more Info",
 *         url="https://tiosplatform.com"
 *     )
 * )
 */
/**
 * @SWG\SecurityScheme(
 *   securityDefinition="passport",
 *   type="oauth2",
 *   tokenUrl="/oauth/token",
 *   flow="password",
 *   scopes={}
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

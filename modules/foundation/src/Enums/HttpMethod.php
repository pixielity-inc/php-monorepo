<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum representing different HTTP methods.
 *
 * @method static GET() Returns the GET enum instance
 * @method static POST() Returns the POST enum instance
 * @method static PUT() Returns the PUT enum instance
 * @method static DELETE() Returns the DELETE enum instance
 * @method static PATCH() Returns the PATCH enum instance
 * @method static OPTIONS() Returns the OPTIONS enum instance
 * @method static HEAD() Returns the HEAD enum instance
 */
enum HttpMethod: string
{
    use Enum;

    /**
     * The GET method requests a representation of the specified resource. Requests using GET should only retrieve data.
     */
    #[Label('GET')]
    #[Description('The GET method requests a representation of the specified resource. Requests using GET should only retrieve data.')]
    case GET = 'GET';

    /**
     * The POST method is used to submit an model to the specified resource, often causing a change in state or side effects on the server.
     */
    #[Label('POST')]
    #[Description('The POST method is used to submit an model to the specified resource, often causing a change in state or side effects on the server.')]
    case POST = 'POST';

    /**
     * The PUT method replaces all current representations of the target resource with the request payload.
     */
    #[Label('PUT')]
    #[Description('The PUT method replaces all current representations of the target resource with the request payload.')]
    case PUT = 'PUT';

    /**
     * The DELETE method deletes the specified resource.
     */
    #[Label('DELETE')]
    #[Description('The DELETE method deletes the specified resource.')]
    case DELETE = 'DELETE';

    /**
     * The PATCH method is used to apply partial modifications to a resource.
     */
    #[Label('PATCH')]
    #[Description('The PATCH method is used to apply partial modifications to a resource.')]
    case PATCH = 'PATCH';

    /**
     * The OPTIONS method is used to describe the communication options for the target resource.
     */
    #[Label('OPTIONS')]
    #[Description('The OPTIONS method is used to describe the communication options for the target resource.')]
    case OPTIONS = 'OPTIONS';

    /**
     * The HEAD method asks for a response identical to that of a GET request, but without the response body.
     */
    #[Label('HEAD')]
    #[Description('The HEAD method asks for a response identical to that of a GET request, but without the response body.')]
    case HEAD = 'HEAD';
}

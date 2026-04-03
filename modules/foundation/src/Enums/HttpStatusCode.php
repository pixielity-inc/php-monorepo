<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum representing HTTP status codes.
 *
 * @method static int CONTINUE() Returns the CONTINUE enum instance
 * @method static int SWITCHING_PROTOCOLS() Returns the SWITCHING_PROTOCOLS enum instance
 * @method static int PROCESSING() Returns the PROCESSING enum instance
 * @method static int OK() Returns the OK enum instance
 * @method static int CREATED() Returns the CREATED enum instance
 * @method static int ACCEPTED() Returns the ACCEPTED enum instance
 * @method static int NON_AUTHORITATIVE_INFORMATION() Returns the NON_AUTHORITATIVE_INFORMATION enum instance
 * @method static int NO_CONTENT() Returns the NO_CONTENT enum instance
 * @method static int RESET_CONTENT() Returns the RESET_CONTENT enum instance
 * @method static int PARTIAL_CONTENT() Returns the PARTIAL_CONTENT enum instance
 * @method static int MULTI_STATUS() Returns the MULTI_STATUS enum instance
 * @method static int ALREADY_REPORTED() Returns the ALREADY_REPORTED enum instance
 * @method static int IM_USED() Returns the IM_USED enum instance
 * @method static int MULTIPLE_CHOICES() Returns the MULTIPLE_CHOICES enum instance
 * @method static int MOVED_PERMANENTLY() Returns the MOVED_PERMANENTLY enum instance
 * @method static int FOUND() Returns the FOUND enum instance
 * @method static int SEE_OTHER() Returns the SEE_OTHER enum instance
 * @method static int NOT_MODIFIED() Returns the NOT_MODIFIED enum instance
 * @method static int USE_PROXY() Returns the USE_PROXY enum instance
 * @method static int RESERVED() Returns the RESERVED enum instance
 * @method static int TEMPORARY_REDIRECT() Returns the TEMPORARY_REDIRECT enum instance
 * @method static int PERMANENT_REDIRECT() Returns the PERMANENT_REDIRECT enum instance
 * @method static int BAD_REQUEST() Returns the BAD_REQUEST enum instance
 * @method static int UNAUTHORIZED() Returns the UNAUTHORIZED enum instance
 * @method static int PAYMENT_REQUIRED() Returns the PAYMENT_REQUIRED enum instance
 * @method static int FORBIDDEN() Returns the FORBIDDEN enum instance
 * @method static int NOT_FOUND() Returns the NOT_FOUND enum instance
 * @method static int METHOD_NOT_ALLOWED() Returns the METHOD_NOT_ALLOWED enum instance
 * @method static int NOT_ACCEPTABLE() Returns the NOT_ACCEPTABLE enum instance
 * @method static int PROXY_AUTHENTICATION_REQUIRED() Returns the PROXY_AUTHENTICATION_REQUIRED enum instance
 * @method static int REQUEST_TIMEOUT() Returns the REQUEST_TIMEOUT enum instance
 * @method static int CONFLICT() Returns the CONFLICT enum instance
 * @method static int GONE() Returns the GONE enum instance
 * @method static int LENGTH_REQUIRED() Returns the LENGTH_REQUIRED enum instance
 * @method static int PRECONDITION_FAILED() Returns the PRECONDITION_FAILED enum instance
 * @method static int PAYLOAD_TOO_LARGE() Returns the PAYLOAD_TOO_LARGE enum instance
 * @method static int URI_TOO_LONG() Returns the URI_TOO_LONG enum instance
 * @method static int UNSUPPORTED_MEDIA_TYPE() Returns the UNSUPPORTED_MEDIA_TYPE enum instance
 * @method static int RANGE_NOT_SATISFIABLE() Returns the RANGE_NOT_SATISFIABLE enum instance
 * @method static int EXPECTATION_FAILED() Returns the EXPECTATION_FAILED enum instance
 * @method static int IM_A_TEAPOT() Returns the IM_A_TEAPOT enum instance
 * @method static int MISDIRECTED_REQUEST() Returns the MISDIRECTED_REQUEST enum instance
 * @method static int UNPROCESSABLE_ENTITY() Returns the UNPROCESSABLE_ENTITY enum instance
 * @method static int LOCKED() Returns the LOCKED enum instance
 * @method static int FAILED_DEPENDENCY() Returns the FAILED_DEPENDENCY enum instance
 * @method static int TOO_EARLY() Returns the TOO_EARLY enum instance
 * @method static int UPGRADE_REQUIRED() Returns the UPGRADE_REQUIRED enum instance
 * @method static int PRECONDITION_REQUIRED() Returns the PRECONDITION_REQUIRED enum instance
 * @method static int TOO_MANY_REQUESTS() Returns the TOO_MANY_REQUESTS enum instance
 * @method static int REQUEST_HEADER_FIELDS_TOO_LARGE() Returns the REQUEST_HEADER_FIELDS_TOO_LARGE enum instance
 * @method static int UNAVAILABLE_FOR_LEGAL_REASONS() Returns the UNAVAILABLE_FOR_LEGAL_REASONS enum instance
 * @method static int INTERNAL_SERVER_ERROR() Returns the INTERNAL_SERVER_ERROR enum instance
 * @method static int NOT_IMPLEMENTED() Returns the NOT_IMPLEMENTED enum instance
 * @method static int BAD_GATEWAY() Returns the BAD_GATEWAY enum instance
 * @method static int SERVICE_UNAVAILABLE() Returns the SERVICE_UNAVAILABLE enum instance
 * @method static int GATEWAY_TIMEOUT() Returns the GATEWAY_TIMEOUT enum instance
 * @method static int VERSION_NOT_SUPPORTED() Returns the VERSION_NOT_SUPPORTED enum instance
 * @method static int VARIANT_ALSO_NEGOTIATES() Returns the VARIANT_ALSO_NEGOTIATES enum instance
 * @method static int INSUFFICIENT_STORAGE() Returns the INSUFFICIENT_STORAGE enum instance
 * @method static int LOOP_DETECTED() Returns the LOOP_DETECTED enum instance
 * @method static int NOT_EXTENDED() Returns the NOT_EXTENDED enum instance
 */
enum HttpStatusCode: int
{
    use Enum;

    /**
     * Informational 1xx
     * 100 Continue.
     */
    #[Label('Continue')]
    #[Description('100 Continue: The server has received the request headers, and the client should proceed to send the request body.')]
    case CONTINUE = 100;

    /**
     * 101 Switching Protocols.
     */
    #[Label('Switching Protocols')]
    #[Description('101 Switching Protocols: The requester has asked the server to switch protocols, and the server has agreed to do so.')]
    case SWITCHING_PROTOCOLS = 101;

    /**
     * 102 Processing.
     */
    #[Label('Processing')]
    #[Description('102 Processing: The server has received and is processing the request, but no response is available yet.')]
    case PROCESSING = 102;

    /**
     * Successful 2xx
     * 200 OK.
     */
    #[Label('OK')]
    #[Description('200 OK: The request was successful, and the response contains the requested data.')]
    case OK = 200;

    /**
     * 201 Created.
     */
    #[Label('Created')]
    #[Description('201 Created: The request was successful, and a new resource was created.')]
    case CREATED = 201;

    /**
     * 202 Accepted.
     */
    #[Label('Accepted')]
    #[Description('202 Accepted: The request has been accepted for processing, but the processing has not been completed.')]
    case ACCEPTED = 202;

    /**
     * 203 Non-Authoritative Information.
     */
    #[Label('Non-Authoritative Information')]
    #[Description('203 Non-Authoritative Information: The server successfully processed the request, but is returning information that may be from another source.')]
    case NON_AUTHORITATIVE_INFORMATION = 203;

    /**
     * 204 No Content.
     */
    #[Label('No Content')]
    #[Description('204 No Content: The server successfully processed the request, but is not returning any content.')]
    case NO_CONTENT = 204;

    /**
     * 205 Reset Content.
     */
    #[Label('Reset Content')]
    #[Description('205 Reset Content: The server successfully processed the request, and the user agent should reset the document view.')]
    case RESET_CONTENT = 205;

    /**
     * 206 Partial Content.
     */
    #[Label('Partial Content')]
    #[Description('206 Partial Content: The server is delivering only part of the resource due to a range header sent by the client.')]
    case PARTIAL_CONTENT = 206;

    /**
     * 207 Multi-Status.
     */
    #[Label('Multi-Status')]
    #[Description('207 Multi-Status: The message body contains multiple status codes for different parts of the request.')]
    case MULTI_STATUS = 207;

    /**
     * 208 Already Reported.
     */
    #[Label('Already Reported')]
    #[Description('208 Already Reported: The members of a DAV binding have already been enumerated in a previous part of the response.')]
    case ALREADY_REPORTED = 208;

    /**
     * 226 IM Used.
     */
    #[Label('IM Used')]
    #[Description('226 IM Used: The server has fulfilled a GET request for the resource, and the response is a representation of the result of one or more instance-manipulations applied to the current instance.')]
    case IM_USED = 226;

    /**
     * Redirection 3xx
     * 300 Multiple Choices.
     */
    #[Label('Multiple Choices')]
    #[Description('300 Multiple Choices: The request has more than one possible response. The user or user agent should choose one of them.')]
    case MULTIPLE_CHOICES = 300;

    /**
     * 301 Moved Permanently.
     */
    #[Label('Moved Permanently')]
    #[Description('301 Moved Permanently: The resource has been moved to a new URL permanently.')]
    case MOVED_PERMANENTLY = 301;

    /**
     * 302 Found.
     */
    #[Label('Found')]
    #[Description('302 Found: The resource has been temporarily moved to a different URL.')]
    case FOUND = 302;

    /**
     * 303 See Other.
     */
    #[Label('See Other')]
    #[Description('303 See Other: The response to the request can be found under a different URL using a GET method.')]
    case SEE_OTHER = 303;

    /**
     * 304 Not Modified.
     */
    #[Label('Not Modified')]
    #[Description('304 Not Modified: The resource has not been modified since the last request.')]
    case NOT_MODIFIED = 304;

    /**
     * 305 Use Proxy.
     */
    #[Label('Use Proxy')]
    #[Description('305 Use Proxy: The requested resource is available only through a proxy. The proxy address is provided in the response.')]
    case USE_PROXY = 305;

    /**
     * 306 Reserved.
     */
    #[Label('Reserved')]
    #[Description('306 Reserved: This status code was used in a previous version of the HTTP specification. It is reserved for future use.')]
    case RESERVED = 306;

    /**
     * 307 Temporary Redirect.
     */
    #[Label('Temporary Redirect')]
    #[Description('307 Temporary Redirect: The resource has been temporarily moved to a different URL. Future requests should use the original URL.')]
    case TEMPORARY_REDIRECT = 307;

    /**
     * 308 Permanent Redirect.
     */
    #[Label('Permanent Redirect')]
    #[Description('308 Permanent Redirect: The resource has been permanently moved to a new URL, and future requests should use the new URL.')]
    case PERMANENT_REDIRECT = 308;

    /**
     * Client Error 4xx
     * 400 Bad Request.
     */
    #[Label('Bad Request')]
    #[Description('400 Bad Request: The server cannot process the request due to a client error.')]
    case BAD_REQUEST = 400;

    /**
     * 401 Unauthorized.
     */
    #[Label('Unauthorized')]
    #[Description('401 Unauthorized: Authentication is required and has failed or has not yet been provided.')]
    case UNAUTHORIZED = 401;

    /**
     * 402 Payment Required.
     */
    #[Label('Payment Required')]
    #[Description('402 Payment Required: Reserved for future use, currently not used.')]
    case PAYMENT_REQUIRED = 402;

    /**
     * 403 Forbidden.
     */
    #[Label('Forbidden')]
    #[Description('403 Forbidden: The server understands the request, but refuses to authorize it.')]
    case FORBIDDEN = 403;

    /**
     * 404 Not Found.
     */
    #[Label('Not Found')]
    #[Description('404 Not Found: The requested resource could not be found.')]
    case NOT_FOUND = 404;

    /**
     * 405 Method Not Allowed.
     */
    #[Label('Method Not Allowed')]
    #[Description('405 Method Not Allowed: The request method is known by the server but is not supported for the targeted resource.')]
    case METHOD_NOT_ALLOWED = 405;

    /**
     * 406 Not Acceptable.
     */
    #[Label('Not Acceptable')]
    #[Description('406 Not Acceptable: The server cannot generate a response that is acceptable to the client based on the Accept headers sent in the request.')]
    case NOT_ACCEPTABLE = 406;

    /**
     * 407 Proxy Authentication Required.
     */
    #[Label('Proxy Authentication Required')]
    #[Description('407 Proxy Authentication Required: The client must first authenticate itself with the proxy.')]
    case PROXY_AUTHENTICATION_REQUIRED = 407;

    /**
     * 408 Request Timeout.
     */
    #[Label('Request Timeout')]
    #[Description('408 Request Timeout: The server timed out waiting for the request.')]
    case REQUEST_TIMEOUT = 408;

    /**
     * 409 Conflict.
     */
    #[Label('Conflict')]
    #[Description('409 Conflict: The request could not be completed due to a conflict with the current state of the resource.')]
    case CONFLICT = 409;

    /**
     * 410 Gone.
     */
    #[Label('Gone')]
    #[Description('410 Gone: The resource requested is no longer available and will not be available again.')]
    case GONE = 410;

    /**
     * 411 Length Required.
     */
    #[Label('Length Required')]
    #[Description('411 Length Required: The request did not specify the length of its content, which is required by the resource.')]
    case LENGTH_REQUIRED = 411;

    /**
     * 412 Precondition Failed.
     */
    #[Label('Precondition Failed')]
    #[Description('412 Precondition Failed: One or more conditions given in the request header fields evaluated to false when tested by the server.')]
    case PRECONDITION_FAILED = 412;

    /**
     * 413 Payload Too Large.
     */
    #[Label('Payload Too Large')]
    #[Description('413 Payload Too Large: The request is larger than the server is willing or able to process.')]
    case PAYLOAD_TOO_LARGE = 413;

    /**
     * 414 URI Too Long.
     */
    #[Label('URI Too Long')]
    #[Description('414 URI Too Long: The URI provided was too long for the server to process.')]
    case URI_TOO_LONG = 414;

    /**
     * 415 Unsupported Media Type.
     */
    #[Label('Unsupported Media Type')]
    #[Description('415 Unsupported Media Type: The request model has a media type that the server or resource does not support.')]
    case UNSUPPORTED_MEDIA_TYPE = 415;

    /**
     * 416 Range Not Satisfiable.
     */
    #[Label('Range Not Satisfiable')]
    #[Description('416 Range Not Satisfiable: The server cannot provide the portion of the file requested by the client.')]
    case RANGE_NOT_SATISFIABLE = 416;

    /**
     * 417 Expectation Failed.
     */
    #[Label('Expectation Failed')]
    #[Description('417 Expectation Failed: The server cannot meet the requirements of the Expect request-header field.')]
    case EXPECTATION_FAILED = 417;

    /**
     * 418 I'm a teapot.
     */
    #[Label("I'm a teapot")]
    #[Description("418 I'm a teapot: Any attempt to instruct a teapot to do anything other than brewing coffee should be responded to with this error code.")]
    case IM_A_TEAPOT = 418;

    /**
     * 419 Page Expired.
     */
    #[Label('Page Expired')]
    #[Description('419 Page Expired: The page has expired and cannot be accessed.')]
    case PAGE_EXPIRED = 419;

    /**
     * 421 Misdirected Request.
     */
    #[Label('Misdirected Request')]
    #[Description('421 Misdirected Request: The request was directed at a server that is not able to produce a response.')]
    case MISDIRECTED_REQUEST = 421;

    /**
     * 422 Unprocessable Entity.
     */
    #[Label('Unprocessable Entity')]
    #[Description('422 Unprocessable Entity: The request was well-formed but was unable to be followed due to semantic errors.')]
    case UNPROCESSABLE_ENTITY = 422;

    /**
     * 423 Locked.
     */
    #[Label('Locked')]
    #[Description('423 Locked: The resource that is being accessed is locked.')]
    case LOCKED = 423;

    /**
     * 424 Failed Dependency.
     */
    #[Label('Failed Dependency')]
    #[Description('424 Failed Dependency: The request failed due to failure of a previous request.')]
    case FAILED_DEPENDENCY = 424;

    /**
     * 425 Too Early.
     */
    #[Label('Too Early')]
    #[Description('425 Too Early: The server is unwilling to risk processing a request that might be replayed.')]
    case TOO_EARLY = 425;

    /**
     * 426 Upgrade Required.
     */
    #[Label('Upgrade Required')]
    #[Description('426 Upgrade Required: The client should switch to a different protocol as specified in the Upgrade header.')]
    case UPGRADE_REQUIRED = 426;

    /**
     * 428 Precondition Required.
     */
    #[Label('Precondition Required')]
    #[Description('428 Precondition Required: The server requires the request to be conditional.')]
    case PRECONDITION_REQUIRED = 428;

    /**
     * 429 Too Many Requests.
     */
    #[Label('Too Many Requests')]
    #[Description('429 Too Many Requests: The user has sent too many requests in a given amount of time.')]
    case TOO_MANY_REQUESTS = 429;

    /**
     * 431 Request Header Fields Too Large.
     */
    #[Label('Request Header Fields Too Large')]
    #[Description('431 Request Header Fields Too Large: The server is unwilling to process the request because one or more header fields are too large.')]
    case REQUEST_HEADER_FIELDS_TOO_LARGE = 431;

    /**
     * 451 Unavailable For Legal Reasons.
     */
    #[Label('Unavailable For Legal Reasons')]
    #[Description('451 Unavailable For Legal Reasons: The resource is unavailable due to legal reasons.')]
    case UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    /**
     * Server Error 5xx
     * 500 Internal Server Error.
     */
    #[Label('Internal Server Error')]
    #[Description('500 Internal Server Error: The server encountered an unexpected condition that prevented it from fulfilling the request.')]
    case INTERNAL_SERVER_ERROR = 500;

    /**
     * 501 Not Implemented.
     */
    #[Label('Not Implemented')]
    #[Description('501 Not Implemented: The server does not support the functionality required to fulfill the request.')]
    case NOT_IMPLEMENTED = 501;

    /**
     * 502 Bad Gateway.
     */
    #[Label('Bad Gateway')]
    #[Description('502 Bad Gateway: The server, while acting as a gateway or proxy, received an invalid response from the upstream server.')]
    case BAD_GATEWAY = 502;

    /**
     * 503 Service Unavailable.
     */
    #[Label('Service Unavailable')]
    #[Description('503 Service Unavailable: The server is currently unable to handle the request due to a temporary overload or maintenance.')]
    case SERVICE_UNAVAILABLE = 503;

    /**
     * 504 Gateway Timeout.
     */
    #[Label('Gateway Timeout')]
    #[Description('504 Gateway Timeout: The server, while acting as a gateway or proxy, did not receive a timely response from the upstream server.')]
    case GATEWAY_TIMEOUT = 504;

    /**
     * 505 HTTP Version Not Supported.
     */
    #[Label('HTTP Version Not Supported')]
    #[Description('505 HTTP Version Not Supported: The server does not support the HTTP protocol version used in the request.')]
    case VERSION_NOT_SUPPORTED = 505;

    /**
     * 506 Variant Also Negotiates.
     */
    #[Label('Variant Also Negotiates')]
    #[Description('506 Variant Also Negotiates: The server has an internal configuration error: transparent content negotiation for the request results in a circular reference.')]
    case VARIANT_ALSO_NEGOTIATES = 506;

    /**
     * 507 Insufficient Storage.
     */
    #[Label('Insufficient Storage')]
    #[Description('507 Insufficient Storage: The server is unable to store the representation needed to complete the request.')]
    case INSUFFICIENT_STORAGE = 507;

    /**
     * 508 Loop Detected.
     */
    #[Label('Loop Detected')]
    #[Description('508 Loop Detected: The server detected an infinite loop while processing a request.')]
    case LOOP_DETECTED = 508;

    /**
     * 510 Not Extended.
     */
    #[Label('Not Extended')]
    #[Description('510 Not Extended: Further extensions to the request are required for the server to fulfill it.')]
    case NOT_EXTENDED = 510;
}

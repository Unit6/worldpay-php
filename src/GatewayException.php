<?php
/*
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Worldpay;

use Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Gateway Exception
 *
 * Handling request errors from the API.
 *
 * Status Code: 400
 * Reason Phrase: Bad Request
 * Contents:
 * {
 *     "httpStatusCode": 400,
 *     "customCode": "BAD_REQUEST",
 *     "message": "Unsupported Payment Method",
 *     "description": "Some of request parameters are invalid, please check your request. For more information please refer to Json schema.",
 *     "errorHelpUrl": null,
 *     "originalRequest": "{'reusable':false,'clientKey':'T_C_00000000-0000-0000-0000-000000000000'}"
 * }
 *
 *
 */
class GatewayException extends Exception
{
    /**
     * BAD_REQUEST (400)
     *
     * If JSON supplied is not valid or if JSON supplied is
     * valid but the request is not complete or has errors,
     * e.g a blank password. The message will describe what
     * the issue is.
     *
     * @var string
     */
    const BAD_REQUEST = 'BAD_REQUEST';

    /**
     * TKN_EXPIRED (400)
     *
     * If the token used has expired. Non-reusable tokens
     * expire after use in an order request.
     *
     * @var string
     */
    const TKN_EXPIRED = 'TKN_EXPIRED';

    /**
     * ERROR_PARSING_JSON (400)
     *
     * If schema is not valid and we can't parse the JSON.
     *
     * @var string
     */
    const ERROR_PARSING_JSON = 'ERROR_PARSING_JSON';

    /**
     * INVALID_PAYMENT_DETAILS (400)
     *
     * When a card type is unsupported such as Diners.
     *
     * @var string
     */
    const INVALID_PAYMENT_DETAILS = 'INVALID_PAYMENT_DETAILS';

    /**
     * UNAUTHORIZED (401)
     *
     * Occurs in the following scenarios:
     *
     * a) If the Client Key or Service Key you are using is
     *    invalid. This may occur if you have reset your keys.
     *
     * b) If you did not send us the Service Key.
     *
     * c) If the key you are using has expired or has been disabled.
     *
     * d) If you send us the Test Service Key when having used
     *    the Live Client Key in Worldpay.js or the other way around.
     *
     * @var string
     */
    const UNAUTHORIZED = 'UNAUTHORIZED';

    /**
     * MERCHANT_DISABLED (401)
     *
     * If your account is no longer active.
     *
     * @var string
     */
    const MERCHANT_DISABLED = 'MERCHANT_DISABLED';

    /**
     * RECURRING_BILLING_NOT_ENABLED (400)
     *
     * Recurring billing is not enabled on your account, please
     * enable before making recurring orders.
     *
     * @var string
     */
    const RECURRING_BILLING_NOT_ENABLED = 'RECURRING_BILLING_NOT_ENABLED';

    /**
     * RECURRING_BILLING_NOT_SETUP (401)
     *
     * We are in process of setting up recurring billing on your
     * account, please try again later.
     *
     * @var string
     */
    const RECURRING_BILLING_NOT_SETUP = 'RECURRING_BILLING_NOT_SETUP';

    /**
     * TKN_NOT_FOUND (404)
     *
     * If the token used could not be found in our database.
     *
     * @var string
     */
    const TKN_NOT_FOUND = 'TKN_NOT_FOUND';

    /**
     * ORDER_NOT_FOUND (404)
     *
     * If the order used could not be found in our database.
     *
     * @var string
     */
    const ORDER_NOT_FOUND = 'ORDER_NOT_FOUND';

    /**
     * TOKEN_CONFLICT (409)
     *
     * Token is used by another Order Request.
     *
     * @var string
     */
    const TOKEN_CONFLICT = 'TOKEN_CONFLICT';

    /**
     * MEDIA_TYPE_NOT_SUPPORTED (415)
     *
     * If media type is other than "application/json".
     *
     * @var string
     */
    const MEDIA_TYPE_NOT_SUPPORTED = 'MEDIA_TYPE_NOT_SUPPORTED';

    /**
     * INTERNAL_SERVER_ERROR (500)
     *
     * Something went wrong on our side. Please let us know if this persists.
     *
     * @var string
     */
    const INTERNAL_SERVER_ERROR = 'INTERNAL_SERVER_ERROR';

    /**
     * UNEXPECTED_ERROR (500)
     *
     * Likewise, something went wrong on our side. Please
     * let us know if this persists.
     *
     * @var string
     */
    const UNEXPECTED_ERROR = 'UNEXPECTED_ERROR';

    /**
     * API_ERROR (500)
     *
     * Likewise, something went wrong on our side. Please let
     * us know if this persists.
     *
     * @var string
     */
    const API_ERROR = 'API_ERROR';

    /**
     * The HTTP status code.
     *
     * Worldpay uses the following HTTP status codes
     *
     * 200: All ok!
     * 400: Something was wrong with the request, e.g. invalid JSON or token
     * 401: Something was wrong with authentication, e.g. invalid key
     * 404: The requested item was not found, e.g. invalid token
     * 405: The endpoint does not support the requested method
     * 415: Unsupported media type defined in the HTTP header
     * 500: Something went wrong on our end
     *
     * @var int
     */
    protected $httpStatusCode;

    /**
     * Description of the error context.
     *
     * @var string
     */
    protected $customCode;

    /**
     * A description of the error that occurred
     *
     * @var string
     */
    protected $message;

    /**
     * Support information with a description of what to do next.
     *
     * @var string
     */
    protected $description;

    /**
     * Link to website with more information about this error.
     *
     * @var string
     */
    protected $errorHelpUrl;

    /**
     * Original Request object that created this error
     *
     * @var array
     */
    protected $originalRequest;

    /**
     * PSR-7 Response object.
     *
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Create new error instance.
     *
     * @param string  $message       Exception message
     * @param integer $code          User defined exception code.
     * @param ResponseInterface|null $response PSR-7 response from Worldpay.
     * @param array                  $result   Decoded response JSON.
     *
     * @return void
     */
    public function __construct($message = null, $code = 0, ResponseInterface $response = null, array $result = null)
    {
        // Override message and code with $response and $result.
        if ( ! is_null($result)) {
            $message = (isset($result['message']) ? $result['message'] : null);
            $code = $response->getStatusCode();

            $this->response = $response;
            $this->httpStatusCode  = (isset($result['httpStatusCode']) ? $result['httpStatusCode'] : null);
            $this->customCode  = (isset($result['customCode']) ? $result['customCode'] : null);

            $this->description = (isset($result['description']) ? $result['description'] : null);
            $this->originalRequest  = (isset($result['originalRequest']) ? $result['originalRequest'] : null);
            $this->errorHelpUrl = (isset($result['errorHelpUrl']) ? $result['errorHelpUrl'] : null);
        }

        parent::__construct($message, $code);
    }

    /**
     * Get Status Code
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * Get Custom Code
     *
     * @return string
     */
    public function getCustomCode()
    {
        return $this->customCode;
    }

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get PSR-7 Response
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get Original Request
     *
     * @return string
     */
    public function getOriginalRequest()
    {
        return $this->originalRequest;
    }

    /**
     * Get Error Help URL
     *
     * @return string
     */
    public function getErrorHelpUrl()
    {
        return $this->errorHelpUrl;
    }
}
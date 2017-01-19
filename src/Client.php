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

use InvalidArgumentException;
use RuntimeException;
use UnexpectedValueException;

use Unit6\HTTP\Body;
use Unit6\HTTP\Client\Request;
use Unit6\HTTP\Client\Response;
use Unit6\HTTP\Headers;

/**
 * Worldpay Client Class
 *
 * Create a client instance.
 */
final class Client
{
    /**
     * Client Library Version
     *
     * @var constant
     */
    const VERSION = '1.0.0';

    /**
     * API Version
     *
     * @var string
     */
    const API_VERSION = 'v1';

    /**
     * API Host
     *
     * @var string
     */
    const API_HOST = 'api.worldpay.com';

    /**
     * API Service Key
     *
     * @var string
     */
    private $serviceKey;

    /**
     * API Client Key
     *
     * @var string
     */
    private $clientKey;

    /**
     * Client User Agent
     *
     * @var string
     */
    private $clientUserAgent;

    /**
     * Request Timeout
     *
     * @var int
     */
    private $timeout = 65;

    /**
     * Client constructor
     *
     * @param string $keys    Your Worldpay client and service keys
     * @param int    $timeout Connection timeout length
     *
     * @return void
     */
    public function __construct(array $keys, $timeout = false)
    {
        if ( ! function_exists('curl_init')) {
            throw new RuntimeException('cURL is required');
        }

        if ( ! function_exists('json_decode')) {
            throw new RuntimeException('JSON is required');
        }

        if ( empty($keys)) {
            throw new InvalidArgumentException('Worldpay client and service key is required: https://online.worldpay.com/docs/api-keys');
        }

        if ( ! isset($keys['serviceKey']) || empty($keys['serviceKey'])) {
            throw new UnexpectedValueException('Worldpay "serviceKey" missing');
        }

        if ( ! isset($keys['clientKey']) || empty($keys['clientKey'])) {
            throw new UnexpectedValueException('Worldpay "clientKey" missing');
        }

        $this->serviceKey = $keys['serviceKey'];
        $this->clientKey  = $keys['clientKey'];

        if ($timeout) {
            $this->setTimeout($timeout);
        }
    }

    /**
     * Get API Endpoint
     *
     * @param string $action API action
     *
     * @return string
     */
    public function getEndpoint($action)
    {
        return 'https://' . self::API_HOST . '/' . self::API_VERSION . '/' . $action;
    }

    /**
     * Get Service Key
     *
     * @return string
     */
    public function getServiceKey()
    {
        return $this->serviceKey;
    }

    /**
     * Get Client Key
     *
     * @return string
     */
    public function getClientKey()
    {
        return $this->clientKey;
    }

    /**
     * Set Timeout
     *
     * @param int $timeout
     *
     * @return void
     */
    public function setTimeout($timeout = 3)
    {
        $this->timeout = $timeout;
    }

    /**
     * Generate Client User Agent for API Request
     *
     * @return string
     */
    private function getClientUserAgent()
    {
        if ($this->clientUserAgent) {
            return $this->clientUserAgent;
        }

        $arch = (bool)((1<<32)-1) ? 'x64' : 'x86';

        $data = [
            'os.name'      => php_uname('s'),
            'os.version'   => php_uname('r'),
            'os.arch'      => $arch,
            'lang'         => 'php',
            'lang.version' => phpversion(),
            'lib.version'  => self::VERSION,
            'api.version'  => self::API_VERSION,
            'owner'        => 'unit6/worldpay'
        ];

        $this->clientUserAgent = http_build_query($data, '', ';');

        return $this->clientUserAgent;
    }

    /**
     * Sends request to Worldpay API
     *
     * @param string $method   Valid HTTP method
     * @param string $resource URI of API resource
     * @param array  $data     Payload parameters.
     *
     * @return Response
     */
    protected function request($method, $resource, array $data = [])
    {
        $headers = new Headers();
        $headers->set('Authorization', $this->getServiceKey());
        $headers->set('Content-Type', 'application/json');
        $headers->set('X-WP-Client-User-Agent', $this->getClientUserAgent());

        $body = (empty($data) ? null : Body::toJSON($data));

        $uri = $this->getEndpoint($resource);

        $request = new Request($method, $uri, $headers, $body);

        // other cURL options.
        $options = [];

        try {
            $response = $request->send($options);
        } catch (UnexpectedValueException $e) {
            #var_dump($e->getMessage()); exit;

            // handle cURL related errors.
            // see: http://php.net/curl.constants#93950
            $errno = $e->getCode();

            if ($errno === CURLE_SSL_CACERT) { // 60
                // Peer certificate cannot be authenticated with known CA certificates.
                throw new GatewayException(sprintf('Worldpay SSL certificate could not be validated; %s', $e->getMessage()), $errno);
            } elseif ($errno === CURLE_OPERATION_TIMEOUTED) { // 28 or CURLE_OPERATION_TIMEDOUT.
                // Operation timeout. The specified time-out period was reached according to the conditions.
                throw new GatewayException(sprintf('Worldpay timeout or possible order failure; %s', $e->getMessage()), $errno);
            } else {
                throw new GatewayException(sprintf('Worldpay is currently unavailable, please try again later; %s', $e->getMessage()), $errno);
            }
        }

        $disposition = $response->getHeaderLine('Content-Disposition');
        $type = $response->getHeaderLine('Content-Type');

        $result = null;
        if (strpos($disposition, 'attachment') === 0 &&
            strpos($type, 'application/octet-stream') === 0) {
            $result = $response;
        } elseif (strpos($type, 'application/json') === 0) {
            $contents = $response->getBody()->getContents();
            $result = json_decode($contents, $assoc = true);
            if ($errno = json_last_error()) {
                throw new GatewayException(sprintf('JSON malformed; %s', json_last_error_msg()), $errno);
            }

            // Check the status code exists
            if (is_array($result) && isset($result['message'], $result['httpStatusCode'])) {
                throw new GatewayException($result['message'], $result['httpStatusCode'], $response, $result);
            }
        }

        /*
        echo 'Status Code: ' . $response->getStatusCode() . PHP_EOL;
        echo 'Reason Phrase: ' . $response->getReasonPhrase() . PHP_EOL;
        echo 'Contents: ' . PHP_EOL . $contents . PHP_EOL;
        exit;
        */

        return [
            'statusCode' => $response->getStatusCode(),
            'reasonPhrase' => $response->getReasonPhrase(),
            'result' => $result
        ];
    }

    /**
     * Creating a Card Token
     *
     * Note: normally you should not invoke this API directly.
     * It will be used by Worldpay.js when taking card details
     * or by our iOS or Android libraries when using mobile apps.
     *
     * You should only call this API directly if you want to see
     * the card details and your platform is PCI compliant.
     *
     * Creating a new token is done through a POST on the Tokens API,
     * specifying paymentMethod's type as Card.
     *
     * @param PaymentMethodInterface $paymentMethod Object containing all payment details,
     *                                              contents vary by token type
     * @param bool                   $reusable      Indicating whether the token should be
     *                                              used only once (false) or multiple times (true)
     *
     * @return Token
     */
    public function createToken(PaymentMethodInterface $paymentMethod, $reusable = false)
    {
        $clientKey = $this->getClientKey();

        $data = [
            'reusable' => $reusable,
            'paymentMethod' => $paymentMethod->getParameters(),
            'clientKey' => $clientKey
        ];

        $response = $this->request('POST', 'tokens', $data);

        return Token::parse($response, $clientKey);
    }

    /**
     * Get Card Details from Worldpay Token
     *
     * Token details can be obtained by sending a get request.
     *
     * @param string $token Valid Token UUID.
     *
     * @return Token
     */
    public function getToken($token)
    {
        $response = $this->request('GET', sprintf('tokens/%s', $token));

        return Token::parse($response);
    }

    /**
     * Provide CVC for Reusable Card Token
     *
     * Under Visa and MasterCard rules, Worldpay is not allowed
     * to store Card Security Codes. These codes are also known
     * as CVC, CVV or CV2.
     *
     * Note: Normally you should not invoke this API directly.
     * It will be used by Worldpay.js for card on file payments
     * or by our iOS or Android libraries when using mobile apps.
     * You should only call this API directly if you want to
     * see the CVC details and your platform is PCI compliant.
     *
     * Adding or refreshing a CVC on an existing token is performed
     * through a PUT on the Tokens API.
     *
     * @param string $token Valid Token UUID.
     * @param string $cvc   Card Security Code.
     *
     * @return bool
     */
    public function updateToken($token, $cvc)
    {
        $clientKey = $this->getClientKey();

        $data = [
            'cvc' => $cvc,
            'clientKey' => $clientKey
        ];

        $response = $this->request('PUT', sprintf('tokens/%s', $token), $data);

        return ($response['reasonPhrase'] === 'OK');
    }

    /**
     * Delete Token
     *
     * This method allows you to delete a token.
     *
     * @param string $id Valid Token UUID.
     *
     * @return bool
     */
    public function deleteToken($token)
    {
        $response = $this->request('DELETE', sprintf('tokens/%s', $token));

        return ($response['reasonPhrase'] === 'OK');
    }

    /**
     * Creating an Order
     *
     * Creating a new order is done through a POST on the
     * Order API. A new order can only be created using a token,
     * which the Worldpay.js library added to your checkout
     * form. This token represents the customer's card details
     * or chosen payment method which Worldpay.js stored on
     * our server.
     *
     * The following fields are optional but recommended to
     * improve risk checking:
     * - name
     * - billingAddress
     * - deliveryAddress
     * - shopperEmailAddress
     * - shopperIpAddress
     * - shopperSessionID
     *
     * Other optional fields include:
     * - customerOrderCode: code under which this order is known
     *                    in your systems
     * - settlementCurrency: the currency you want to be paid in
     *                     (which has to be enabled in your currency settings
     *
     * @param Order $order Worldpay Order Request
     *
     * @return Order Worldpay Order Response
     */
    public function createOrder(Order $order)
    {
        $data = $order->getParameters();

        $response = $this->request('POST', 'orders', $data);

        return Order::parse($response);
    }

    /**
     * Getting order details
     *
     * Order details can be obtained by sending a get request.
     *
     * @param string $orderCode Worldpay Order Code UUID.
     *
     * @return Order
     */
    public function getOrder($orderCode)
    {
        $response = $this->request('GET', sprintf('orders/%s', $orderCode));

        return Order::parse($response);
    }

    /**
     * Getting Order Details
     *
     * Details of a set of orders can be obtained by sending a GET request.
     *
     * @param array $params Search query parameters:
     *                      - environment:    String, Mandatory
     *                            "TEST" or "LIVE" depending on whether
     *                             you want to retrieve Test or Live orders.
     *                      - fromDate:   String, Mandatory
     *                            Start of the date range you want to
     *                            retrieve orders for as yyyy-mm-dd,
     *                            e.g. "2015-03-27"
     *                      - toDate: String, Mandatory
     *                            End of the date range you want to
     *                            retrieve orders for as yyyy-mm-dd,
     *                            e.g. "2015-04-27"
     *                      - paymentStatus:  String, Optional
     *                            State of the orders you want to retrieve.
     *                            For a full last of paymentStatuses see
     *                            the Orders Introduction section.
     *                      - sortDirection:  String, Optional
     *                            "asc" or "desc" depending on whether you
     *                            want the list of orders to be sorted in
     *                            ascending or descending order. Default
     *                            is descending.
     *                      - sortProperty:   String, Optional
     *                            "creationDate" or "modificationDate".
     *                            Default is modificationDate which is
     *                            the date at which the state of this
     *                            order was last modified.
     *                      - pageNumber: String, Optional
     *                            Get Orders will only return 20 orders
     *                            per page. This field allows you to
     *                            specify which page you want to retrieve.
     *                            Note: the first page is page 0.
     *                      - csv:    String, Optional
     *                            true or false depending on whether you
     *                            want to receive the results as a Comma
     *                            Separated Values (CSV) file or not.
     *                            The default is false. CSV files can be
     *                            used to import data into Microsoft Excel.
     *
     *
     * @return array|Response
     */
    public function getOrders(array $params = [])
    {
        $data = [];
        $keys = ['environment', 'fromDate', 'toDate', 'paymentStatus', 'sortDirection', 'sortProperty', 'pageNumber', 'csv'];

        foreach ($keys as $key) {
            if (isset($params[$key])) {
                $data[$key] = $params[$key];
            }
        }

        $is_attachment = (isset($data['csv']) && $data['csv'] === 'true');

        $response = $this->request('GET', sprintf('orders?%s', http_build_query($data)));

        return ($response['reasonPhrase'] === 'OK' ? $response['result'] : []);
    }

    /**
     * Capture Authorized Worldpay Order
     *
     * Authorized (authorizeOnly) orders can be captured by sending
     * a capture request. You can capture a partial amount by
     * including an optional capture amount.
     *
     * Note that an order can only be captured once; any remainder
     * left after a partial capture can not be captured later.
     *
     * @param string       $orderCode Worldpay Order Code UUID.
     * @param integer|null $amount    Partial amount to capture.
     *
     * @return Order
     */
    public function captureAuthorizedOrder($orderCode, $amount = null)
    {
        $data = [];

        if ( ! empty($amount)) {
            Order:: parseAmount($amount);
            $data['captureAmount'] = $amount;
        }

        $response = $this->request('POST', sprintf('orders/%s/capture', $orderCode), $data);

        return Order::parse($response);
    }

    /**
     * Cancel Authorized Worldpay Order
     *
     * Authorized (authorizeOnly) orders can be cancelled by
     * sending a DELETE request.
     *
     * @param string $orderCode Worldpay Order Code UUID.
     *
     * @return bool True on HTTP 200 (success), otherwise False for failure.
     */
    public function cancelAuthorizedOrder($orderCode)
    {
        $response = $this->request('DELETE', sprintf('orders/%s', $orderCode));

        return ($response['reasonPhrase'] === 'OK');
    }

    /**
     * Refund Worldpay Order
     *
     * Refunding an order is done through a POST on the Order
     * Refund API URL, without any request body. An order can
     * only be refunded if its current state is SUCCESS or SETTLED,
     * or PARTIALLY_REFUNDED.
     *
     * Refund response structure is same as above, Order Creation
     * Response, where paymentStatus field shall reflect the latest
     * order status as "REFUNDED".
     *
     * @param string       $orderCode Worldpay Order Code UUID.
     * @param integer|null $amount    Partial amount to capture.
     *
     * @return bool True on HTTP 200 (success), otherwise False for failure.
     */
    public function refundOrder($orderCode, $amount = null)
    {
        $data = [];

        if ( ! empty($amount)) {
            Order:: parseAmount($amount);
            $data['refundAmount'] = $amount;
        }

        $response = $this->request('POST', sprintf('orders/%s/refund', $orderCode), $data);

        return ($response['reasonPhrase'] === 'OK');
    }

    /**
     * Authorise Worldpay 3DS Order
     *
     * Send the 3DS response back to Worldpay and get final
     * order confirmation.
     *
     * The order will be updated with the 3DS response once you
     * have sent Worldpay a second order request. This must include
     * the 3DS response you received from the issuer/shopper's bank.
     *
     * @param string  $orderCode    Worldpay Order Code UUID.
     * @param string  $responseCode Value of the PARes sent to TermURL.
     * @param Shopper $shopper      Information about the shopper.
     *
     * @return Order
     */
    public function authorise3DSOrder($orderCode, $responseCode, Shopper $shopper)
    {
        $data = $shopper->getParameters();
        $data['threeDSResponseCode'] = $responseCode;

        $response = $this->request('PUT', sprintf('orders/%s', $orderCode), $data);

        return Order::parse($response);
    }

    /**
     * Upload Evidence for Disputed Order
     *
     * Documented evidence can be supplied for disputed orders,
     * and is done through a POST on the Order Dispute API URL,
     * with the filename and base64 encoded file in the body.
     *
     * Documents can only be uploaded if the current order
     * state is INFORMATION_REQUESTED or INFORMATION_SUPPLIED.
     *
     * @param string   $orderCode Worldpay Order Code UUID.
     * @param Evidence $evidence  Value of the PARes sent to TermURL.
     *
     * @return Order
     */
    public function defendDisputedOrder($orderCode, Evidence $evidence)
    {
        $data = $evidence->getParameters();

        $response = $this->request('POST', sprintf('orders/%s/disputes', $orderCode), $data);

        var_dump($response); exit;
    }
}

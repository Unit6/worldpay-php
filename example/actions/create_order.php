<?php
/**
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require '../config.php';

use Unit6\Worldpay\Address;
use Unit6\Worldpay\Currency;
use Unit6\Worldpay\GatewayException;
use Unit6\Worldpay\Order;
use Unit6\Worldpay\Shopper;
use Unit6\Worldpay\Token;
use Unit6\Worldpay\Card;

// Coherce to boolean.
$reusable = (isset($input['reusable']) ? ! empty($input['reusable']) : null);
$is3DSecure = (isset($input['is3DSOrder']) ? ! empty($input['is3DSOrder']) : null);
$isAuthorizeOnly = (isset($input['authorizeOnly']) ? ! empty($input['authorizeOnly']) : null);

$customerReference = uniqid(); #$input['customerOrderCode'];
$payeeName = $input['paymentMethod']['name'];

// If token provided, parse the token.
if (isset($input['token'])) {
    #$token = $client->getToken($input['token']);
    $token = new Token($input['token'], $reusable);
} elseif ( ! isset($input['token']) && isset($input['paymentMethod'])) {
    // If there is no token and there are paymentMethod details
    // exchange them with Worldpay for a token.
    $paymentMethod = new Card($input['paymentMethod']);
    $token = $client->createToken($paymentMethod);
}
// Remove payment details and use token instead.
unset($input['paymentMethod']);

if ( ! ($token instanceof Token)) {
    throw new UnexpectedValueException('Expected valid token');
}

// When using an order request, make sure you include the
// additional 3DS fields ('is3DSOrder', 'Shopper*') and for
// test orders set the shopper name to the special value '3D'.
if ($is3DSecure) {
    $payeeName = '3D';
}

$currency = new Currency($input['currencyCode']);
$settlementCurrency = new Currency($input['settlementCurrency']);
$billingAddress = new Address($input['billingAddress']);
$deliveryAddress = new Address($input['deliveryAddress']);

$shopper = new Shopper('name@domain.co.uk', uniqid());
$_SESSION['shopperSessionID'] = $shopper->getSessionID();

// The amount to be charged in the smallest unit
// is of the currencyCode that you specified.
$amount = ($input['amount'] * 100);

// Fix the customerIdentifiers being sent as JSON string.
$customerIdentifiers = (isset($input['customerIdentifiers']) ? json_decode($input['customerIdentifiers'], $assoc = true) : []);

$order = (new Order($input['orderType'], $is3DSecure))
    ->withToken($token)
    ->withAuthorizeOnly($isAuthorizeOnly)
    ->withDescription($input['orderDescription'])
    ->withAmount($amount)
    ->withCurrency($currency)
    ->withSettlementCurrency($settlementCurrency)
    ->withBillingAddress($billingAddress)
    ->withDeliveryAddress($deliveryAddress)
    ->withPayeeName($payeeName)
    ->withCustomerReference($customerReference)
    ->withCustomerIdentifiers($customerIdentifiers)
    ->withShopper($shopper);

#var_dump($order->getParameters(), __FILE__, 'BEFORE'); exit;

try {
    $order = $client->createOrder($order);
} catch (GatewayException $e) {
    #var_dump($e->getMessage(), $e->getDescription()); exit;
    echo 'Error Code: ' . $e->getCustomCode() . '<br/>' .
         'Error Description: ' . $e->getDescription()  . ' <br/>' .
         'Error Message: ' . $e->getMessage() . '<br/>' .
         'HTTP Status Code: ' . $e->getStatusCode() . '<br/>';
    exit;
}

$url = $rootURL . '/?page=create_order&code=' . $order->getCode();

header('Location: ' . $url);
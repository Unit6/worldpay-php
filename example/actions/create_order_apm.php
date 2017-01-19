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

$links = [
    'cancel'  => $rootURL . '/apm/cancel.php',
    'failure' => $rootURL . '/apm/failure.php',
    'pending' => $rootURL . '/apm/pending.php',
    'success' => $rootURL . '/apm/success.php'
];

$payeeName = $input['name'];
$customerReference = uniqid();

// Coherce to boolean.
$reusable = (isset($input['reusable']) ? ! empty($input['reusable']) : null);
$is3DSecure = (isset($input['is3DSOrder']) ? ! empty($input['is3DSOrder']) : null);
$isAuthorizeOnly = (isset($input['authorizeOnly']) ? ! empty($input['authorizeOnly']) : null);

$token = new Token($input['token'], $reusable);

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

// The amount to be charged in the smallest unit
// is of the currencyCode that you specified.
$amount = ($input['amount'] * 100);

// Fix the customerIdentifiers being sent as JSON string.
$customerIdentifiers = json_decode($input['customerIdentifiers'], $assoc = true);

$order = (new Order('ECOM', $is3DSecure))
    ->withToken($token)
    #->withAuthorizeOnly($isAuthorizeOnly)
    ->withDescription($input['orderDescription'])
    ->withAmount($amount)
    ->withCurrency($currency)
    ->withSettlementCurrency($settlementCurrency)
    ->withBillingAddress($billingAddress)
    ->withDeliveryAddress($deliveryAddress)
    ->withPayeeName($payeeName)
    ->withCustomerReference($customerReference)
    ->withCustomerIdentifiers($customerIdentifiers)
    ->withCallbackURLs($links);

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

#var_dump($order->getParameters(), __FILE__, 'AFTER'); exit;

$url = $rootURL . '/?page=create_order_apm&code=' . $order->getCode();

header('Location: ' . $url);

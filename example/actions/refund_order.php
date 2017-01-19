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

use Unit6\Worldpay\GatewayException;

$type = $input['type'];

if ( ! in_array($type, ['Full', 'Partial'])) {
    throw new InvalidArgumentException('Refund order action type invalid.');
}

$orderCode = $input['orderCode'];

// The amount to be charged in the smallest unit
// is of the currencyCode that you specified.
$amount = ($type === 'Partial' ? ($input['amount'] * 100) : null);

$url = $rootURL . '/?page=refund_order&type=' . $type . '&code=' . $orderCode;

try {
    // Refund the order using the Worldpay order code
    $order = $client->refundOrder($orderCode, $amount);
} catch (GatewayException $e) {
    #var_dump($e->getMessage(), $e->getDescription()); exit;
    echo 'Error Code: ' . $e->getCustomCode() . '<br/>' .
         'Error Description: ' . $e->getDescription()  . ' <br/>' .
         'Error Message: ' . $e->getMessage() . '<br/>' .
         'HTTP Status Code: ' . $e->getStatusCode() . '<br/>';
    exit;
}

header('Location: ' . $url);

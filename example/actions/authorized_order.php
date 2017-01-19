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

if ( ! in_array($type, ['Capture', 'Cancel'])) {
    throw new UnexpectedValueException('Authorized order action type invalid');
}

$orderCode = $input['orderCode'];

$url = $rootURL . '/?page=authorized_order&type=' . $type . '&code=' . $orderCode;

try {
    if ($type === 'Capture') {
        // The amount to be charged in the smallest unit
        // is of the currencyCode that you specified.
        $amount = ($input['amount'] * 100);

        // Capture the authorized order using the Worldpay order code
        $order = $client->captureAuthorizedOrder($orderCode, $amount);

    } elseif ($type === 'Cancel') {
        // Cancel the authorized order using the Worldpay order code
        $outcome = $client->cancelAuthorizedOrder($orderCode);

        $url .= '&outcome=' . ($outcome ? 'Success' : 'Failure');
    }
} catch (GatewayException $e) {
    #var_dump($e->getMessage(), $e->getDescription()); exit;
    echo 'Error Code: ' . $e->getCustomCode() . '<br/>' .
         'Error Description: ' . $e->getDescription()  . ' <br/>' .
         'Error Message: ' . $e->getMessage() . '<br/>' .
         'HTTP Status Code: ' . $e->getStatusCode() . '<br/>';
    exit;
}

header('Location: ' . $url);

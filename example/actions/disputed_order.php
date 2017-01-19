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

use Unit6\Worldpay\Evidence;
use Unit6\Worldpay\GatewayException;

if ( ! isset($input['orderCode']) || empty($input['orderCode'])) {
    throw new UnexpectedValueException('Worldpay order code required');
}

$orderCode = $input['orderCode'];

$url = $rootURL . '/?page=disputed_order&code=' . $orderCode;

$files = $request->getUploadedFiles();

if ( ! isset($files['evidence'])) {
    throw new UnexpectedValueException('Dispute evidence required');
}

try {
    $evidence = (new Evidence())->withFile($files['evidence']);
} catch (UnexpectedValueException $e) {
    var_dump($e->getMessage()); exit;
}

try {
    $result = $client->defendDisputedOrder($orderCode, $evidence);
} catch (GatewayException $e) {
    #var_dump($e->getMessage(), $e->getDescription()); exit;
    echo 'Error Code: ' . $e->getCustomCode() . '<br/>' .
         'Error Description: ' . $e->getDescription()  . ' <br/>' .
         'Error Message: ' . $e->getMessage() . '<br/>' .
         'HTTP Status Code: ' . $e->getStatusCode() . '<br/>';
    exit;
}

header('Location: ' . $url);
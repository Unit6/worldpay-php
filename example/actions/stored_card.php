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
$tokenID = $input['token'];
$cvc = (isset($input['cvc']) ? $input['cvc'] : null);

if ( ! in_array($type, ['getToken', 'updateToken', 'deleteToken'])) {
    throw new UnexpectedValueException('Token action type invalid');
}

if (empty($tokenID)) {
    throw new InvalidArgumentException('Token ID cannot be empty');
}

$url = $rootURL . '/?page=stored_card&token=' . $tokenID . '&type=' . $type;

try {
    $token = call_user_func([$client, $type], $tokenID, $cvc);

} catch (GatewayException $e) {
    #var_dump($e->getMessage(), $e->getDescription()); exit;
    echo 'Error Code: ' . $e->getCustomCode() . '<br/>' .
         'Error Description: ' . $e->getDescription()  . ' <br/>' .
         'Error Message: ' . $e->getMessage() . '<br/>' .
         'HTTP Status Code: ' . $e->getStatusCode() . '<br/>';
    exit;
}

#var_dump($token->getParameters(), __FILE__, 'AFTER'); exit;

header('Location: ' . $url);

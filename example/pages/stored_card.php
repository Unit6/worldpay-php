<?php
/**
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Unit6\Worldpay\GatewayException;

if ( ! isset($_GET['token'])) {
    require 'content/form_stored_card.php';
} else {

    $type = $_GET['type'];
    $tokenID = $_GET['token'];
    $reusable = 'n/a';
    $paymentMethod = 'n/a';

    try {
        $token = $client->getToken($tokenID);

        $tokenID = $token->getID();
        $paymentMethod = print_r($token->getPaymentMethod()->getParameters(), true);
        $reusable = ($token->isReusable() ? 'Yes' : 'No');

    } catch (GatewayException $e) {
        #var_dump($e->getMessage(), $e->getDescription()); exit;
        if ($e->getCustomCode() !== GatewayException::TKN_NOT_FOUND) {
            echo 'Error Code: ' . $e->getCustomCode() . '<br/>' .
                 'Error Description: ' . $e->getDescription()  . ' <br/>' .
                 'Error Message: ' . $e->getMessage() . '<br/>' .
                 'HTTP Status Code: ' . $e->getStatusCode() . '<br/>';
            exit;
        }
    }

    echo '<h1 class="page-header">Stored Card: ' . $type . '</h1>';
    echo '<p>Token ID: ' . $tokenID . '</p>';
    echo '<p>Reuseable: ' . $reusable . '</p>';
    echo '<pre>' . $paymentMethod . '</pre>';
}
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
use Unit6\Worldpay\Shopper;

$responseCode = $_POST['PaRes'];
$customerReference = $_POST['MD'];

/**
 * 3-D Secure Simulation Codes
 *
 * The following is a list of test codes from the Worldpay 3-D Secure simulation.
 *
 * These return the order as either SUCCESS or FAILED.
 *  - IDENTIFIED:
 *      Cardholder authenticated.
 *  - NOT_IDENTIFIED:
 *      Authentication offered but not used.
 *  - CANCELLED_BY_SHOPPER:
 *      Payment cancelled.
 *      The order does not proceed to authorisation.
 *  - UNKNOWN_IDENTITY:
 *      Cardholder failed authentication.
 *      The order does not proceed to authorisation.
 *
 * These simulation codes throws the below ResponseException:
 *    CustomCode: INVALID_PAYMENT_DETAILS
 *    Message: EXT_67: Verification of threeDSResponseCode failed
 *
 *  - 3DS_INVALID_ERROR_CODE:
 *      The error code is invalid.
 *      The order does not proceed to authorisation.
 *  - 3DS_VALID_ERROR_CODE:
 *      The error code is valid.
 *      The order does not proceed to authorization.
 *  - ERROR:
 *      Response validation failed.
 *      The order does not proceed to authorisation.
 */

$orderCode = $_SESSION['orderCode'];
$shopperSessionID = $_SESSION['shopperSessionID'];

$shopper = new Shopper('name@domain.co.uk', $shopperSessionID);

$errorCode = '0';

try {
    $order = $client->authorise3DSOrder($orderCode, $responseCode, $shopper);
    #$orderCode = $order->getCode();
} catch (GatewayException $e) {
    $errorCode = $e->getCustomCode();
    if ($errorCode !== GatewayException::INVALID_PAYMENT_DETAILS) {
        echo 'Error Code: ' . $e->getCustomCode() . '<br/>' .
             'Error Description: ' . $e->getDescription()  . ' <br/>' .
             'Error Message: ' . $e->getMessage() . '<br/>' .
             'HTTP Status Code: ' . $e->getStatusCode() . '<br/>';
        exit;
    }
}

$url = $rootURL . '/?page=create_order&code=' . $orderCode . '&threeds=1&error=' . $errorCode;

// Assuming we're still in the Iframe, we can redirect the parent.
echo '<script>window.top.location.href = "' . $url . '"</script>';
#header('Location: ' . $url);
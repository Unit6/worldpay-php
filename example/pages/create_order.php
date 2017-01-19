<?php
/**
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Unit6\Worldpay\PaymentStatus;

if ( ! isset($_GET['code'])) {
    require 'content/form_order.php';
} else {
    $orderCode = $_GET['code'];
    $order = $client->getOrder($orderCode);

    if ($order === null) {
        echo 'Failed to retrieve order: ' . $orderCode;
        exit;
    }

    $orderCode = $order->getCode();
    $paymentStatus = $order->getPaymentStatus();
    $paymentResponse = print_r($order->getPaymentResponse(), true);

    // Check if order was successful.
    if (in_array($paymentStatus, [PaymentStatus::SUCCESS, PaymentStatus::AUTHORIZED])) {
        // TODO: Store the order code somewhere ...
        echo '<h1 class="page-header">Create Order Outcome</h1>';
        echo '<p>Order Code: ' . $orderCode . '</p>';
        echo '<p>Token: ' . $order->getTokenID() . '</p>';
        echo '<p>Amount: ' . $order->getAmount() . '</p>';
        echo '<p>Authorized Amount: ' . $order->getAuthorizedAmount() . '</p>';
        echo '<p>Payee: ' . $order->getPayeeName() . ' (Ref: ' . $order->getCustomerReference() . ')</p>';
        echo '<p>Payment Status: ' . $paymentStatus . '</p>';
        echo '<pre>' . $paymentResponse . '</pre>';

    } elseif ($paymentStatus === PaymentStatus::PRE_AUTHORIZED && $order->is3DSecure()) {
        // $paymentStatus === 'PRE_AUTHORIZED';
        // $redirectURL !== null;
        // For 3-D Secure, use redirect to URL

        $_SESSION['orderCode'] = $orderCode;

        $_SESSION['3DS'] = [
            'IssuerUrl' => $order->getRedirectURL(),
            'TermUrl' => $rootURL . '/actions/term_url.php',
            'PaReq' => $order->get3DSecureToken(),
            'MD' => $order->getCustomerReference()
        ];

        $src = $rootURL . '/pages/iframe_3ds.php';

        echo '<h1 class="page-header">Confirm 3-D Secure</h1>';
        echo '<iframe src="' . $src .'" width="100%" height="700px" frameborder="0" marginheight="0" marginwidth="0" scrolling="auto"></iframe>';
    } else {
        // Something went wrong.
        echo '<p>Payment Status: ' . $paymentStatus . '</p>';
        echo '<pre>' . $paymentResponse . '</pre>';
    }
}
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
    require 'content/form_order_refund.php';
} else {
    $type = $_GET['type'];
    $orderCode = $_GET['code'];
    $order = $client->getOrder($orderCode);

    if ($order === null) {
        echo 'Failed to retrieve order: ' . $orderCode;
        exit;
    }

    $orderCode = $order->getCode();
    $paymentStatus = $order->getPaymentStatus();
    $paymentResponse = print_r($order->getPaymentResponse(), true);

    // Check if order refunded successfully.
    if (in_array($paymentStatus, [PaymentStatus::REFUNDED, PaymentStatus::PARTIALLY_REFUNDED])) {
        echo '<h1 class="page-header">Order Refunded</h1>';
        echo '<p>Order Code: ' . $orderCode . '</p>';
        echo '<p>Token: ' . $order->getTokenID() . '</p>';
        echo '<p>Amount: ' . $order->getAmount() . '</p>';
        echo '<p>Authorized Amount: ' . $order->getAuthorizedAmount() . '</p>';
        echo '<p>Payment Status: ' . $paymentStatus . '</p>';
        echo '<pre>' . $paymentResponse . '</pre>';

    } else {
        // Something went wrong.
        echo '<p>Payment Status: ' . $paymentStatus . '</p>';
        echo '<pre>' . $paymentResponse . '</pre>';
    }
}
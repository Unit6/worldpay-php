<?php
/**
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
?>
<h1 class="page-header">Get Order Detail</h1>

<form method="post" action="actions/get_order.php">

    <h2>Order Information</h2>

    <div class="form-group">
        <label for="order-code">Worldpay Order Code</label>
        <input type="text" class="form-control" id="order-code"
            name="orderCode"
            value="" />
    </div>

    <button type="submit" class="btn btn-primary">Get Order</button>
</form>

<?php
    if (isset($_GET['code'])) {
        $order = $client->getOrder($_GET['code']);
        echo '<br /><pre>' . print_r($order, true) . '</pre>';
    }

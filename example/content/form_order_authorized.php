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
<h1 class="page-header">Authorized Order</h1>

<form id="authorized-order-form" method="post" action="actions/authorized_order.php">

    <div class="form-group">
        <label for="type">Type</label>
        <select class="form-control" id="type" name="type">
            <option>Capture</option>
            <option>Cancel</option>
        </select>
    </div>

    <div class="form-group">
        <label for="order-code">Worldpay Order Code</label>
        <input type="text" class="form-control" id="order-code"
            name="orderCode"
            value="" />
    </div>

    <div id="amount-row" class="row">
        <div class="form-group col-xs-4">
            <label for="amount">Amount</label>
            <input type="text" class="form-control" id="amount"
                name="amount"
                value="0.00" />
        </div>
    </div>

    <div id="authorized-order-outcome" class="row"></div>

    <button type="submit" class="btn btn-primary" id="authorized-order-submit">Submit</button>
</form>

<script>
    $(document).ready(function () {
        $('#type').on('change', function (e) {
            var value = $(this).val();
            if (value === 'Cancel') {
                $('#amount-row').hide();
            } else {
                $('#amount-row').show();
            }
        }).trigger('change');
    });
</script>
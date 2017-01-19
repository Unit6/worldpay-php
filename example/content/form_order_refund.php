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
<h1 class="page-header">Refund Order</h1>

<form id="refund-form" method="post" action="actions/refund_order.php">
    <div class="form-group">
        <label for="type">Type</label>
        <select class="form-control" id="type" name="type">
            <option>Full</option>
            <option>Partial</option>
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

    <div id="refund-outcome" class="row"></div>

    <button type="submit" class="btn btn-primary" id="refund-submit">Submit</button>
</form>

<script>
    $(document).ready(function () {
        $('#type').on('change', function (e) {
            var value = $(this).val();
            if (value === 'Partial') {
                $('#amount-row').show();
            } else {
                $('#amount-row').hide();
            }
        }).trigger('change');
    });
</script>
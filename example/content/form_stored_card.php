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
<h1 class="page-header">Stored Card</h1>

<form id="stored-card-form" method="post" action="actions/stored_card.php">

    <h2>Token</h2>

    <div class="form-group">
        <label for="type">Method</label>
        <select class="form-control" id="type" name="type">
            <option value="getToken">Get Card Details</option>
            <option value="updateToken">Refresh Token with CVC</option>
            <option value="deleteToken">Delete Token</option>
        </select>
    </div>

    <div class="row">
        <div class="form-group col-xs-10">
            <label for="token">Worldpay Resuable Token</label>
            <input type="tel" class="form-control" id="token"
                name="token"
                value="" />
        </div>
        <div id="cvc-row" class="form-group col-xs-2">
            <label for="cvc">CVC</label>
            <input type="tel" class="form-control" id="cvc"
                name="cvc"
                size="4"
                value="321" />
        </div>
    </div>

    <div id="stored-card-outcome" class="row"></div>

    <button type="submit" class="btn btn-primary" id="stored-card-submit">Submit</button>
</form>

<script>
    $(document).ready(function () {
        $('#type').on('change', function (e) {
            var value = $(this).val();
            if (value === 'updateToken') {
                $('#cvc').removeAttr('disabled');
            } else {
                $('#cvc').attr('disabled', 'disabled');
            }
        }).trigger('change');
    });
</script>
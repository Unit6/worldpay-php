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
<h1 class="page-header">Create Order Example</h1>

<form id="payment-form" method="post" action="actions/create_order.php">

    <h2>Card Details</h2>

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name"
            name="paymentMethod[name]"
            data-worldpay="name"
            value="John Smith" />
    </div>

    <div class="row">
        <div class="form-group col-xs-10">
            <label for="card">Card Number</label>
            <input type="tel" class="form-control" id="card"
                name="paymentMethod[cardNumber]"
                size="20"
                data-worldpay="number"
                value="4444333322221111" />
        </div>
        <div class="form-group col-xs-2">
            <label for="cvc">CVC</label>
            <input type="tel" class="form-control" id="cvc"
                name="paymentMethod[cvc]"
                size="4"
                data-worldpay="cvc"
                value="321" />
        </div>
    </div>

    <div class="row">
        <div class="form-group col-xs-4">
            <label for="expiration-month">Exp. Month</label>
            <select class="form-control" id="expiration-month"
                name="paymentMethod[expiryMonth]"
                data-worldpay="exp-month">
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
                <option value="05">05</option>
                <option value="06">06</option>
                <option value="07">07</option>
                <option value="08">08</option>
                <option value="09">09</option>
                <option value="10" selected="selected">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select>
        </div>
        <div class="form-group col-xs-4">
            <label for="expiration-year">Exp. Year</label>
            <select class="form-control" id="expiration-year"
                name="paymentMethod[expiryYear]"
                data-worldpay="exp-year">
                <option value="2015">2015</option>
                <option value="2016" selected="selected">2016</option>
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-xs-4">
            <label for="amount">Amount</label>
            <input type="text" class="form-control" id="amount"
                name="amount"
                value="15.23" />
        </div>
        <div class="form-group col-xs-4">
            <label for="currency">Currency</label>
             <select class="form-control" id="currency"
                name="currencyCode">
                <option value=""></option>
                <option value="USD">USD</option>
                <option value="GBP" selected>GBP</option>
                <option value="EUR">EUR</option>
                <option value="CAD">CAD</option>
                <option value="NOK">NOK</option>
                <option value="SEK">SEK</option>
                <option value="SGD">SGD</option>
                <option value="HKD">HKD</option>
                <option value="DKK">DKK</option>
            </select>
        </div>
        <div class="form-group col-xs-4">
            <label for="settlement-currency">Settlement Currency</label>
             <select class="form-control" id="settlement-currency"
                name="settlementCurrency">
                <option value=""></option>
                <option value="USD">USD</option>
                <option value="GBP">GBP</option>
                <option value="EUR" selected>EUR</option>
                <option value="CAD">CAD</option>
                <option value="NOK">NOK</option>
                <option value="SEK">SEK</option>
                <option value="SGD">SGD</option>
                <option value="HKD">HKD</option>
                <option value="DKK">DKK</option>
            </select>
        </div>
    </div>

    <h2>Billing Address</h2>

    <div class="row">
        <div class="form-group col-xs-4">
            <label for="billing-address-line-1">Address 1</label>
            <input type="text" class="form-control" id="billing-address-line-1"
                name="billingAddress[address1]"
                value="18 Linver Road" />
        </div>

        <div class="form-group col-xs-4">
            <label for="billing-address-line-2">Address 2</label>
            <input type="text" class="form-control" id="billing-address-line-2"
                name="billingAddress[address2]"
                value="Fulham" />
        </div>

        <div class="form-group col-xs-4">
            <label for="billing-address-line-3">Address 3</label>
            <input type="text" class="form-control" id="billing-address-line-3"
                name="billingAddress[address3]"
                value="" />
        </div>
    </div>

    <div class="row">
        <div class="form-group col-xs-4">
            <label for="billing-city">City</label>
            <input type="text" class="form-control" id="billing-city"
                name="billingAddress[city]"
                value="London" />
        </div>
        <div class="form-group col-xs-4">
            <label for="billing-postal-code">Postal Code</label>
            <input type="text" class="form-control" id="billing-postal-code"
                name="billingAddress[postalCode]"
                value="SW6 3RB" />
        </div>
        <div class="form-group col-xs-4">
            <label for="billing-country-code">Country Code</label>
            <input type="text" class="form-control" id="billing-country-code"
                name="billingAddress[countryCode]"
                value="GB" />
        </div>
    </div>

    <h2>Delivery Address</h2>

    <div class="row">
        <div class="form-group col-xs-4">
            <label for="delivery-address-line-1">Address 1</label>
            <input type="text" class="form-control" id="delivery-address-line-1"
                name="deliveryAddress[address1]"
                value="18 Linver Road" />
        </div>

        <div class="form-group col-xs-4">
            <label for="delivery-address-line-2">Address 2</label>
            <input type="text" class="form-control" id="delivery-address-line-2"
                name="deliveryAddress[address2]"
                value="Fulham" />
        </div>

        <div class="form-group col-xs-4">
            <label for="delivery-address-line-3">Address 3</label>
            <input type="text" class="form-control" id="delivery-address-line-3"
                name="deliveryAddress[address3]"
                value="" />
        </div>
    </div>

    <div class="row">
        <div class="form-group col-xs-4">
            <label for="delivery-city">City</label>
            <input type="text" class="form-control" id="delivery-city"
                name="deliveryAddress[city]"
                value="London" />
        </div>
        <div class="form-group col-xs-4">
            <label for="delivery-postal-code">Postal Code</label>
            <input type="text" class="form-control" id="delivery-postal-code"
                name="deliveryAddress[postalCode]"
                value="SW6 3RB" />
        </div>
        <div class="form-group col-xs-4">
            <label for="delivery-country-code">Country Code</label>
            <input type="text" class="form-control" id="delivery-country-code"
                name="deliveryAddress[countryCode]"
                value="GB" />
        </div>
    </div>

    <h2>Other</h2>

    <div class="row">
        <div class="form-group col-xs-4">
            <label for="order-type">Order Type</label>
            <select class="form-control" id="order-type"
                name="orderType">
                <option value="ECOM" selected>ECOM</option>
                <option value="MOTO">MOTO</option>
            </select>
        </div>

        <div class="form-group col-xs-8">
            <label for="order-description">Order Description</label>
            <input type="text" class="form-control" id="order-description"
                name="orderDescription"
                value="My test order" />
        </div>
    </div>

    <div class="form-group">
        <label for="statement-narrative">Statement Narrative</label>
        <input type="text" class="form-control" id="statement-narrative"
            maxlength="24"
            name="statement-narrative"
            value="Statement Narrative" />
    </div>

    <div class="form-group">
        <label for="customer-identifiers">Customer Identifiers (JSON)</label>
        <textarea class="form-control" id="customer-identifiers"
            rows="6" cols="30"
            name="customerIdentifiers">{"accountReference":"1234567","dateOfBirth":"01-01-1970","familyName":"Smith","postalCode":"EC4N 8AF"}</textarea>
    </div>

    <div class="checkbox">
        <label><input type="checkbox" id="reusable"
            name="reusable">Reusable Token</label>
    </div>

    <div class="checkbox">
        <label><input type="checkbox" id="is-3ds-order"
            name="is3DSOrder">Use 3-D Secure</label>
    </div>

    <div class="checkbox">
        <label><input type="checkbox" id="authorize-only"
            name="authorizeOnly">Authorize Only</label>
    </div>

    <div id="payment-outcome" class="row"></div>

    <button type="submit" class="btn btn-primary" id="payment-submit">Place Order</button>
</form>



<script type="text/javascript">
    var form = document.getElementById('payment-form');
    var errors = document.getElementById('payment-outcome');
    var clientKey = '<?php echo $client->getClientKey(); ?>';

    if ( ! window['Worldpay']) {
        //document.getElementById('payment-submit').disabled = true;
    }

    Worldpay.useOwnForm({
        clientKey: clientKey,
        form: form,
        reusable: false,
        callback: function (status, response) {
            errors.innerHTML = '';
            if (response.error) {
                Worldpay.handleError(form, errors, response.error);
            } else {
                //var tokenInput = $('#token');
                var token = response.token;

                //if (tokenInput.length) {
                //    token = tokenInput.val();
                //}

                Worldpay.formBuilder(form, 'input', 'hidden', 'token', token);
                form.submit();
            }
        }
    });

    $(document).ready(function () {
        $('#reusable').change(function () {
            Worldpay.reusable = $(this).is(':checked');
        });

        $('#reusable').prop('checked', false);
    });
</script>
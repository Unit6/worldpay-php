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
<h1 class="page-header">Create Order with APM</h1>

<form id="payment-form" method="post" action="actions/create_order_apm.php">

    <h2>Payment Details</h2>

    <div class="form-group">
        <label for="apm-name">APM</label>
         <select class="form-control" id="apm-name"
            name="apmName"
            data-worldpay="apm-name">
            <option value="paypal" selected="selected">PayPal</option>
            <option value="giropay">Giropay</option>
        </select>
    </div>

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name"
            name="name"
            value="John Smith" />
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
                data-worldpay="country-code"
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

    <div id="language-code-row" class="form-group">
        <label for="language-code">Language Code</label>
        <input type="text" class="form-control"
            id="language-code"
            maxlength="2"
            data-worldpay="language-code"
            value="EN" />
    </div>

    <div class="form-group">
        <label for="order-description">Order Description</label>
        <input type="text" class="form-control" id="order-description"
            name="orderDescription"
            value="My test order" />
    </div>

    <div id="swift-code-row" class="form-group" style="display: none;">
        <label for="swift-code">Swift Code</label>
        <input type="text" class="form-control"
            id="swift-code"
            name="swiftCode"
            value="NWBKGB21" />
    </div>

    <div class="form-group">
        <label for="customer-identifiers">Customer Identifiers (JSON)</label>
        <textarea class="form-control" id="customer-identifiers"
            rows="6" cols="30"
            name="customerIdentifiers">{"accountReference":"1234567","dateOfBirth":"01-01-1970","familyName":"Smith","postalCode":"EC4N 8AF"}</textarea>
    </div>

    <div id="reusable-row" class="checkbox">
        <label><input type="checkbox" id="reusable"
            name="reusable">Reusable Token</label>
    </div>

    <div class="checkbox">
        <label><input type="checkbox" id="is-3ds-order"
            name="is3DSOrder">Use 3-D Secure</label>
    </div>

    <div class="checkbox">
        <label><input type="checkbox" id="authorise-only"
            name="authoriseOnly">Authorise Only</label>
    </div>

    <div id="payment-outcome" class="row"></div>

    <button type="submit" class="btn btn-primary" id="payment-submit">Place Order</button>
</form>

<script type="text/javascript">
    var form = document.getElementById('payment-form');
    var errors = document.getElementById('payment-outcome');
    var clientKey = '<?php echo $client->getClientKey(); ?>';

    $(document).ready(function () {

        var apmName = $('#apm-name');
        var reusable = $('#reusable');
        var reusableRow = $('#reusable-row');
        var currency = $('#currency');

        // Set client key
        Worldpay.tokenType = 'apm';
        Worldpay.setClientKey(clientKey);

        // Get form element
        Worldpay.useForm(form, function (status, response) {
            errors.innerHTML = '';

            console.log(response.error);

            if (response.error) {
                Worldpay.handleError(form, errors, response.error);
            } else {
                var token = response.token;

                Worldpay.formBuilder(form, 'input', 'hidden', 'token', token);
                Worldpay.formBuilder(form, 'input', 'hidden', 'apmName', apmName.val());
                form.submit();
            }
        });

        reusable.prop('checked', false);
        reusable.change(function () {
            if ($(this).is(':checked') && $('#apm-name').val() !== 'giropay') {
                Worldpay.reusable = true;
            }
            else {
                Worldpay.reusable = false;
            }
        });

        var languageCode = $('#language-code');
        var languageCodeRow = $('#language-code-row');
        var swiftCode = $('#swift-code');
        var swiftCodeRow = $('#swift-code-row');

        apmName.on('change', function () {
            if ($(this).val() === 'giropay') {
                Worldpay.reusable = false;
                swiftCode.attr('data-worldpay-apm','swiftCode');
                swiftCodeRow.show();

                // No language code for Giropay
                languageCode.removeAttr('data-worldpay');
                languageCodeRow.hide();

                // Reusable token option is not available for Giropay
                reusableRow.hide();

                // Set acceptance currency to EUR
                currency.val('EUR');
            } else {
                // We don't want to send swift code to the API
                // if the APM is not Giropay.
                swiftCode.removeAttr('data-worldpay-apm');
                swiftCodeRow.hide();
                reusableRow.show();

                // Language code enabled by default.
                languageCode.attr('data-worldpay','language-code');
                languageCodeRow.show();

                currency.val('GBP');
            }
        });
    });
</script>
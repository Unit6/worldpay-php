# unit6/worldpay

Tools to process payments using the Worldpay REST API (v1).


## Features

A list of supported API functionality.

- Tokens:
	- Creating a token
	- Getting token details
	- Providing CVC for reusable token
	- Deleting a token

- Orders:
	- Creating a standard order
	- Creating a 3D Secure order
	- Authorizing an order
	- Capturing an order
	- Cancelling an order
	- Refunding an order
	- Partially refunding an order
	- Defending a disputed order *[UNTESTED]*
	- Get order details
	- Get details of list of orders
	- Creating a Financial Services-related

## TODO

API features, yet to be implemented. Some are not officially documented at present.

- Webhooks:
	- Processing incoming webhook
	- Create a webhook
	- Get list of webhooks
	- Update a webhook
	- Delete a webhook
	- Trigger a webhook event
- Merchant:
	- Get merchant settings
	- Update risk settings for CVC/AVS
	- Enable/disable recurring billing
	- Create merchant
	- Update merchant
	- Activate merchant
- Transfers:
	- Getting transfers list
	- Getting transfer details


## Documentation

For information on how to use this library, see the examples below. If you need information on the Worldpay REST API, you can find information on the online [API Reference](https://online.worldpay.com/api-reference).

## Requirements

 - PHP 5.6
 - cURL 7.43
 - JSON

## Examples

While not required, [ngrok](https://ngrok.com) or some other local tunneling proxy service will be especially helpful in running the examples, especially for APM orders.

First install ngrok. On a Mac using [Homebrew](http://brew.sh/), this is just a case of running:

**(NOTE: I'm using v1.7 as v2.0 source code hasn't been released, as yet)**

```
$ brew install ngrok
```

Once ngrok is installed, you'll need to start the built-in PHP server in one terminal window, taking care to bind to `127.0.0.1` as this the default for ngrok. Pick an available port number.

```
$ php -S 127.0.0.1:8000 -t example/
```

Then run ngrok in a separate terminal specifing the same port number to forward incoming requests to. This will generate a public URL similar to: `https://abcd1234.ngrok.com`

```
$ ngrok 8000
```

## Gotchas

Some of these are because the documentation isn't 100% clear.

| Topic | Description |
| ---: | :-- |
| **APM** | At lest in the tests, the callback URLs (`successUrl`, `cancelUrl`, `pendingUrl`, `failureUrl`) simply append the Order ID to whatever the URL is that you provide `?orderCode=<UUID>`. It does not detect whether or not a query string is already present. |
| **APM** | The `redirectURL` will give you the APM providers (e.g. PayPal) gateway URL for you to redirect users to authenticate. I originally assumed it was only used for 3-D Secure. |
| **APM** | Callback URLs must be publicly available otherwise they will be rejected by the API as invalid URLs. I wasn't able to use URLs pointing at my localhost with `mysite.dev`, for example. *Use ngrok to get around this in the examples.* |
| **3-D Secure** | The documentation has misleading information on the case of some fields, namely `oneTime3DSToken` is actually returned from the API as `oneTime3DsToken` which has a **lower-case** letter `s` in the middle. |
| **3-D Secure** | By means of terminology clarification, the `oneTime3DSToken` is a `PAReq` while `threeDSResponseCode` is a `PARes` and the `redirectURL` will give you an an Issuer or **ACS URL**. |
| **3-D Secure** | You can either redirect the browser by using HTTP `POST` to the Issuer URL for 3-D Secure authentication or you can embed an HTML `<form>` in an Iframe which submits with `POST` the `PAReq`, `TermUrl` and optional `MD` (Merchant Data) to the `IssuerUrl` within the `<form>` on page load. |
| **Disputed Orders** | At present it is impossible to test the disputes functionality because they can only be performed on orders where the current state is `INFORMATION_REQUESTED` or `INFORMATION_SUPPLIED`. There is no way to manipulate the state of an order within the existing test environment. |
| **Order Search** | Timestamps for `creationDate` and `modificationDate` are returned in milliseconds. |
| **Order Search** | Query parameter `sortProperty` options are incorrectly documented. It should be one of: `CREATE_DATE`, `USERNAME`, `PRICE_CODE`, `MODIFICATION_DATE`, `PARTNER_COMPANY_NAME`, `ONBOARDING_PARTNER_COMPANY_NAME`, `ADMIN_CODE`, `MERCHANT_NAME`, `CONTACT_EMAIL`, `PARTNER_NAME`, `ONBOARDING_STATUS`, `ONBOARDING_MESSAGE`, `TIME_ELAPSED`. |

### License

This project is licensed under the MIT license -- see the `LICENSE.txt` for the full license details.

### Acknowledgements

Some inspiration has been taken from the following projects:

- [Worldpay/worldpay-lib-php](https://github.com/Worldpay/worldpay-lib-php)
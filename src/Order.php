<?php
/**
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Worldpay;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Order Class
 *
 * Create an order resource.
 */
class Order extends AbstractResource
{
    /**
     * Required parameters
     *
     * @var array
     */
    protected static $required = [
        'amount',
        'billingAddress',
        'currencyCode',
        'name',
        'orderDescription',
        'token'
    ];

    /**
     * Token UUID
     *
     * A unique token which the Worldpay.js library added
     * to your checkout form. This token represents the
     * customer's card details which Worldpay.js stored on our server
     *
     * @var Token
     */
    protected $token;

    /**
     * Order Description (orderDescription)
     *
     * The description of the order provided by you
     *
     * @var string
     */
    protected $description;

    /**
     * Order Amount
     *
     * The amount to be charged in pennies/cents (or whatever the
     * smallest unit is of the currencyCode that you specified).
     *
     * @var int
     */
    protected $amount;

    /**
     * Order Amount [response only]
     *
     * The amount that has been authorized for this order in cents
     *
     * @var int
     */
    protected $authorizedAmount;

    /**
     * Order Currency (currencyCode)
     *
     * The ISO currency code of the currency you want to charge
     * your customer in.
     *
     * @var Currency
     */
    protected $currency;

    /**
     * Order Currency Exponent [response only]
     *
     * The number of decimals after the dot for this currency.
     * This is important to know as 'amount' has to be specified
     * in the smallest unit of the currencyCode. The value will
     * be 2 for most currencies but can sometimes be 3.
     *
     * @var Currency
     */
    protected $currencyExponent;

    /**
     * Order Settlement Currency
     *
     * The ISO currency code of the currency you want to be
     * settled in in. This field is mandatory only if you have
     * enabled multiple settlement currencies in currency
     * settings (this setting is only available when you have
     * activated your account).
     *
     * @var Currency
     */
    protected $settlementCurrency;

    /**
     * Order Settlement Currency Exponent [response only]
     *
     * The number of decimals after the dot for this currency.
     * This is important to know as 'amount' has to be specified
     * in the smallest unit of the currencyCode. The value will
     * be 2 for most currencies but can sometimes be 3.
     *
     * @var Currency
     */
    protected $settlementCurrencyExponent;

    /**
     * Authorize Only (authorizeOnly)
     *
     * Indicates the order must only authorize the funds, and
     * not take payment. To take payment a Capture request must
     * be sent. Not all payment methods support this feature.
     *
     * @var bool
     */
    protected $authorizeOnly = null;

    /**
     * Use 3-D Secure (is3DSOrder)
     *
     * Indicates that a card order should use 3D Secure if available.
     * If this field is not specified 3D Secure will not be used.
     *
     * Sends a Verify Enrollment Request (VEReq)
     *
     * @var bool
     */
    protected $threeDomainSecure = null;

    /**
     * Order type (orderType)
     *
     * Indicates the type of order you will be placing.
     * See full list of order types in the right hand column.
     * If this field is not specified the default value is ECOM
     *
     * @var string
     */
    protected $type = 'ECOM';

    /**
     * Supported Order Types
     *
     * - ECOM: Default value for an order.
     * - MOTO: Mail Order Telephone Order / Virtual Terminal.
     * - RECURRING: Recurring order payments.
     *
     * @var array
     */
    public static $orderTypes = ['ECOM', 'MOTO', 'RECURRING'];

    /**
     * Order Code (orderCode) [response only]
     *
     * A Worldpay generated unique order code. This order code will
     * be referred to in all of our reports. A prefix can be
     * specified using the orderCodePrefix attribute.
     *
     * @var string
     */
    protected $code;

    /**
     * Order Code Prefix (orderCodePrefix)
     *
     * Allows the automatically generated orderCode to be prefixed
     * with a specified string. This prefix can be up to 20 characters
     * long, and may only include alphanumeric (a-z A-Z 0-9) characters
     * and - minus and _ underscore symbols.
     *
     * @var string
     */
    protected $codePrefix;

    /**
     * Order Code Prefix (orderCodeSuffix)
     *
     * Allows the automatically generated orderCode to be suffixed with
     * a specified string. This suffix can be up to 20 characters long,
     * and may only include alphanumeric (a-z A-Z 0-9) characters and -
     * minus and _ underscore symbols.
     *
     * @var string
     */
    protected $codeSuffix;

    /**
     * Customer Order Reference (customerOrderCode)
     *
     * The code or ID under which this order is known in your systems.
     *
     * @var string
     */
    protected $customerReference;

    /**
     * Payment Status [response only]
     *
     * The current status of the Order.
     *
     * @var string
     */
    protected $paymentStatus;

    /**
     * Payment Status Reason [response only]
     *
     * Payment Status Reason is an optional field available only
     * when paymentStatus is FAILED.
     *
     * @var string
     */
    protected $paymentStatusReason;

    /**
     * Payment Response [response only]
     *
     * Object containing all payment details.
     *
     * @var PaymentMethodInterface
     */
    protected $paymentResponse;

    /**
     * Customer Payee (name)
     *
     * Name of the payee/cardholder. In the request this is passed at
     * the top-level of the JSON. In the response it will be embedded
     * in paymentResponse (@see Order::setPayment)
     *
     * @var string
     */
    protected $payeeName;

    /**
     * Billing Address
     *
     * Object containing the address where the customer's card is
     * registered. This object will be used for AVS address
     * verification and other fraud checks, and is strongly
     * recommended to be set.
     *
     * @var Address
     */
    protected $billingAddress;

    /**
     * Delivery Address
     *
     * Object containing the address where the goods/services
     * are to be delivered/invoiced. This object will be used
     * for fraud checks, and is strongly recommended to be set.
     *
     * @var Address
     */
    protected $deliveryAddress;

    /**
     * Shopper Information
     *
     * @var Shopper
     */
    protected $shopper;

    /**
     * Shopper Email Address (shopperEmailAddress)
     *
     * The email address supplied by the shopper.
     *
     * @var string
     */
    protected $shopperEmail;

    /**
     * Shopper IP Address (shopperIpAddress)
     *
     * The customer's IP address NOT be your server IP. Required for 3D Secure Orders
     *
     * @example "188.102.39.120"
     *
     * @var string
     */
    protected $shopperIP;

    /**
     * Shopper Session ID (shopperSessionId)
     *
     * Set to the shopper's unique session identifier. Required for 3D Secure Orders
     *
     * @var string
     */
    protected $shopperSessionID;

    /**
     * Shopper User Agent
     *
     * Set to the shopper's browser user agent. Required for 3D Secure Orders.
     *
     * @var string
     */
    protected $shopperUserAgent;

    /**
     * Shopper Accept Header
     *
     * Accept header of the shopper's browser. Required for 3D Secure Orders
     *
     * @var string
     */
    protected $shopperAcceptHeader;

    /**
     * Card Issuers URL (redirectURL) [response only]
     *
     * The URL of the payment provider or 3D Secure card issuer hosted
     * page where your customer needs to authenticate their details.
     * You should redirect the customer to this page.
     *
     * Access Control Server (ACS) URL received in the
     * Verify Enrollment Response (VERes).
     *
     * @var string
     */
    protected $redirectURL;

    /**
     * 3D-Secure Token (oneTime3DSToken) [response only]
     *
     * Token to use when redirecting your customer to the card issuer
     * hosted page where your customer needs to enter their 3D Secure password.
     *
     * Payment Authentication Request (PAReq) received in the
     * Verify Enrollment Response (VERes).
     *
     * @var bool
     */
    protected $threeDomainSecureToken; // PAReq

    /**
     * 3D-Secure Response Code (threeDSResponseCode)
     *
     * Response code you will receive from the card issuer after shopper
     * authentication. This code must be provided in your order update
     * when completing a 3D Secure order
     *
     * Payment Authentication Response (PARes)
     *
     * @var bool
     */
    protected $threeDomainSecureResponseCode; // PARes

    /**
     * Success URL (successUrl)
     *
     * APM Orders only: The URL you require the shopper to be sent
     * to after they authenticate successfully on the payment
     * provider's site. Mandatory for APM orders, unless a default
     * success URL is defined in Order Settings. Where both are
     * defined this attribute's value will take precedence.
     *
     * @var string
     */
    protected $successURL;

    /**
     * Failure URL (failureUrl)
     *
     * APM Orders only: The URL you require the shopper to be sent
     * to after they authenticate unsuccessfully on the payment
     * provider's site. Mandatory for APM orders, unless a default
     * failure URL is defined in Order Settings. Where both are
     * defined this attribute's value will take precedence.
     *
     * @var string
     */
    protected $failureURL;

    /**
     * Pending URL (pendingUrl)
     *
     * APM Orders only: The URL you require the shopper to be sent
     * to when the payment approval is pending/not finalised.
     * Mandatory for APM orders, unless a default pending URL is
     * defined in Order Settings. Where both are defined this
     * attribute's value will take precedence.
     *
     * @var string
     */
    protected $pendingURL;

    /**
     * Cancel URL (cancelUrl)
     *
     * APM Orders only: The URL you require the shopper to be sent
     * to after they cancel authentication on the payment provider's
     * site. Mandatory for APM orders, unless a default cancel URL
     * is defined in Order Settings. Where both are defined this
     * attribute's value will take precedence.
     *
     * @var string
     */
    protected $cancelURL;

    /**
     * Customer Identifiers
     *
     * UK Businesses who operate in the Financial Services sector,
     * for example Banks, Mortgage Providers, Payday Loan Operators,
     * need to collect additional details when processing UK Visa
     * transactions for the purposes of better Anti-Money Laundering
     * detection and prevention.
     *
     * Visa requires that UK businesses who are in Merchant Category
     * Code (MCC) 6012, and are processing a UK-issued Visa card
     * transaction, must collect the name, postcode, date of birth
     * and account number of the recipient of the funds.
     *
     * The customerIdentifier key-value attributes must be
     * included in any such order request, and can optionally
     * be specified for an order involving any other card or
     * payment method.
     *
     * @see https://online.worldpay.com/support/articles/how-can-i-comply-with-visa-scheme-requirements-for-my-financial-services-company
     *
     * @var array
     */
    protected $customerIdentifiers = [];

    /**
     * Customer Identifiers Keys
     *
     * MCC 6012 Financial Services fields
     *
     * Merchants who need to specify additional information about the
     * recipient of funds to comply with Visa's MCC 6012 requirements
     * must use the customerIdentifiers object's key-value pairs.
     *
     * For more information on this please see our support topic.
     *
     * 6012 customerIdentifiers Keys and Values
     *
     * - accountReference    String, Mandatory for 6012 merchants
     *      Receiving bank account number or first 6 and last 4
     *      digits of receiving credit card number.
     *      Example : "1234567" or "6759128722"
     * - dateOfBirth String, Mandatory for 6012 merchants
     *      Date of birth of the recipient of the funds in DD-MM-YYYY format.
     *      Example : "01-01-1970"
     * - familyName  String, Mandatory for 6012 merchants
     *      Family name of the recipient of the funds.
     *      Example : "Smith"
     * - postalCode  String, Mandatory for 6012 merchants
     *      Residential post code of the recipient of the funds,
     *      including a space.
     *      Example : "EC4N 8AF"
     *
     * @var array
     */
    public static $customerIdentifiersKeys = [
        'accountReference',
        'dateOfBirth',
        'familyName',
        'postalCode'
    ];

    /**
     * Order Environment [response only]
     *
     * Indicates whether this order was made in the TEST or LIVE environment
     *
     * @var string
     */
    protected $environment;

    /**
     * Order Risk Score [response only]
     *
     * Contains feedback from the Worldpay risk management systems which
     * you can optionally use to complement scoring of your own risk
     * management systems.
     *
     * @var string
     */
    protected $riskScore;

    /**
     * Order History of Payment Statuses [response only]
     *
     * The history of paymentStatuses that this order has gone through.
     * Please note: only fields not already covered in the list above
     * will be explained again here. The full list of fields included
     * in the history field is shown on the right.
     *
     * @var array
     */
    protected $history;

    /**
     * Order Disputes [response only]
     *
     * Object containing details of documents that have been uploaded
     * to defend against a disputed order
     *
     * @var array
     */
    protected $disputes;

    /**
     * Order Sort Properties
     *
     * Used in searching for orders.
     *
     * @var array
     */
    public static $sortProperties = [
        'ADMIN_CODE',
        'CONTACT_EMAIL',
        'CREATE_DATE',
        'MERCHANT_NAME',
        'MODIFICATION_DATE',
        'ONBOARDING_MESSAGE',
        'ONBOARDING_PARTNER_COMPANY_NAME',
        'ONBOARDING_STATUS',
        'PARTNER_COMPANY_NAME',
        'PARTNER_NAME',
        'PRICE_CODE',
        'TIME_ELAPSED',
        'USERNAME'
    ];

    /**
     * Create new order
     *
     * @param string    $type       Type of order you will be placing.
     * @param bool|null $is3DSecure Type of order you will be placing.
     */
    public function __construct($type = 'ECOM', $is3DSecure = null, array $params = [])
    {
        self::parseType($type);

        if ( ! empty($params)) {
            if ($is3DSecure) {
                array_push(self::$required, 'shopperSessionID', 'shopperUserAgent', 'shopperAcceptHeader', 'shopperIP');
            }

            self::parseRequired($params);
        }

        $this->type = $type;
        $this->threeDomainSecure = $is3DSecure;
    }

    /**
     * Order with Payment Method Token
     *
     * @param Token $token
     *
     * @return self
     */
    public function withToken(Token $token)
    {
        $clone = clone $this;
        $clone->token = $token;

        return $clone;
    }

    /**
     * Set Payment Method Token for Order
     *
     * @param Token $token
     *
     * @return void
     */
    public function setToken(Token $token)
    {
        $this->token = $token;
    }

    /**
     * Order with Description.
     *
     * @param string $description
     *
     * @return self
     */
    public function withDescription($description)
    {
        $clone = clone $this;
        $clone->description = $description;

        return $clone;
    }

    /**
     * Order for Amount
     *
     * @param int $amount
     *
     * @return self
     */
    public function withAmount($amount, $authorized = false)
    {
        self::parseAmount($amount);

        $clone = clone $this;
        $clone->amount = $amount;

        return $clone;
    }

    /**
     * Set Order Authorized Amount
     *
     * @param int $amount
     *
     * @return void
     */
    public function setAuthorizedAmount($amount)
    {
        $this->authorizedAmount = $amount;
    }

    /**
     * Order in Currency
     *
     * The ISO currency code of the currency you want to
     * charge your customer in.
     *
     * @param Currency $currency Currency to charge in.
     *
     * @return self
     */
    public function withCurrency(Currency $currency)
    {
        $clone = clone $this;
        $clone->currency = $currency;

        return $clone;
    }

    /**
     * Set Currency of Order
     *
     * @param Currency $currency Currency to charge in.
     *
     * @return void
     */
    public function setCurrency(Currency $currency)
    {
        $this->currency = $currency;
    }

    /**
     * Settle Order in Currency
     *
     * The ISO currency code of the currency you want to be
     * settled in in. This field is mandatory only if you have
     * enabled multiple settlement currencies in currency
     * settings (this setting is only available when you have
     * activated your account).
     *
     * @param Token $token
     *
     * @return self
     */
    public function withSettlementCurrency(Currency $currency)
    {
        $clone = clone $this;
        $clone->settlementCurrency = $currency;

        return $clone;
    }

    /**
     * Set Settlement Currency of Order
     *
     * @param Currency $currency Currency to charge in.
     *
     * @return void
     */
    public function setSettlementCurrency(Currency $currency)
    {
        $this->settlementCurrency = $currency;
    }

    /**
     * Order to Authorize Funds Only
     *
     * @param bool $state
     *
     * @return self
     */
    public function withAuthorizeOnly($state)
    {
        $clone = clone $this;
        $clone->authorizeOnly = (bool) $state;

        return $clone;
    }

    /**
     * Order using 3-D Secure Protocol
     *
     * @param bool $state
     *
     * @return self
     */
    public function with3DSecure($state)
    {
        $clone = clone $this;
        $clone->threeDomainSecure = (bool) $state;

        return $clone;
    }

    /**
     * Order of Type
     *
     * @param string $type
     *
     * @return self
     */
    public function withType($type)
    {
        self::parseType($type);

        $clone = clone $this;
        $clone->type = $type;

        return $clone;
    }

    /**
     * Worldpay Order Code
     *
     * @param string $code
     *
     * @return void
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Order with Code Prefix
     *
     * @param string $prefix
     *
     * @return self
     */
    public function withCodePrefix($prefix)
    {
        self::parseCodeTag($prefix);

        $clone = clone $this;
        $clone->codePrefix = $prefix;

        return $clone;
    }

    /**
     * Order with Code Suffix
     *
     * @param string $suffix
     *
     * @return self
     */
    public function withCodeSuffix($suffix)
    {
        $clone = clone $this;
        $clone->codeSuffix = $suffix;

        return $clone;
    }

    /**
     * Order with Internal Customer Order Reference (customerOrderCode)
     *
     * @param string $customerReference
     *
     * @return self
     */
    public function withCustomerReference($customerReference)
    {
        $clone = clone $this;
        $clone->customerReference = $customerReference;

        return $clone;
    }

    /**
     * Set Customer Reference for Order
     *
     * @param string $customerReference
     *
     * @return void
     */
    public function setCustomerReference($customerReference)
    {
        $this->customerReference = $customerReference;
    }

    /**
     * Set Payment Information for Order
     *
     * @param string      $status The current status of the Order.
     * @param string|null $reason Set only when $status is FAILED.
     * @param array       $detail Response of payment details.
     *
     * @return void
     */
    public function setPayment($status, $reason = null, array $detail = [])
    {
        $paymentMethod = null;

        if ( ! empty($detail)) {
            // Name of the payee/cardholder. In the request this is passed
            // at the top-level of the JSON. In the response it will be
            // embedded in paymentResponse.
            $this->payeeName = $detail['name'];

            // Set payment method used. If a Card was used the
            // response will contain an ObfuscatedCard instead.
            // APM responses will additionally contain a 'name' and
            // field for 'billingAddress'.
            $paymentMethod = (
                 $detail['type'] === PaymentMethod::APM
                ? new APM($detail)
                : new ObfuscatedCard($detail)
            );

            if (isset($detail['billingAddress'])) {
                $paymentMethod->setBillingAddress(new Address($detail['billingAddress']));
            }
        }

        $this->paymentStatus = $status;
        $this->paymentStatusReason = $reason;
        $this->paymentResponse = $paymentMethod;
    }

    /**
     * Order with Payee/Cardholder Name
     *
     * @param string $payeeName
     *
     * @return self
     */
    public function withPayeeName($payeeName)
    {
        $clone = clone $this;
        $clone->payeeName = $payeeName;

        return $clone;
    }

    /**
     * Set Payee/Cardholder Name for Order
     *
     * @param string $payeeName
     *
     * @return self
     */
    public function setPayeeName($payeeName)
    {
        $this->payeeName = $payeeName;
    }

    /**
     * Order with Billing Address
     *
     * @param Address $address
     *
     * @return self
     */
    public function withBillingAddress(Address $address)
    {
        $clone = clone $this;
        $clone->billingAddress = $address;

        return $clone;
    }

    /**
     * Set Billing Address of Order
     *
     * @param Address $address
     *
     * @return void
     */
    public function setBillingAddress(Address $address)
    {
        $this->billingAddress = $address;
    }

    /**
     * Order with Delivery Address
     *
     * @param Address $address
     *
     * @return self
     */
    public function withDeliveryAddress(Address $address)
    {
        $clone = clone $this;
        $clone->deliveryAddress = $address;

        return $clone;
    }

    /**
     * Set Delivery Address of Order
     *
     * @param Address $address
     *
     * @return void
     */
    public function setDeliveryAddress(Address $address)
    {
        $this->deliveryAddress = $address;
    }

    /**
     * Order for Shopper
     *
     * Optional but recommended to improve risk checking.
     *
     * @param Shopper $shopper Shopper Information
     *
     * @return self
     */
    public function withShopper(Shopper $shopper)
    {
        $clone = clone $this;
        $clone->shopper = $shopper;

        return $clone;
    }

    /**
     * Set Shopper Email Address
     *
     * @param string $email Shopper Email Address.
     *
     * @return void
     */
    public function setShopperEmail($email)
    {
        $this->shopperEmail = $email;
    }

    /**
     * Set Shopper IP Address
     *
     * @param string $ip Shopper IP Address.
     *
     * @return void
     */
    public function setShopperIP($ip)
    {
        $this->shopperIP = $ip;
    }

    /**
     * Set Shopper Session ID
     *
     * @param string $sessionID Shopper Session ID.
     *
     * @return void
     */
    public function setShopperSessionID($sessionID)
    {
        $this->shopperSessionID = $sessionID;
    }

    /**
     * Set Shopper User Agent
     *
     * @param string $userAgent Shopper User Agent.
     *
     * @return void
     */
    public function setShopperUserAgent($userAgent)
    {
        $this->shopperUserAgent = $userAgent;
    }

    /**
     * Set Shopper Accept Header
     *
     * @param string $acceptHeader Shopper Accept Header
     *
     * @return void
     */
    public function setShopperAcceptHeader($acceptHeader)
    {
        $this->shopperAcceptHeader = $acceptHeader;
    }

    /**
     * Set Redirect URL
     *
     * The URL of the payment provider or 3D Secure card
     * issuer hosted page where your customer needs to
     * authenticate their details.
     *
     * @param string $url Redirect URL.
     *
     * @return void
     */
    public function setRedirectURL($url)
    {
        $this->redirectURL = $url;
    }

    /**
     * Set 3-D Secure Token for Order.
     *
     * @param string $token PAReq Value
     *
     * @return void
     */
    public function set3DSecureToken($state)
    {
        $this->threeDomainSecureToken = $token;
    }

    /**
     * Order with 3-D Secure Response Code.
     *
     * @param string $responseCode PARes Value
     *
     * @return self
     */
    public function with3DSecureResponseCode($responseCode)
    {
        $clone = clone $this;
        $clone->threeDomainSecureResponseCode = $responseCode;

        return $clone;
    }

    /**
     * APM Order with Callback URLs
     *
     * @param array $links URLs for APM Orders.
     *
     * @return self
     */
    public function withCallbackURLs(array $links)
    {
        $clone = clone $this;

        if (isset($links['cancel'])) {
            $clone->cancelURL = $links['cancel'];
        }

        if (isset($links['failure'])) {
            $clone->failureURL = $links['failure'];
        }

        if (isset($links['pending'])) {
            $clone->pendingURL = $links['pending'];
        }

        if (isset($links['success'])) {
            $clone->successURL = $links['success'];
        }

        return $clone;
    }

    /**
     * APM Order with Success URL
     *
     * @param string $url Success URL
     *
     * @return self
     */
    public function withSuccessURL($url)
    {
        $clone = clone $this;
        $clone->successURL = $url;

        return $clone;
    }

    /**
     * APM Order with Failure URL
     *
     * @param string $url Failure URL
     *
     * @return self
     */
    public function withFailureURL($url)
    {
        $clone = clone $this;
        $clone->failureURL = $url;

        return $clone;
    }

    /**
     * APM Order with Pending URL
     *
     * @param string $url Pending URL
     *
     * @return self
     */
    public function withPendingURL($url)
    {
        $clone = clone $this;
        $clone->pendingURL = $url;

        return $clone;
    }

    /**
     * APM Order with Cancel URL
     *
     * @param string $url Cancel URL
     *
     * @return self
     */
    public function withCancelURL($url)
    {
        $clone = clone $this;
        $clone->cancelURL = $url;

        return $clone;
    }

    /**
     * Customer Identifiers (MCC 6012 Financial Services fields)
     *
     * @param array   $customerIdentifiers Attributes as key-value pairs.
     * @param boolean $required            Check requirements for MCC 6012.
     *
     * @return self
     */
    public function withCustomerIdentifiers(array $customerIdentifiers, $required = false)
    {
        if ($required) {
            self::parseCustomerIdentifiers($customerIdentifiers);
        }

        $clone = clone $this;
        $clone->customerIdentifiers = $customerIdentifiers;

        return $clone;
    }

    /**
     * Set Environment of Order
     *
     * @param string $environment Indicates environment order
     *                            was made in: TEST or LIVE
     *
     * @return void
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * Set Risk Score of Order
     *
     * @param array $score Feedback from the Worldpay risk management systems.
     *
     * @return void
     */
    public function setRiskScore(array $riskScore)
    {
        $this->riskScore = $riskScore;
    }

    /**
     * Set History of Order
     *
     * @param array $history Payment statuses that this order
     *                       has gone through.
     *
     * @return void
     */
    public function setHistory(array $history)
    {
        $this->history = $history;
    }

    /**
     * Set Disputes of Order
     *
     * @param array $disputes Details of documents that have been
     *                        uploaded to defend against a disputed order.
     *
     * @return void
     */
    public function setDisputes(array $disputes)
    {
        $this->disputes = $disputes;
    }

    /**
     * Get Order Token
     *
     * @return Token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get Order Token ID
     *
     * @return string
     */
    public function getTokenID()
    {
        return $this->getToken()->getID();
    }

    /**
     * Get Order Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get Order Amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get Order Authorized Amount
     *
     * @return int
     */
    public function getAuthorizedAmount()
    {
        return $this->authorizedAmount;
    }

    /**
     * Get Order Currency
     *
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Get Order Currency Code
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->getCurrency()->getCode();
    }

    /**
     * Get Order Currency Exponent
     *
     * @return string
     */
    public function getCurrencyExponent()
    {
        return $this->getCurrency()->getExponent();
    }

    /**
     * Get Order Settlement Currency
     *
     * @return Currency
     */
    public function getSettlementCurrency()
    {
        return $this->settlementCurrency;
    }

    /**
     * Get Order Settlement Currency Code
     *
     * @return string
     */
    public function getSettlementCurrencyCode()
    {
        return $this->getSettlementCurrency()->getCode();
    }

    /**
     * Get Order Settlement Currency Exponent
     *
     * @return string
     */
    public function getSettlementCurrencyExponent()
    {
        return $this->getSettlementCurrency()->getExponent();
    }

    /**
     * Order using Authorize Only
     *
     * @return string
     */
    public function isAuthorizeOnly()
    {
        return $this->authorizeOnly;
    }

    /**
     * Order using 3-D Secure Protocol
     *
     * @return string
     */
    public function is3DSecure()
    {
        return $this->threeDomainSecure;
    }

    /**
     * Get Order Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get Order Code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get Order Code Prefix
     *
     * @return string
     */
    public function getCodePrefix()
    {
        return $this->codePrefix;
    }

    /**
     * Get Order Code Suffix
     *
     * @return string
     */
    public function getCodeSuffix()
    {
        return $this->codeSuffix;
    }

    /**
     * Get Customer Order Reference/Code
     *
     * @return string
     */
    public function getCustomerReference()
    {
        return $this->customerReference;
    }

    /**
     * Get Order Payment Status
     *
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * Get Order Payment Status Reason
     *
     * @return string
     */
    public function getPaymentStatusReason()
    {
        return $this->paymentStatusReason;
    }

    /**
     * Get Order Payment Response
     *
     * @return PaymentMethodInterface
     */
    public function getPaymentResponse()
    {
        return $this->paymentResponse;
    }

    /**
     * Get Payee/Cardholder Name for Order
     *
     * @return string
     */
    public function getPayeeName()
    {
        return $this->payeeName;
    }

    /**
     * Get Order Billing Address
     *
     * @return Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * Get Order Delivery Address
     *
     * @return Address
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    /**
     * Get Customer Identifiers
     *
     * @return string
     */
    public function getCustomerIdentifiers()
    {
        return $this->customerIdentifiers;
    }

    /**
     * Get Shopper
     *
     * @return Shopper
     */
    public function getShopper()
    {
        return $this->shopper;
    }

    /**
     * Get Shopper Email
     *
     * @return string
     */
    public function getShopperEmail()
    {
        return $this->shopperEmail;
    }

    /**
     * Get Shopper IP Address
     *
     * @return string
     */
    public function getShopperIP()
    {
        return $this->shopperIP;
    }

    /**
     * Get Shopper Session ID
     *
     * @return string
     */
    public function getShopperSessionID()
    {
        return $this->shopperSessionID;
    }

    /**
     * Get Shopper User Agent
     *
     * @return string
     */
    public function getShopperUserAgent()
    {
        return $this->shopperUserAgent;
    }

    /**
     * Get Shopper AcceptHeader
     *
     * @return string
     */
    public function getShopperAcceptHeader()
    {
        return $this->shopperAcceptHeader;
    }

    /**
     * Get Card Issuer Redirect URL
     *
     * @return string
     */
    public function getRedirectURL()
    {
        return $this->redirectURL;
    }

    /**
     * Get 3-D Secure Token (PAReq)
     *
     * @return string
     */
    public function get3DSecureToken()
    {
        return $this->threeDomainSecureToken;
    }

    /**
     * Get 3-D Secure Code (PARes)
     *
     * @return string
     */
    public function get3DSecureResponseCode()
    {
        return $this->threeDomainSecureResponseCode;
    }

    /**
     * Get APM Success URL
     *
     * @return string
     */
    public function getSuccessURL()
    {
        return $this->successURL;
    }

    /**
     * Get APM Failure URL
     *
     * @return string
     */
    public function getFailureURL()
    {
        return $this->failureURL;
    }

    /**
     * Get APM Pending URL
     *
     * @return string
     */
    public function getPendingURL()
    {
        return $this->pendingURL;
    }

    /**
     * Get APM Cancel URL
     *
     * @return string
     */
    public function getCancelURL()
    {
        return $this->cancelURL;
    }

    /**
     * Get Order Environment
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Get Order Risk Score
     *
     * @return array
     */
    public function getRiskScore()
    {
        return $this->riskScore;
    }

    /**
     * Get Order History
     *
     * @return array
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Get Order Disputes
     *
     * @return array
     */
    public function getDisputes()
    {
        return $this->disputes;
    }

    /**
     * Format Order Parameters
     *
     * @return array
     */
    public function formatParameters(&$params)
    {
        $currency = $this->getCurrency();

        $params = [
            'amount' => $this->getAmount(),
            'currencyCode' => $currency->getCode(),
            'currencyCodeExponent' => $currency->getExponent(),
            'name' => $this->getPayeeName(),
            'orderType' => $this->getType(),
            'orderDescription' => $this->getDescription(),
            'token' => $this->getTokenID()
        ];

        // Set order statuses.
        $params['is3DSOrder'] = $this->is3DSecure();
        $params['authorizeOnly'] = $this->isAuthorizeOnly();

        if ($currency = $this->getSettlementCurrency()) {
            $params['settlementCurrency'] = $currency->getCode();
            $params['settlementCurrencyExponent'] = $currency->getExponent();
        }

        // Append shopper email, IP, session, browser details.
        if ($shopper = $this->getShopper()) {
            $params = array_merge($params, $shopper->getParameters());
        }

        if ($prefix = $this->getCodePrefix()) {
            $params['orderCodePrefix'] = $prefix;
        }

        if ($suffix = $this->getCodeSuffix()) {
            $params['orderCodeSuffix'] = $suffix;
        }

        if ($address = $this->getBillingAddress()) {
            $params['billingAddress'] = $address->getParameters();
        }

        if ($address = $this->getDeliveryAddress()) {
            $params['deliveryAddress'] = $address->getParameters();
        }

        if ($reference = $this->getCustomerReference()) {
            $params['customerOrderCode'] = $reference;
        }

        if ($customerIdentifiers = $this->getCustomerIdentifiers()) {
            $params['customerIdentifiers'] = $customerIdentifiers;
        }

        if ($url = $this->getSuccessURL()) {
            $params['successUrl'] = $url;
        }

        if ($url = $this->getFailureURL()) {
            $params['failureUrl'] = $url;
        }

        if ($url = $this->getPendingURL()) {
            $params['pendingUrl'] = $url;
        }

        if ($url = $this->getCancelURL()) {
            $params['cancelUrl'] = $url;
        }
    }

    /**
     * Parse Order Type
     *
     * @param string $type
     *
     * @return void
     */
    public static function parseType(&$type)
    {
        if ( ! in_array($type, self::$orderTypes)) {
            throw new InvalidArgumentException(sprintf('Invalid order type: %s', $type));
        }
    }

    /**
     * Parse Order Amount
     *
     * @param string $amount
     *
     * @return void
     */
    public static function parseAmount(&$amount)
    {
        if ($amount > 0 && strpos($amount, '.') !== false) {
            throw new InvalidArgumentException('Amount cannot contain a point, it must be a whole number');
        }
    }

    /**
     * Parse Order Code Tag (suffix or prefix)
     *
     * This tag can be up to 20 characters long, and may only
     * include alphanumeric (a-z A-Z 0-9) characters and - minus
     * and _ underscore symbols.
     *
     * @param string $tag
     *
     * @return void
     */
    public static function parseCodeTag(&$tag)
    {
        if (strlen($tag) > 20) {
            throw new InvalidArgumentException('Order code prefix/suffix must be less than 20 characters');
        }

        if (preg_match('/[^a-zA-Z0-9\-\_]/', $tag)) {
            throw new InvalidArgumentException('Order code prefix/suffix may only include alphanumeric (a-z A-Z 0-9) characters and - minus and _ underscore symbols.');
        }
    }

    /**
     * Parse Order Customer Identifier
     *
     * @param array $customerIdentifiers
     *
     * @return void
     */
    public static function parseCustomerIdentifiers(&$customerIdentifiers)
    {
        if (empty($customerIdentifiers)) {
            throw new InvalidArgumentException('Customer identifiers cannot be empty');
        }

        if ( ! is_array($customerIdentifiers)) {
            throw new InvalidArgumentException('Customer identifiers must be an array of key-value attributes');
        }

        foreach (self::$customerIdentifiersKeys as $key) {
            if ( ! isset($customerIdentifiers[$key])) {
                throw new InvalidArgumentException(sprintf('Customer identifier "%s" key is required', $key));
            }

            if (empty($customerIdentifiers[$key])) {
                throw new InvalidArgumentException(sprintf('Customer identifier "%s" value cannot be empty', $key));
            }
        }
    }

    /**
     * Create Order from API Response
     *
     * @param array $response Result from API request
     *
     * @return self
     */
    public static function parse(array $response)
    {
        $map = [
            #'token' => 'token',
            'orderDescription' => 'description',
            'amount' => 'amount',
            'authorizedAmount' => 'authorizedAmount',
            #'currencyCode' => 'currencyCode',
            #'currencyCodeExponent' => 'currencyCodeExponent',
            #'settlementCurrency' => 'settlementCurrency',
            #'settlementCurrencyExponent' => 'settlementCurrencyExponent',
            'authorizeOnly' => 'authorizeOnly',
            'is3DSOrder' => 'threeDomainSecure',
            'orderType' => 'type',
            'orderCode' => 'code',
            'orderCodePrefix' => 'codePrefix',
            'orderCodeSuffix' => 'codeSuffix',
            'customerOrderCode' => 'customerReference',
            #'paymentStatus' => 'paymentStatus',
            #'paymentStatusReason' => 'paymentStatusReason',
            #'paymentResponse' => 'paymentResponse',
            #'name' => 'payeeName', // embedded in paymentResponse
            #'billingAddress' => 'billingAddress',
            #'deliveryAddress' => 'deliveryAddress',
            'shopperEmailAddress' => 'shopperEmail',
            'shopperIpAddress' => 'shopperIP',
            'shopperSessionId' => 'shopperSessionID',
            'shopperUserAgent' => 'shopperUserAgent',
            'shopperAcceptHeader' => 'shopperAcceptHeader',
            'redirectURL' => 'redirectURL',
            'oneTime3DsToken' => 'threeDomainSecureToken',
            'threeDSResponseCode' => 'threeDomainSecureResponseCode',
            'successUrl' => 'successURL',
            'failureUrl' => 'failureURL',
            'pendingUrl' => 'pendingURL',
            'cancelUrl' => 'cancelURL',
            'customerIdentifiers' => 'customerIdentifiers',
            'environment' => 'environment',
            'riskScore' => 'riskScore',
            'history' => 'history',
            'disputes' => 'disputes'
        ];

        $order = null;

        if (isset($response['statusCode']) &&
            $response['statusCode'] === 200) {
            $result = $response['result'];

            $params = [];
            foreach ($map as $external => $internal) {
                if ( ! isset($result[$external])) {
                    continue;
                }

                $params[$internal] = $result[$external];
            }

            $order = new self();
            $order->setParameters($params);
            $order->setToken(new Token($result['token']));
            $order->setCurrency(new Currency($result['currencyCode']));

            if (isset($result['settlementCurrency'])) {
                $order->setSettlementCurrency(new Currency($result['settlementCurrency']));
            }

            // Parse payment status, reason and response.
            $order->setPayment(
                (isset($result['paymentStatus']) ? $result['paymentStatus'] : null),
                (isset($result['paymentStatusReason']) ? $result['paymentStatusReason'] : null),
                (isset($result['paymentResponse']) ? $result['paymentResponse'] : null)
            );

            // Parse billing address.
            if (isset($result['billingAddress'])) {
                $order->setBillingAddress(new Address($result['billingAddress']));
            }

            // Parse delivery address.
            if (isset($result['deliveryAddress'])) {
                $order->setDeliveryAddress(new Address($result['deliveryAddress']));
            }
        }

        return $order;
    }
}
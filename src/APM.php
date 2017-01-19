<?php
/*
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Worldpay;

/**
 * Alternative Payment Method
 *
 * Create an alternative payment method.
 */
class APM extends AbstractResource implements PaymentMethodInterface
{
    const NAME_PAYPAL = 'paypal';

    /**
     * Payment Method Type
     *
     * @var string
     */
    protected $type = PaymentMethod::APM;

    /**
     * APM Name
     *
     * The specific APM that this token represents.
     *
     * @example 'paypal'
     *
     * @var string
     */
    protected $apmName;

    /**
     * Shopper Country Code
     *
     * Indicates to the APM provider which locale to present
     * to the shopper in ISO 3166 2-letter format. Mandatory
     * for APM tokens, unless a default country code is defined
     * in Order Settings. Where both are defined this attribute's
     * value will take precedence.
     *
     * @example 'GB'
     *
     * @var string
     */
    protected $shopperCountryCode;

    /**
     * Name of the Payee
     *
     * @example 'John Smith'
     *
     * @var string
     */
    protected $name;

    /**
     * Billing Address
     *
     * Payee billing address for payment.
     *
     * @var Address
     */
    protected $billingAddress;

    /**
     * Required payment method parameters
     *
     * @var array
     */
    public static $required = [
        'apmName',
        'shopperCountryCode'
    ];

    /**
     * Create new APM
     *
     * @param array $params
     *
     * @return PaymentMethodInterface
     */
    public function __construct(array $params)
    {
        $params['type'] = $this->type;

        $this->setParameters($params);
    }

    /**
     * Get Payment Method Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get Name of Alternative Method
     *
     * @var string
     */
    public function getAPMName()
    {
        return $this->apmName;
    }

    /**
     * Get Shopper Country Code
     *
     * @var string
     */
    public function getShopperCountryCode()
    {
        return $this->shopperCountryCode;
    }

    /**
     * Get Cardholder Name
     *
     * @var string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Payment method with billing address.
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
     * Set Billing Address of Payment method
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
     * Get Billing Address of Card
     *
     * @return Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * Format APM Parameters
     *
     * @return array
     */
    public function formatParameters(&$params)
    {
        $params['type'] = $this->getType();
        $params['apmName'] = $this->getAPMName();
        $params['shopperCountryCode'] = $this->getShopperCountryCode();
    }
}
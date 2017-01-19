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
 * Card Payment Method
 *
 * Create an card payment method.
 */
class AbstractCard extends AbstractResource implements PaymentMethodInterface
{
    /**
     * Card Class Credit
     *
     * @var string
     */
    const CLASS_CREDIT = 'credit';

    /**
     * Card Class Debit
     *
     * @var string
     */
    const CLASS_DEBIT = 'debit';

    /**
     * Payment Method Type
     *
     * This will be 'Card' on the request and 'ObfuscatedCard' on the response.
     *
     * @var string
     */
    protected $type;

    /**
     * Name of the cardholder
     *
     * @example 'John Smith'
     *
     * @var string
     */
    protected $name;

    /**
     * Expiry month of the card
     *
     * @example '2'
     *
     * @var int
     */
    protected $expiryMonth;

    /**
     * Expiry year of the card
     *
     * @example '2015'
     *
     * @var int
     */
    protected $expiryYear;

    /**
     * Issue number on the card. (optional)
     *
     * This field is only used for some types of debit cards
     *
     * @example '1'
     *
     * @var int
     */
    protected $issueNumber;

    /**
     * Start month of the card. (optional)
     *
     * This field is only used for some types of debit cards.
     *
     * @example '2'
     *
     * @var int
     */
    protected $startMonth;

    /**
     * Start year of the card. (optional)
     *
     * This field is only used for some types of debit cards.
     *
     * @example '2013'
     *
     * @var int
     */
    protected $startYear;

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
     * Is Card Obfuscated
     *
     * Obfuscated cards have their numbers masked.
     *
     * @var bool
     */
    public function isObfuscated()
    {
        return ($this->getType() === PaymentMethod::OBFUSCATED_CARD);
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
     * Get Cardholder Name
     *
     * @var string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Card Expiry Month
     *
     * @var int
     */
    public function getExpiryMonth()
    {
        return $this->expiryMonth;
    }

    /**
     * Get Card Expiry Year
     *
     * @var int
     */
    public function getExpiryYear()
    {
        return $this->expiryYear;
    }


    /**
     * Get Card Start Month
     *
     * @var int
     */
    public function getIssueNumber()
    {
        return $this->issueNumber;
    }

    /**
     * Get Card Start Month
     *
     * @var int
     */
    public function getStartMonth()
    {
        return $this->startMonth;
    }

    /**
     * Get Card Start Year
     *
     * @var int
     */
    public function getStartYear()
    {
        return $this->startYear;
    }

    /**
     * Format Card Parameters
     *
     * @return array
     */
    public function formatParameters(&$params)
    {
        $params['type'] = $this->getType();
        $params['name'] = $this->getName();
        $params['expiryMonth'] = $this->getExpiryMonth();
        $params['expiryYear'] = $this->getExpiryYear();

        // Option components.
        if ($issueNumber = $this->getIssueNumber()) {
            $params['issueNumber'] = $issueNumber;
        }

        if ($startMonth = $this->getStartMonth()) {
            $params['startMonth'] = $startMonth;
        }

        if ($startYear = $this->getStartYear()) {
            $params['startYear'] = $startYear;
        }

        // Type specific.
        if ( ! $this->isObfuscated()) {
            $params['cardNumber'] = $this->getNumber();
            $params['cvc'] = $this->getCVC();
        } else {
            $params['cardType'] = $this->getCardType();
            $params['maskedCardNumber'] = $this->getMaskedNumber();
            $params['cardSchemeType'] = $this->getSchemeType();
            $params['cardSchemeName'] = $this->getSchemeName();
            $params['cardIssuer'] = $this->getIssuer();
            $params['countryCode'] = $this->getCountryCode();
            $params['cardClass'] = $this->getClass();
            $params['cardProductTypeDescNonContactless'] = $this->getProductDetail();
            $params['cardProductTypeDescContactless'] = $this->getProductDetail($contactless = true);
            $params['prepaid'] = $this->isPrepaid();
        }
    }
}
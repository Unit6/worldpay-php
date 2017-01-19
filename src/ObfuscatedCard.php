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
 * Obfuscated Card Payment Method
 *
 * Create an card payment method.
 */
class ObfuscatedCard extends AbstractCard
{
    /**
     * Payment Method Type
     *
     * @var string
     */
    protected $type = PaymentMethod::OBFUSCATED_CARD;

    /**
     * Card type. (response only)
     *
     * Type of the card that was used:
     * @see https://online.worldpay.com/support/articles/what-payment-types-can-i-accept-with-worldpay
     *
     * @example 'VISA_CREDIT'
     *
     * @var string
     */
    protected $cardType;

    /**
     * Masked card number. (response only)
     *
     * The last four digits of the card number with all other numbers masked
     *
     * @example 'xxxx xxxx xxxx 1111'
     *
     * @var string
     */
    protected $maskedCardNumber;

    /**
     * Card scheme type. (response only)
     *
     * Indicates the card is either 'consumer' or 'corporate'.
     *
     * @example 'consumer'
     *
     * @var string
     */
    protected $cardSchemeType;

    /**
     * Card scheme name. (response only)
     *
     * Type of the card that was used.
     *
     * @example 'VISA CREDIT'
     *
     * @var string
     */
    protected $cardSchemeName;

    /**
     * Product type detail for non-contactless cards. (response only)
     *
     * @example 'Visa Credit Personal'
     *
     * @var string
     */
    protected $cardProductTypeDescNonContactless;

    /**
     * Product type detail for contactless cards. (response only)
     *
     * @example 'CL Visa Credit Pers'
     *
     * @var string
     */
    protected $cardProductTypeDescContactless;

    /**
     * Card issuer
     *
     * The financial institution that issued the card. (response only)
     *
     * @example 'LLOYDS BANK PLC'
     *
     * @var string
     */
    protected $cardIssuer;

    /**
     * The issuer country code in ISO 3166 2-letter format. (response only)
     *
     * @example 'GB'
     *
     * @var string
     */
    protected $countryCode;

    /**
     * Indicates whether the card is 'credit' or 'debit'. (response only)
     *
     * @example 'credit'
     *
     * @var string
     */
    protected $cardClass;

    /**
     * Indicates whether the card is prepaid. (response only)
     *
     * @example 'false'
     *
     * @var bool
     */
    protected $prepaid;

    /**
     * Required payment method parameters
     *
     * @var array
     */
    protected static $required = [
        'name',
        'expiryMonth',
        'expiryYear',
        'cardType',
        'maskedCardNumber',
        'cardSchemeType',
        'cardSchemeName',
        'cardIssuer',
        'countryCode',
        'cardClass',
        'cardProductTypeDescNonContactless',
        'cardProductTypeDescContactless',
        'prepaid'
    ];

    /**
     * Create New Obfuscated Card
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
     * Get Card Type
     *
     * @var string
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * Get Masked Card Number
     *
     * The last four digits of the card number with all other numbers masked
     *
     * @return string
     */
    public function getMaskedNumber()
    {
        return $this->maskedCardNumber;
    }

    /**
     * Get Card Scheme Type
     *
     * Indicates the card is either 'consumer' or 'corporate'.
     *
     * @return string
     */
    public function getSchemeType()
    {
        return $this->cardSchemeType;
    }

    /**
     * Get Card Scheme Name
     *
     * Type of the card that was used.
     *
     * @return string
     */
    public function getSchemeName()
    {
        return $this->cardSchemeName;
    }

    /**
     * Get Card Issuer
     *
     * The financial institution that issued the card.
     *
     * @return string
     */
    public function getIssuer()
    {
        return $this->cardIssuer;
    }

    /**
     * Country Code
     *
     * The issuer country code in ISO 3166 2-letter format.
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Card Class
     *
     * Indicates whether the card is 'credit' or 'debit'.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->cardClass;
    }

    /**
     * Product type detail for Cards
     *
     * @return string
     */
    public function getProductDetail($contactless = false)
    {
        return ($contactless ? $this->cardProductTypeDescContactless
            : $this->cardProductTypeDescNonContactless);
    }

    /**
     * Indicates whether the card is prepaid
     *
     * @return bool
     */
    public function isPrepaid()
    {
        return $this->prepaid;
    }
}
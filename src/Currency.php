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

use InvalidArgumentException;

/**
 * Currency Class
 *
 * Create a currency instance.
 */
class Currency
{
    /**
     * List of supported currencies
     *
     * @var array
     */
    public static $currencies = [
        'ARS' => ['exponent' => 2, 'name' => 'Nuevo Argentine Peso'],
        'AUD' => ['exponent' => 2, 'name' => 'Australian Dollar'],
        'BRL' => ['exponent' => 2, 'name' => 'Brazilian Real'],
        'CAD' => ['exponent' => 2, 'name' => 'Canadian Dollar'],
        'CHF' => ['exponent' => 2, 'name' => 'Swiss Franc'],
        'CLP' => ['exponent' => 0, 'name' => 'Chilean Peso'],
        'CNY' => ['exponent' => 2, 'name' => 'Yuan Renminbi'],
        'COP' => ['exponent' => 2, 'name' => 'Colombian Peso'],
        'CZK' => ['exponent' => 2, 'name' => 'Czech Koruna'],
        'DKK' => ['exponent' => 2, 'name' => 'Danish Krone'],
        'EUR' => ['exponent' => 2, 'name' => 'Euro'],
        'GBP' => ['exponent' => 2, 'name' => 'Pound Sterling'],
        'HKD' => ['exponent' => 2, 'name' => 'Hong Kong Dollar'],
        'HUF' => ['exponent' => 2, 'name' => 'Hungarian Forint'],
        'IDR' => ['exponent' => 2, 'name' => 'Indonesian Rupiah'],
        'JPY' => ['exponent' => 0, 'name' => 'Japanese Yen'],
        'KES' => ['exponent' => 2, 'name' => 'Kenyan Shilling'],
        'KRW' => ['exponent' => 0, 'name' => 'South-Korean Won'],
        'MYR' => ['exponent' => 2, 'name' => 'Malaysian Ringgit'],
        'NOK' => ['exponent' => 2, 'name' => 'Norwegian Krone'],
        'NZD' => ['exponent' => 2, 'name' => 'New Zealand Dollar'],
        'PHP' => ['exponent' => 2, 'name' => 'Philippine Peso'],
        'PLN' => ['exponent' => 2, 'name' => 'New Polish Zloty'],
        'SEK' => ['exponent' => 2, 'name' => 'Swedish Krone'],
        'SGD' => ['exponent' => 2, 'name' => 'Singapore Dollar'],
        'THB' => ['exponent' => 2, 'name' => 'Thai Baht'],
        'TWD' => ['exponent' => 2, 'name' => 'New Taiwan Dollar'],
        'USD' => ['exponent' => 2, 'name' => 'US Dollars'],
        'VND' => ['exponent' => 0, 'name' => 'Vietnamese New Dong'],
        'ZAR' => ['exponent' => 2, 'name' => 'South African Rand']
    ];

    /**
     * ISO 4217 Code of Currency
     *
     * @var string
     */
    protected $code;

    /**
     * Name of Currency
     *
     * @var string
     */
    protected $name;

    /**
     * Currency Exponent
     *
     * @var int
     */
    protected $exponent;

    /**
     * Currency Exponent Base
     *
     * Convert amount to and from smallest unit of currency
     * to derive exponent which assumes a base of 10.
     *
     * @var integer
     */
    public static $base = 10;

    /**
     * Create a new Currency
     *
     * @param string $name
     */
    public function __construct($code)
    {
        if (empty($code)) {
            throw new InvalidArgumentException('Currency code cannot be empty');
        }

        if ( ! isset(self::$currencies[$code])) {
            throw new InvalidArgumentException(sprintf('Unsupported currency code: "%s"', $code));
        }

        $currency = self::$currencies[$code];

        $this->code = $code;
        $this->name = $currency['name'];
        $this->exponent = $currency['exponent'];

        return $this;
    }

    /**
     * Get Currency Code
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Currency Name
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get Currency Exponent
     *
     * @return string
     */
    public function getExponent()
    {
        return $this->exponent;
    }

    /**
     * Get Decimal Amount
     *
     * Get monetary representation using exponent.
     *
     * @example '15.00'
     *
     * @param string|integer $amount
     *
     * @return string
     */
    public function toDecimal($amount)
    {
        return sprintf('%0.2f', ($amount / pow(self::$base, $this->getExponent())));
    }

    /**
     * Get Amount as Integer
     *
     * Order should be made in the smallest exponent.
     *
     * @example '1500'
     *
     * @param string|float $amount
     *
     * @return integer
     */
    public function toInteger($amount)
    {
        return (integer) ($amount * pow(self::$base, $this->getExponent()));
    }

    /**
     * Return the Currency when cast to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getCode();
    }
}
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
 * Test Currency Instance
 *
 * Check for correct operation of the Currency class.
 */
class CurrencyTest extends \PHPUnit_Framework_TestCase
{
    private $currency;

    public function setUp()
    {
    }

    public function tearDown()
    {
        unset($this->currency);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFailCreateCurrencyWithEmptyCurrencyCode()
    {
        $currency = new Currency('');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFailCreateCurrencyWithInvalidCurrencyCode()
    {
        $currency = new Currency('000');
    }

    public function testCreateInstanceOfCurrency()
    {
        $currency = new Currency(CURRENCY_CODE);

        $this->assertInstanceOf(__NAMESPACE__ . '\Currency', $currency);

        return $currency;
    }

    /**
     * @depends testCreateInstanceOfCurrency
     */
    public function testGetCurrencyName(Currency $currency)
    {
        $this->assertEquals(CURRENCY_NAME, $currency->getName());
    }

    /**
     * @depends testCreateInstanceOfCurrency
     */
    public function testGetCurrencyCodeByCastToString(Currency $currency)
    {
        $this->assertEquals(CURRENCY_CODE, (string) $currency);
    }

    /**
     * @depends testCreateInstanceOfCurrency
     */
    public function testGetCurrencyCode(Currency $currency)
    {
        $this->assertEquals(CURRENCY_CODE, $currency->getCode());
    }

    /**
     * @depends testCreateInstanceOfCurrency
     */
    public function testGetCurrencyExponent(Currency $currency)
    {
        $this->assertEquals(CURRENCY_EXPONENT, $currency->getExponent());
    }

    /**
     * @depends testCreateInstanceOfCurrency
     */
    public function testGetCurrencyAmountInDecimal(Currency $currency)
    {
        $this->assertEquals(AMOUNT_DECIMAL, $currency->toDecimal(AMOUNT));
    }

    /**
     * @depends testCreateInstanceOfCurrency
     */
    public function testGetCurrencyAmountInInteger(Currency $currency)
    {
        $this->assertEquals(AMOUNT, $currency->toInteger(AMOUNT_DECIMAL));
    }
}
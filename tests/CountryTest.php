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
 * Test Country Instance
 *
 * Check for correct operation of the Country class.
 */
class CountryTest extends \PHPUnit_Framework_TestCase
{
    private $country;

    public function setUp()
    {
    }

    public function tearDown()
    {
        unset($this->country);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFailCreateCountryWithEmptyCountryCode()
    {
        $country = new Country('');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFailCreateCountryWithInvalidCountryCode()
    {
        $country = new Country('000');
    }

    public function testCreateInstanceOfCountry()
    {
        $country = new Country(COUNTRY_CODE);

        $this->assertInstanceOf(__NAMESPACE__ . '\Country', $country);

        return $country;
    }

    /**
     * @depends testCreateInstanceOfCountry
     */
    public function testGetCountryName(Country $country)
    {
        $this->assertEquals(COUNTRY_NAME, $country->getName());
    }

    /**
     * @depends testCreateInstanceOfCountry
     */
    public function testGetCountryCode(Country $country)
    {
        $this->assertEquals(COUNTRY_CODE, $country->getCode());
    }

    /**
     * @depends testCreateInstanceOfCountry
     */
    public function testGetCountryCodeByCastToString(Country $country)
    {
        $this->assertEquals(COUNTRY_CODE, (string) $country);
    }
}
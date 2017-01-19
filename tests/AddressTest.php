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
 * Test Address Instance
 *
 * Check for correct operation of the Address class.
 */
class AddressTest extends \PHPUnit_Framework_TestCase
{
    private $address;

    public function setUp()
    {
    }

    public function tearDown()
    {
        unset($this->address);
    }

    public function testAddressRequiredParameters()
    {
        $params = [
            'address1' => ADDRESS_1,
            'address2' => ADDRESS_2,
            'address3' => ADDRESS_3,
            'postalCode' => POSTAL_CODE,
            'city' => CITY,
            'state' => STATE,
            'countryCode' => COUNTRY_CODE,
        ];

        foreach (Address::$required as $key) {
            $this->assertArrayHasKey($key, $params);
            $this->assertNotEmpty($params[$key]);
        }

        return $params;
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionWithEmptyAddressParameters()
    {
        $address = new Address([]);
    }

    /**
     * @depends testAddressRequiredParameters
     * @expectedException \InvalidArgumentException
     */
    public function testFailCreateAddressWithInvalidCountryCode(array $params)
    {
        $params['countryCode'] = '00';

        $address = new Address($params);
    }

    /**
     * @depends testAddressRequiredParameters
     */
    public function testCreateInstanceOfAddress(array $params)
    {
        $address = new Address($params);

        $this->assertInstanceOf(__NAMESPACE__ . '\Address', $address);
        $this->assertInstanceOf(__NAMESPACE__ . '\AbstractResource', $address);

        return $address;
    }

    /**
     * @depends testCreateInstanceOfAddress
     */
    public function testGetAddressCountryInstance(Address $address)
    {
        $this->assertInstanceOf(__NAMESPACE__ . '\Country', $address->getCountry());
    }

    /**
     * @depends testCreateInstanceOfAddress
     */
    public function testGetAddressCountryCode(Address $address)
    {
        $this->assertEquals(COUNTRY_CODE, $address->getCountryCode());
    }

    /**
     * @depends testCreateInstanceOfAddress
     */
    public function testGetAddressState(Address $address)
    {
        $this->assertEquals(STATE, $address->getState());
    }

    /**
     * @depends testCreateInstanceOfAddress
     */
    public function testGetAddressCity(Address $address)
    {
        $this->assertEquals(CITY, $address->getCity());
    }

    /**
     * @depends testCreateInstanceOfAddress
     */
    public function testGetAddressPostalCode(Address $address)
    {
        $this->assertEquals(POSTAL_CODE, $address->getPostalCode());
    }

    /**
     * @depends testCreateInstanceOfAddress
     */
    public function testGetAddressLine(Address $address)
    {
        $this->assertEquals(ADDRESS_1, $address->getLine(1));
        $this->assertEquals(ADDRESS_3, $address->getLine(3));
        $this->assertEquals(ADDRESS_1 . ', ' . ADDRESS_3, $address->getLine(0));
    }

    /**
     * @depends testCreateInstanceOfAddress
     */
    public function testGetAddressParameters(Address $address)
    {
        $params = $address->getParameters();

        $this->assertArrayHasKey('address1', $params);
        $this->assertArrayHasKey('address3', $params);
        $this->assertArrayHasKey('postalCode', $params);
        $this->assertArrayHasKey('city', $params);
        $this->assertArrayHasKey('countryCode', $params);
    }
}
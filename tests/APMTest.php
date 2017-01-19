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
 * Test APM Instance
 *
 * Check for correct operation of the APM class.
 */
class APMTest extends \PHPUnit_Framework_TestCase
{
    private $apm;

    public function setUp()
    {
    }

    public function tearDown()
    {
        unset($this->apm);
    }

    public function testAPMRequiredParameters()
    {
        $params = [
            'apmName' => APM_NAME,
            'shopperCountryCode' => SHOPPER_COUNTRY_CODE,
        ];

        foreach (APM::$required as $key) {
            $this->assertArrayHasKey($key, $params);
            $this->assertNotEmpty($params[$key]);
        }

        return $params;
    }

    /**
     * @depends testAPMRequiredParameters
     */
    public function testCreateInstanceWithPayeeNameAPM(array $params)
    {
        $params['name'] = PAYEE_NAME;
        $apm = new APM($params);

        $this->assertInstanceOf(__NAMESPACE__ . '\APM', $apm);
        $this->assertInstanceOf(__NAMESPACE__ . '\PaymentMethodInterface', $apm);

        $this->assertEquals(PAYEE_NAME, $apm->getName());
    }

    /**
     * @depends testAPMRequiredParameters
     */
    public function testCreateInstanceOfAPM(array $params)
    {
        $apm = new APM($params);

        $this->assertInstanceOf(__NAMESPACE__ . '\APM', $apm);
        $this->assertInstanceOf(__NAMESPACE__ . '\PaymentMethodInterface', $apm);

        return $apm;
    }

    /**
     * @depends testCreateInstanceOfAPM
     */
    public function testGetNameOfAPM(APM $apm)
    {
        $this->assertEquals(APM_NAME, $apm->getAPMName());
    }

    /**
     * @depends testCreateInstanceOfAPM
     */
    public function testBillingAddressWithAPM(APM $apm)
    {
        $address = new Address([
            'address1' => ADDRESS_1,
            'address2' => ADDRESS_2,
            'address3' => ADDRESS_3,
            'postalCode' => POSTAL_CODE,
            'city' => CITY,
            'state' => STATE,
            'countryCode' => COUNTRY_CODE,
        ]);

        $this->assertInstanceOf(__NAMESPACE__ . '\Address', $address);

        return $apm->withBillingAddress($address);
    }

    /**
     * @depends testBillingAddressWithAPM
     */
    public function testGetBillingAddressOfAPM(APM $apm)
    {
        $address = $apm->getBillingAddress();

        $this->assertInstanceOf(__NAMESPACE__ . '\Address', $address);
    }

    /**
     * @depends testCreateInstanceOfAPM
     */
    public function testGetParametersOfAPM(APM $apm)
    {
        $params = $apm->getParameters();

        $this->assertArrayHasKey('type', $params);
        $this->assertArrayHasKey('apmName', $params);
        $this->assertArrayHasKey('shopperCountryCode', $params);
    }
}
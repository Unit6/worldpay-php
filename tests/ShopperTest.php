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
 * Test Shopper Instance
 *
 * Check for correct operation of the Shopper class.
 */
class ShopperTest extends \PHPUnit_Framework_TestCase
{
    private $shopper;

    public function setUp()
    {
    }

    public function tearDown()
    {
        unset($this->shopper);
    }

    public function testShopperParameters()
    {
        $params = [
            'email' => SHOPPER_EMAIL,
            'sessionID' => SHOPPER_SESSION_ID,
            'ip' => SHOPPER_IP,
            'userAgent' => SHOPPER_USER_AGENT,
            'acceptHeader' => SHOPPER_ACCEPT_HEADER,
        ];

        foreach ($params as $key => $value) {
            $this->assertNotEmpty($value);
        }

        return $params;
    }

    public function testCreateInstanceOfShopperWithNoArguments()
    {
        $shopper = new Shopper();

        $this->assertInstanceOf(__NAMESPACE__ . '\Shopper', $shopper);
    }

    /**
     * @depends testShopperParameters
     */
    public function testCreateInstanceOfShopperWithParameters(array $params)
    {
        $shopper = (new Shopper())
            ->withEmail($params['email'])
            ->withIP($params['ip'])
            ->withSessionID($params['sessionID'])
            ->withUserAgent($params['userAgent'])
            ->withAcceptHeader($params['acceptHeader']);


        $this->assertEquals(SHOPPER_EMAIL, $shopper->getEmail());
        $this->assertEquals(SHOPPER_IP, $shopper->getIP());

        $this->assertInstanceOf(__NAMESPACE__ . '\Shopper', $shopper);
    }

    /**
     * @depends testShopperParameters
     */
    public function testCreateInstanceOfShopper(array $params)
    {
        $shopper = new Shopper($params['email'], $params['sessionID']);

        $this->assertInstanceOf(__NAMESPACE__ . '\Shopper', $shopper);
        $this->assertInstanceOf(__NAMESPACE__ . '\AbstractResource', $shopper);

        return $shopper;
    }

    /**
     * @depends testCreateInstanceOfShopper
     */
    public function testGetShopperEmail(Shopper $shopper)
    {
        $this->assertEquals(SHOPPER_EMAIL, $shopper->getEmail());
    }

    /**
     * @depends testCreateInstanceOfShopper
     */
    public function testGetShopperSessionID(Shopper $shopper)
    {
        $this->assertEquals(SHOPPER_SESSION_ID, $shopper->getSessionID());
    }

    /**
     * @depends testCreateInstanceOfShopper
     */
    public function testShopperIPIsNull(Shopper $shopper)
    {
        $this->assertNull($shopper->getIP());

        return $shopper;
    }

    /**
     * @depends testShopperIPIsNull
     */
    public function testShopperInstanceWithIP(Shopper $shopper)
    {
        return $shopper->withIP(SHOPPER_IP);
    }

    /**
     * @depends testShopperInstanceWithIP
     */
    public function testGetShopperIP(Shopper $shopper)
    {
        $this->assertEquals(SHOPPER_IP, $shopper->getIP());
    }

    /**
     * @depends testCreateInstanceOfShopper
     */
    public function testShopperUserAgentIsNull(Shopper $shopper)
    {
        $this->assertNull($shopper->getUserAgent());

        return $shopper;
    }

    /**
     * @depends testShopperUserAgentIsNull
     */
    public function testShopperInstanceWithUserAgent(Shopper $shopper)
    {
        return $shopper->withUserAgent(SHOPPER_USER_AGENT);
    }

    /**
     * @depends testShopperInstanceWithUserAgent
     */
    public function testGetShopperUserAgent(Shopper $shopper)
    {
        $this->assertEquals(SHOPPER_USER_AGENT, $shopper->getUserAgent());
    }

    /**
     * @depends testCreateInstanceOfShopper
     */
    public function testShopperAcceptHeaderIsNull(Shopper $shopper)
    {
        $this->assertNull($shopper->getAcceptHeader());

        return $shopper;
    }

    /**
     * @depends testShopperAcceptHeaderIsNull
     */
    public function testShopperInstanceWithAcceptHeader(Shopper $shopper)
    {
        return $shopper->withAcceptHeader(SHOPPER_ACCEPT_HEADER);
    }

    /**
     * @depends testShopperInstanceWithAcceptHeader
     */
    public function testGetShopperAcceptHeader(Shopper $shopper)
    {
        $this->assertEquals(SHOPPER_ACCEPT_HEADER, $shopper->getAcceptHeader());
    }

    /**
     * @depends testCreateInstanceOfShopper
     */
    public function testGetShopperParameters(Shopper $shopper)
    {
        $params = $shopper->getParameters();

        $this->assertArrayHasKey('shopperEmailAddress', $params);
        $this->assertArrayHasKey('shopperIpAddress', $params);
        $this->assertArrayHasKey('shopperSessionId', $params);
        $this->assertArrayHasKey('shopperUserAgent', $params);
        $this->assertArrayHasKey('shopperAcceptHeader', $params);
    }
}
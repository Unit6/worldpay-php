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
 * Test Payment Method Instance
 *
 * Check for correct operation of the PaymentMethod class.
 */
class PaymentMethodTest extends \PHPUnit_Framework_TestCase
{
    private $methods;

    public function setUp()
    {
        $this->methods = PaymentMethod::getConstants();
    }

    public function tearDown()
    {
        unset($this->methods);
    }

    public function testPaymentMethodCount()
    {
        $this->assertEquals(3, count($this->methods));
    }

    public function testPaymentMethodOptionAPM()
    {
        $this->assertEquals('APM', PaymentMethod::APM);
    }

    public function testPaymentMethodOptionCard()
    {
        $this->assertEquals('Card', PaymentMethod::CARD);
    }

    public function testPaymentMethodOptionObfuscatedCard()
    {
        $this->assertEquals('ObfuscatedCard', PaymentMethod::OBFUSCATED_CARD);
    }
}
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
 * Test Payment Statuses Instance
 *
 * Check for correct operation of the PaymentStatus class.
 */
class PaymentStatusTest extends \PHPUnit_Framework_TestCase
{
    private $statuses;

    public function setUp()
    {
        $this->statuses = PaymentStatus::getConstants();
    }

    public function tearDown()
    {
        unset($this->statuses);
    }

    public function testPaymentStatusCount()
    {
        $this->assertEquals(13, count($this->statuses));
    }

    public function testPaymentStatusList()
    {
        foreach ($this->statuses as $key => $value) {
            $this->assertEquals($key, $value);
        }
    }
}
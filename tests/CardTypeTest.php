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
 * Test Card Type Instance
 *
 * Check for correct operation of the CardType class.
 */
class CardTypeTest extends \PHPUnit_Framework_TestCase
{
    private $types;

    public function setUp()
    {
        $this->types = CardType::getConstants();
    }

    public function tearDown()
    {
        unset($this->types);
    }

    public function testCardTypeCount()
    {
        $this->assertEquals(13, count($this->types));
    }

    public function testCardTypeList()
    {
        foreach ($this->types as $key => $value) {
            $this->assertEquals($key, $value);
        }
    }
}
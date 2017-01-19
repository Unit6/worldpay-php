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
 * Test Card Scheme Instance
 *
 * Check for correct operation of the CardScheme class.
 */
class CardSchemeTest extends \PHPUnit_Framework_TestCase
{
    private $types;
    private $names;

    public function setUp()
    {
        $this->types = CardScheme::getConstants('type');
        $this->names = CardScheme::getConstants('name');
    }

    public function tearDown()
    {
        unset($this->types);
        unset($this->names);
    }

    public function testCardSchemeCount()
    {
        $this->assertEquals(2, count($this->types));
        $this->assertEquals(6, count($this->names));
    }

    public function testCardSchemeTypes()
    {
        $this->assertEquals('consumer', CardScheme::TYPE_CONSUMER);
        $this->assertEquals('corporate', CardScheme::TYPE_CORPORATE);
    }

    public function testCardSchemeNames()
    {
        $this->assertEquals('VISA CREDIT', CardScheme::NAME_VISA_CREDIT);
        $this->assertEquals('VISA DEBIT', CardScheme::NAME_VISA_DEBIT);
        $this->assertEquals('MASTERCARD CREDIT', CardScheme::NAME_MCI_CREDIT);
        $this->assertEquals('MASTERCARD DEBIT', CardScheme::NAME_MCI_DEBIT);
        $this->assertEquals('MAESTRO', CardScheme::NAME_MAESTRO);
        $this->assertEquals('ELECTRON', CardScheme::NAME_ELECTRON);
    }
}
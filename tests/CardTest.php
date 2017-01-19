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
 * Test Card Instance
 *
 * Check for correct operation of the Card class.
 */
class CardTest extends \PHPUnit_Framework_TestCase
{
    private $card;

    public function setUp()
    {
    }

    public function tearDown()
    {
        unset($this->card);
    }

    public function testCardRequiredParameters()
    {
        $params = [
            'name' => CARD_NAME,
            'expiryMonth' => CARD_EXPIRY_MONTH,
            'expiryYear' => CARD_EXPIRY_YEAR,
            'cardNumber' => CARD_NUMBER,
            'cvc' => CARD_CVC,
            // optional.
            'issueNumber' => CARD_ISSUE_NUMBER,
            'startMonth' => CARD_START_MONTH,
            'startYear' => CARD_START_YEAR
        ];

        foreach (Card::$required as $key) {
            $this->assertArrayHasKey($key, $params);
            $this->assertNotEmpty($params[$key]);
        }

        return $params;
    }

    /**
     * @depends testCardRequiredParameters
     */
    public function testCreateInstanceOfCard(array $params)
    {
        $card = new Card($params);

        $this->assertInstanceOf(__NAMESPACE__ . '\Card', $card);
        $this->assertInstanceOf(__NAMESPACE__ . '\AbstractCard', $card);
        $this->assertInstanceOf(__NAMESPACE__ . '\PaymentMethodInterface', $card);

        return $card;
    }

    /**
     * @depends testCreateInstanceOfCard
     */
    public function testGetCardNumber(Card $card)
    {
        $this->assertEquals(CARD_NUMBER, $card->getNumber());
    }

    /**
     * @depends testCreateInstanceOfCard
     */
    public function testGetCardSecurityCode(Card $card)
    {
        $this->assertEquals(CARD_CVC, $card->getCVC());
    }

    /**
     * @depends testCreateInstanceOfCard
     */
    public function testBillingAddressWithCard(Card $card)
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

        return $card->withBillingAddress($address);
    }

    /**
     * @depends testBillingAddressWithCard
     */
    public function testGetBillingAddressOfCard(Card $card)
    {
        $address = $card->getBillingAddress();

        $this->assertInstanceOf(__NAMESPACE__ . '\Address', $address);
    }

    /**
     * @depends testCreateInstanceOfCard
     */
    public function testGetCardType(Card $card)
    {
        $this->assertEquals(PaymentMethod::CARD, $card->getType());
    }

    /**
     * @depends testCreateInstanceOfCard
     */
    public function testGetCardholderName(Card $card)
    {
        $this->assertEquals(CARD_NAME, $card->getName());
    }

    /**
     * @depends testCreateInstanceOfCard
     */
    public function testGetCardExpiryMonth(Card $card)
    {
        $this->assertEquals(CARD_EXPIRY_MONTH, $card->getExpiryMonth());
    }

    /**
     * @depends testCreateInstanceOfCard
     */
    public function testGetCardExpiryYear(Card $card)
    {
        $this->assertEquals(CARD_EXPIRY_YEAR, $card->getExpiryYear());
    }

    /**
     * @depends testCreateInstanceOfCard
     */
    public function testGetCardIssueNumber(Card $card)
    {
        $this->assertEquals(CARD_ISSUE_NUMBER, $card->getIssueNumber());
    }

    /**
     * @depends testCreateInstanceOfCard
     */
    public function testGetCardStartMonth(Card $card)
    {
        $this->assertEquals(CARD_START_MONTH, $card->getStartMonth());
    }

    /**
     * @depends testCreateInstanceOfCard
     */
    public function testGetCardStartYear(Card $card)
    {
        $this->assertEquals(CARD_START_YEAR, $card->getStartYear());
    }

    /**
     * @depends testCreateInstanceOfCard
     */
    public function testGetCardParameters(Card $card)
    {
        $params = $card->getParameters();

        $this->assertArrayHasKey('type', $params);
        $this->assertArrayHasKey('name', $params);
        $this->assertArrayHasKey('expiryMonth', $params);
        $this->assertArrayHasKey('expiryYear', $params);
        $this->assertArrayHasKey('issueNumber', $params);
        $this->assertArrayHasKey('startMonth', $params);
        $this->assertArrayHasKey('cardNumber', $params);
        $this->assertArrayHasKey('cvc', $params);
    }
}
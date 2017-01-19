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
 * Test ObfuscatedCard Instance
 *
 * Check for correct operation of the ObfuscatedCard class.
 */
class ObfuscatedCardTest extends \PHPUnit_Framework_TestCase
{
    private $obfuscatedCard;

    public function setUp()
    {
    }

    public function tearDown()
    {
        unset($this->obfuscatedCard);
    }

    public function testObfuscatedCardRequiredParameters()
    {
        $params = [
            'name' => CARD_NAME,
            'expiryMonth' => CARD_EXPIRY_MONTH,
            'expiryYear' => CARD_EXPIRY_YEAR,
            'cardType' => CARD_TYPE,
            'maskedCardNumber' => CARD_NUMBER_MASKED,
            'cardSchemeType' => CARD_SCHEME_TYPE,
            'cardSchemeName' => CARD_SCHEME_NAME,
            'cardIssuer' => CARD_ISSUER,
            'countryCode' => CARD_COUNTRY_CODE,
            'cardClass' => CARD_CLASS,
            'cardProductTypeDescNonContactless' => CARD_PRODUCT_NONC,
            'cardProductTypeDescContactless' => CARD_PRODUCT_CONT,
            'prepaid' => CARD_PREPAID,
            // optional.
            'issueNumber' => CARD_ISSUE_NUMBER,
            'startMonth' => CARD_START_MONTH,
            'startYear' => CARD_START_YEAR
        ];

        foreach (ObfuscatedCard::$required as $key) {
            if ($key === 'prepaid') continue;
            $this->assertArrayHasKey($key, $params);
            $this->assertNotEmpty($params[$key]);
        }

        return $params;
    }

    /**
     * @depends testObfuscatedCardRequiredParameters
     */
    public function testCreateInstanceOfObfuscatedCard(array $params)
    {
        $card = new ObfuscatedCard($params);

        $this->assertInstanceOf(__NAMESPACE__ . '\ObfuscatedCard', $card);
        $this->assertInstanceOf(__NAMESPACE__ . '\AbstractCard', $card);
        $this->assertInstanceOf(__NAMESPACE__ . '\PaymentMethodInterface', $card);

        return $card;
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetCardType(ObfuscatedCard $card)
    {
        $this->assertEquals(CARD_TYPE, $card->getCardType());
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetCardMaskedNumber(ObfuscatedCard $card)
    {
        $this->assertEquals(CARD_NUMBER_MASKED, $card->getMaskedNumber());
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetCardSchemeType(ObfuscatedCard $card)
    {
        $this->assertEquals(CARD_SCHEME_TYPE, $card->getSchemeType());
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetCardSchemeName(ObfuscatedCard $card)
    {
        $this->assertEquals(CARD_SCHEME_NAME, $card->getSchemeName());
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetCardIssuer(ObfuscatedCard $card)
    {
        $this->assertEquals(CARD_ISSUER, $card->getIssuer());
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetCardCountryCode(ObfuscatedCard $card)
    {
        $this->assertEquals(CARD_COUNTRY_CODE, $card->getCountryCode());
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetCardClass(ObfuscatedCard $card)
    {
        $this->assertEquals(CARD_CLASS, $card->getClass());
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetCardProductDetail(ObfuscatedCard $card)
    {
        $this->assertEquals(CARD_PRODUCT_NONC, $card->getProductDetail());
        $this->assertEquals(CARD_PRODUCT_CONT, $card->getProductDetail($contactless = true));
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetCardIsPrepaid(ObfuscatedCard $card)
    {
        $this->assertEquals(CARD_PREPAID, $card->isPrepaid());
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testBillingAddressWithObfuscatedCard(ObfuscatedCard $card)
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
     * @depends testBillingAddressWithObfuscatedCard
     */
    public function testGetBillingAddressOfObfuscatedCard(ObfuscatedCard $card)
    {
        $address = $card->getBillingAddress();

        $this->assertInstanceOf(__NAMESPACE__ . '\Address', $address);
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetObfuscatedCardType(ObfuscatedCard $card)
    {
        $this->assertEquals(PaymentMethod::OBFUSCATED_CARD, $card->getType());
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetCardholderNameOfObfuscatedCard(ObfuscatedCard $card)
    {
        $this->assertEquals(CARD_NAME, $card->getName());
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetObfuscatedCardExpiryMonth(ObfuscatedCard $card)
    {
        $this->assertEquals(CARD_EXPIRY_MONTH, $card->getExpiryMonth());
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetObfuscatedCardExpiryYear(ObfuscatedCard $card)
    {
        $this->assertEquals(CARD_EXPIRY_YEAR, $card->getExpiryYear());
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetObfuscatedCardIssueNumber(ObfuscatedCard $card)
    {
        $this->assertEquals(CARD_ISSUE_NUMBER, $card->getIssueNumber());
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetObfuscatedCardStartMonth(ObfuscatedCard $card)
    {
        $this->assertEquals(CARD_START_MONTH, $card->getStartMonth());
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetObfuscatedCardStartYear(ObfuscatedCard $card)
    {
        $this->assertEquals(CARD_START_YEAR, $card->getStartYear());
    }

    /**
     * @depends testCreateInstanceOfObfuscatedCard
     */
    public function testGetObfuscatedCardParameters(ObfuscatedCard $card)
    {
        $params = $card->getParameters();

        $this->assertArrayHasKey('type', $params);
        $this->assertArrayHasKey('name', $params);
        $this->assertArrayHasKey('expiryMonth', $params);
        $this->assertArrayHasKey('expiryYear', $params);
        $this->assertArrayHasKey('issueNumber', $params);
        $this->assertArrayHasKey('startMonth', $params);
        $this->assertArrayHasKey('cardType', $params);
        $this->assertArrayHasKey('maskedCardNumber', $params);
        $this->assertArrayHasKey('cardSchemeType', $params);
        $this->assertArrayHasKey('cardSchemeName', $params);
        $this->assertArrayHasKey('cardIssuer', $params);
        $this->assertArrayHasKey('countryCode', $params);
        $this->assertArrayHasKey('cardClass', $params);
        $this->assertArrayHasKey('cardProductTypeDescNonContactless', $params);
        $this->assertArrayHasKey('cardProductTypeDescContactless', $params);
        $this->assertArrayHasKey('prepaid', $params);
    }
}
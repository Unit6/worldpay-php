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
 * Test Token Instance
 *
 * Check for correct operation of the Token class.
 */
class TokenTest extends \PHPUnit_Framework_TestCase
{
    private $token;

    public function setUp()
    {
    }

    public function tearDown()
    {
        unset($this->token);
    }

    public function testCreateInstanceOfReusableToken()
    {
        $token = new Token(TOKEN_ID, true);

        $this->assertInstanceOf(__NAMESPACE__ . '\Token', $token);
        $this->assertNotEmpty($token->getID());
        $this->assertTrue($token->isReusable());
    }

    public function testCreateInstanceOfToken()
    {
        $token = new Token(TOKEN_ID);

        $this->assertInstanceOf(__NAMESPACE__ . '\Token', $token);

        return $token;
    }

    /**
     * @depends testCreateInstanceOfToken
     */
    public function testGetTokenID(Token $token)
    {
        $this->assertEquals(TOKEN_ID, $token->getID());
    }

    /**
     * @depends testCreateInstanceOfToken
     */
    public function testGetTokenIsReusable(Token $token)
    {
        $this->assertFalse($token->isReusable());
    }

    /**
     * @depends testCreateInstanceOfToken
     */
    public function testTokenWithMethods(Token $token)
    {
        $paymentMethod = new Card([
            'name' => CARD_NAME,
            'expiryMonth' => CARD_EXPIRY_MONTH,
            'expiryYear' => CARD_EXPIRY_YEAR,
            'cardNumber' => CARD_NUMBER,
            'cvc' => CARD_CVC
        ]);

        return $token
            ->withPaymentMethod($paymentMethod)
            ->withClientKey(CLIENT_KEY);
    }

    /**
     * @depends testTokenWithMethods
     */
    public function testGetClientKeyFromToken(Token $token)
    {
        $this->assertEquals(CLIENT_KEY, $token->getClientKey());
    }

    /**
     * @depends testTokenWithMethods
     */
    public function testGetPaymentMethodFromToken(Token $token)
    {
        $this->assertInstanceOf(__NAMESPACE__ . '\PaymentMethodInterface', $token->getPaymentMethod());
    }

    /**
     * @depends testTokenWithMethods
     */
    public function testGetTokenParameters(Token $token)
    {
        $params = $token->getParameters();

        $this->assertArrayHasKey('reusable', $params);
        $this->assertArrayHasKey('paymentMethod', $params);
        $this->assertArrayHasKey('clientKey', $params);
    }
}
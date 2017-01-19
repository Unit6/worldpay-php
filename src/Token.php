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

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Order Class
 *
 * Create an order resource.
 */
class Token
{
    /**
     * Token UUID
     *
     * A unique token which the Worldpay.js library added
     * to your checkout form. This token represents the
     * customer's card details which Worldpay.js stored on our server
     *
     * @var string
     */
    protected $id;

    /**
     * Token Reusable
     *
     * Indicating whether the token should be used only once
     * (false) or multiple times (true).
     *
     * @var bool
     */
    protected $reusable = false;

    /**
     * Payment Method
     *
     * Object containing all payment details, contents vary by token type
     *
     * @var PaymentMethod
     */
    protected $paymentMethod;

    /**
     * Your Worldpay client key
     *
     * @var string
     */
    protected $clientKey;

    /**
     * Creating a Card Token
     *
     * Note: normally you should not invoke this API directly.
     * It will be used by Worldpay.js when taking card details
     * or by our iOS or Android libraries when using mobile apps.
     *
     * You should only call this API directly if you want to see
     * the card details and your platform is PCI compliant.
     *
     * Creating a new token is done through a POST on the Tokens API,
     * specifying paymentMethod's type as Card.
     *
     * @param string $id       Token UUID
     * @param bool   $reusable Indicating whether the token should be
     *                         used only once (false) or multiple times (true)
     *
     * @return Token
     */
    public function __construct($id, $reusable = false)
    {
        $this->id = $id;
        $this->reusable = $reusable;
    }

    /**
     * Create Token from API Response
     *
     * @param array  $response  Result from API request
     * @param string $clientKey Worldpay Client Key
     *
     * @return self
     */
    public static function parse(array $response, $clientKey = null)
    {
        $token = null;

        if (isset($response['statusCode']) &&
            $response['statusCode'] === 200) {
            $result = $response['result'];

            $detail = $result['paymentMethod'];

            $paymentMethod = (
                 $detail['type'] === PaymentMethod::APM
                ? new APM($detail)
                : new ObfuscatedCard($detail)
            );

            $token = (new self($result['token'], $result['reusable']))
                ->withPaymentMethod($paymentMethod)
                ->withClientKey($clientKey);
        }

        return $token;
    }

    /**
     * Token with Payment Method
     *
     * @param int $amount
     *
     * @return self
     */
    public function withPaymentMethod(PaymentMethodInterface $paymentMethod)
    {
        $clone = clone $this;
        $clone->paymentMethod = $paymentMethod;

        return $clone;
    }

    /**
     * Token with Client Key
     *
     * @param string $clientKey
     *
     * @return self
     */
    public function withClientKey($clientKey)
    {
        $clone = clone $this;
        $clone->clientKey = $clientKey;

        return $clone;
    }

    /**
     * Get Payment Method
     *
     * @return PaymentMethodInterface
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Get Token UUID
     *
     * @return string
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Get Token Reusable State
     *
     * @return bool
     */
    public function isReusable()
    {
        return $this->reusable;
    }

    /**
     * Get Token Parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return [
            'reusable' => $this->isReusable(),
            'paymentMethod' => $this->paymentMethod->getParameters(),
            'clientKey' => $this->clientKey
        ];
    }
}
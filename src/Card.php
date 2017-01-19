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
 * Card Payment Method
 *
 * Create an card payment method.
 */
class Card extends AbstractCard
{
    /**
     * Payment Method Type
     *
     * @var string
     */
    protected $type = PaymentMethod::CARD;

    /**
     * Number of the card to be charged
     *
     * @example '4444333322221111'
     *
     * @var string
     */
    protected $cardNumber;

    /**
     * Security code of the card to be charged.
     *
     * This is also known as CVV or CV2. It is the 3-digit number
     * at the back of the card. In the case of Amex, the 4-digit
     * number at the front of the card
     *
     * @example '123'
     *
     * @var string
     */
    protected $cvc;

    /**
     * Required payment method parameters
     *
     * @var array
     */
    protected static $required = [
        'name',
        'expiryMonth',
        'expiryYear',
        'cardNumber',
        'cvc'
    ];

    /**
     * Create New Card
     *
     * @param array $params
     *
     * @return PaymentMethodInterface
     */
    public function __construct(array $params)
    {
        if (isset($params['billingAddress'])) {
            $params['billingAddress'] = new Address($params['billingAddress']);
        }

        $params['type'] = $this->type;

        $this->setParameters($params);
    }

    /**
     * Get Card Number
     *
     * @var string
     */
    public function getNumber()
    {
        return $this->cardNumber;
    }

    /**
     * Get Card Security Code
     *
     * @var string
     */
    public function getCVC()
    {
        return $this->cvc;
    }
}
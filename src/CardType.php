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

use ReflectionClass;

/**
 * Worldpay Card Types (cardType)
 *
 * Worldpay has the following supported card types
 */
class CardType
{
    /**
     * Visa Credit
     *
     * @var string
     */
    const VISA_CREDIT = 'VISA_CREDIT';

    /**
     * Visa Debit
     *
     * @var string
     */
    const VISA_DEBIT = 'VISA_DEBIT';

    /**
     * Visa Corporate Credit
     *
     * @var string
     */
    const VISA_CORPORATE_CREDIT = 'VISA_CORPORATE_CREDIT';

    /**
     * Visa Corporate Debit
     *
     * @var string
     */
    const VISA_CORPORATE_DEBIT = 'VISA_CORPORATE_DEBIT';

    /**
     * Mastercard Credit
     *
     * @var string
     */
    const MASTERCARD_CREDIT = 'MASTERCARD_CREDIT';

    /**
     * Mastercard Debit
     *
     * @var string
     */
    const MASTERCARD_DEBIT = 'MASTERCARD_DEBIT';

    /**
     * Mastercard Corporate Credit
     *
     * @var string
     */
    const MASTERCARD_CORPORATE_CREDIT = 'MASTERCARD_CORPORATE_CREDIT';

    /**
     * Mastercard Corporate Debit
     *
     * @var string
     */
    const MASTERCARD_CORPORATE_DEBIT = 'MASTERCARD_CORPORATE_DEBIT';

    /**
     * Maestro
     *
     * @var string
     */
    const MAESTRO = 'MAESTRO';

    /**
     * American Express
     *
     * @var string
     */
    const AMEX = 'AMEX';

    /**
     * Cartebleue
     *
     * @var string
     */
    const CARTEBLEUE = 'CARTEBLEUE';

    /**
     * JCB
     *
     * @var string
     */
    const JCB = 'JCB';

    /**
     * Diners
     *
     * @var string
     */
    const DINERS = 'DINERS';

    /**
     * Get List of Card Types
     *
     * @return array
     */
    static function getConstants()
    {
        $class = new ReflectionClass(__CLASS__);

        return $class->getConstants();
    }
}
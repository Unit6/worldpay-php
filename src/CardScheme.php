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
 * Worldpay Card Scheme (cardSchemeType, cardSchemeName)
 *
 * Worldpay has the following supported card schemes.
 */
class CardScheme
{
    /**
     * Card Scheme Type Consumer
     *
     * @var string
     */
    const TYPE_CONSUMER = 'consumer';

    /**
     * Card Scheme Type Corporate
     *
     * @var string
     */
    const TYPE_CORPORATE = 'corporate';

    /**
     * Card Scheme Name: Visa Credit
     *
     * @var string
     */
    const NAME_VISA_CREDIT = 'VISA CREDIT';
    /**
     * Card Scheme Name: Visa Debit
     *
     * @var string
     */
    const NAME_VISA_DEBIT = 'VISA DEBIT';
    /**
     * Card Scheme Name: Mastercard Credit
     *
     * @var string
     */
    const NAME_MCI_CREDIT = 'MASTERCARD CREDIT';
    /**
     * Card Scheme Name: Mastercard Debit
     *
     * @var string
     */
    const NAME_MCI_DEBIT = 'MASTERCARD DEBIT';
    /**
     * Card Scheme Name: Maestro
     *
     * @var string
     */
    const NAME_MAESTRO = 'MAESTRO';

    /**
     * Card Scheme Name: Electron
     *
     * Accepted for UK merchants, processed as a debit card
     * transaction in GBP only.
     *
     * @var string
     */
    const NAME_ELECTRON = 'ELECTRON';

    /**
     * Get List of Card Schemes
     *
     * @return array
     */
    static function getConstants()
    {
        $class = new ReflectionClass(__CLASS__);

        return $class->getConstants();
    }
}
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
 * Worldpay Payment Methods
 */
class PaymentMethod
{
    /**
     * Alternative Payment Method
     *
     * @var string
     */
    const APM = 'APM';
    /**
     * Card
     *
     * @var string
     */
    const CARD = 'Card';
    /**
     * Obfuscated Card for Repsonses
     *
     * @var string
     */
    const OBFUSCATED_CARD = 'ObfuscatedCard';

    /**
     * Get List of Methods
     *
     * @return array
     */
    public static function getConstants()
    {
        $class = new ReflectionClass(__CLASS__);

        return $class->getConstants();
    }
}
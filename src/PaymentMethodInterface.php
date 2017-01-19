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
 * Payment Method Interface
 *
 * Defines the interface for a payment method.
 */
interface PaymentMethodInterface
{
    /**
     * Get Payment Method Type
     *
     * @return string
     */
    public function getType();

    /**
     * Get order parameters
     *
     * @return array
     */
    public function getParameters();

    /**
     * Get order JSON
     *
     * @return string
     */
    public function __toString();
}
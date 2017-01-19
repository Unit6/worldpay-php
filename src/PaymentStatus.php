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
 * Worldpay Order States (paymentStatus)
 *
 * Worldpay uses the following Order States
 *
 * @see https://online.worldpay.com/support/articles/what-do-the-different-order-states-mean
 */
class PaymentStatus
{
    /**
     * SUCCESS
     *
     * The card issuer/payment service has approved the
     * payment, reserving the payment amount for the merchant.
     *
     * @var string
     */
    const SUCCESS = 'SUCCESS';

    /**
     * FAILED
     *
     * There was a problem with the payment, eg card details
     * were not valid, the shopper could not authenticate the
     * payment, or there were insufficient funds in the
     * customer's account.
     *
     * @var string
     */
    const FAILED = 'FAILED';

    /**
     * SENT_FOR_REFUND
     *
     * When you request a refund or partial refund various checks
     * are performed e.g. on the credit status of the account,
     * and while these checks are ongoing the order will be
     * in this transition state.
     *
     * When the refund is complete the order will transition to
     * REFUNDED or PARTIALLY_REFUNDED as appropriate. In the
     * unlikely event that there is a problem with the refund,
     * we will return the order to its former state, and inform
     * you via email that the refund failed.
     *
     * @var string
     */
    const SENT_FOR_REFUND = 'SENT_FOR_REFUND';

    /**
     * REFUNDED
     *
     * The order has been wholly refunded to the customer.
     *
     * Shown if you issued a refund – either in response to a
     * customer request, or if you decided not to proceed, for
     * example when goods or services are no longer available.
     *
     * Note: If you refund a payment very shortly after it has
     * been placed (usually within ten minutes) it may appear
     * as REFUNDED. However this is not technically classed as
     * a refund because the payment was never made, so you will
     * not be charged any fees for the transaction.
     *
     * @var string
     */
    const REFUNDED = 'REFUNDED';

    /**
     * PARTIALLY_REFUNDED
     *
     * Part of the order value has been refunded to the customer.
     *
     * If you issued a partial refund - perhaps you could only
     * fulfil part of an order, eg if one of the goods or services
     * were no longer available, but the rest of the order was
     * delivered.
     *
     * @var string
     */
    const PARTIALLY_REFUNDED = 'PARTIALLY_REFUNDED';

    /**
     * PRE_AUTHORIZED
     *
     * Used as part of a 3-D Secure transaction where the card
     * has been enrolled by the card issuer. The transaction
     * still needs to be AUTHORIZED.
     *
     * @var string
     */
    const PRE_AUTHORIZED = 'PRE_AUTHORIZED';

    /**
     * AUTHORIZED
     *
     * The card issuer/payment service has authorised the order,
     * and the funds are now ring-fenced on the customer account,
     * awaiting capture to take payment.
     *
     * @var string
     */
    const AUTHORIZED = 'AUTHORIZED';

    /**
     * CANCELLED
     *
     * The order has been cancelled by the merchant, and the
     * formerly ring-fenced funds are now released on the
     * customer account.
     *
     * @var string
     */
    const CANCELLED = 'CANCELLED';

    /**
     * EXPIRED
     *
     * The authorised order has expired, and the formerly
     * ring-fenced funds are now released on the customer account.
     *
     * @var string
     */
    const EXPIRED = 'EXPIRED';

    /**
     * SETTLED
     *
     * The payment has been received from the card issuer/payment
     * service and has been included in a settlement transfer
     * to your bank.
     *
     * @var string
     */
    const SETTLED = 'SETTLED';

    /**
     * CHARGED_BACK
     *
     * Your customer disputed this order and the money was return.
     *
     * If a customer did not recognise a payment and complained
     * to his card issuer/payment service, the card issuer/payment
     * service may reverse the payment, and the order will appear
     * as CHARGED_BACK.
     *
     * @var string
     */
    const CHARGED_BACK = 'CHARGED_BACK';

    /**
     * INFORMATION_REQUESTED
     *
     * When Worldpay decides to dispute a charge-back, we will
     * ask you for some additional information.
     *
     * If Worldpay are notified of a disputed payment that
     * can be contested, we will ask you (the merchant) to
     * provide additional information (such as a delivery note,
     * proof of purchase, etc) so that you can try to avoid
     * incurring a chargeback. The order status in this case
     * will be INFORMATION_REQUESTED.
     *
     * @var string
     */
    const INFORMATION_REQUESTED = 'INFORMATION_REQUESTED';

    /**
     * INFORMATION_SUPPLIED
     *
     * This is the order state after you have supplied us
     * with information to dispute a charge-back.
     *
     * @var string
     */
    const INFORMATION_SUPPLIED = 'INFORMATION_SUPPLIED';

    /**
     * Get List of Statuses
     *
     * @return array
     */
    static function getConstants()
    {
        $class = new ReflectionClass(__CLASS__);

        return $class->getConstants();
    }
}
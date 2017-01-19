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
 * Address Class
 *
 * Create an address for modelling a billingAddress or deliveryAddress.
 *
 * billingAddress:  JSON, Optional
 *      Object containing the address where the customer's card is
 *      registered. This object will be used for AVS address
 *      verification and other fraud checks, and is strongly
 *      recommended to be set. If any of (address1, city,
 *      postalCode or countryCode) are unset this object will be
 *      silently ignored. In the request this object is passed
 *      at the top-level of the JSON. In the response it will
 *      be embedded in paymentResponse
 *
 * deliveryAddress:    JSON, Optional
 *      Object containing the address where the goods/services are
 *      to be delivered/invoiced. This object will be used for
 *      fraud checks, and is strongly recommended to be set. If
 *      any of (address1, city, postalCode or countryCode) are
 *      unspecified this object will be silently ignored
 */
class Address extends AbstractResource
{
    /**
     * Address Required Fields
     *
     * Note: These attributes must be specified and non-null
     * for an address to be valid.
     *
     * @var array
     */
    public static $required = [
        'address1',
        'postalCode',
        'city',
        'countryCode',
    ];

    /**
     * The first line of the address.
     *
     * Note: This attribute must be specified and non-null
     * for an address to be valid.
     *
     * @var string
     */
    protected $address1;

    /**
     * The second line of the address
     *
     * @var string
     */
    protected $address2;

    /**
     * The third line of the address
     *
     * @var string
     */
    protected $address3;

    /**
     * The postcode or ZIP code.
     *
     * @var string
     */
    protected $postalCode;

    /**
     * The postal town or city.
     *
     * @var string
     */
    protected $city;

    /**
     * Subdivision of a country
     *
     * @example Schleswig-Holstein, Queensland
     *
     * @var string
     */
    protected $state;

    /**
     * The ISO 3166 alpha-2 2-letter country code.
     *
     * @var string
     */
    protected $countryCode;

    /**
     * Address Country
     *
     * @var Country
     */
    protected $country;

    /**
     * Creating an Address
     *
     * @param array $params Address values.
     *
     * @return Address
     */
    public function __construct(array $params)
    {
        $this->setParameters($params);

        $this->country = new Country($params['countryCode']);
    }

    /**
     * Get token parameters
     *
     * @return array
     */
    public function getParameters()
    {
        $address = [];

        foreach ($this->params as $key => $value) {
            if (empty($value)) {
                continue;
            }

            $address[$key] = $value;
        }

        return $address;
    }
}
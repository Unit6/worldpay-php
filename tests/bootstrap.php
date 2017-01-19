<?php
/*
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// set the default timezone
date_default_timezone_set('UTC');

$path = dirname(__FILE__);

require realpath($path . '/../autoload.php');
require realpath($path . '/../vendor/autoload.php');

function getKeys($path)
{
    if ( ! is_readable($path)) {
        throw new UnexpectedValueException('Worldpay test client and service keys required: ' . $location);
    }

    $data = [];

    foreach (file($path) as $line) {
        list($key, $value) = explode(':', $line);
        $data[$key] = trim($value);
    }

    return $data;
};

$keys = getKeys($path . '/../example/.keys');

define('SERVICE_KEY', $keys['serviceKey']);
define('CLIENT_KEY', $keys['clientKey']);

define('AMOUNT', 500);
define('AMOUNT_DECIMAL', '5.00');

define('CURRENCY_CODE', 'GBP');
define('CURRENCY_EXPONENT', 2);
define('CURRENCY_NAME', 'Pound Sterling');

define('ADDRESS_1', '18 Linver Road');
define('ADDRESS_2', '');
define('ADDRESS_3', 'Fulham');
define('POSTAL_CODE', 'SW6 3RB');
define('CITY', 'London');
define('STATE', '');

define('COUNTRY_CODE', 'GB');
define('COUNTRY_NAME', 'United Kingdom');

define('SHOPPER_EMAIL', 'j.smith@example.com');
define('SHOPPER_SESSION_ID', 'af49c354-8263-4747-80dd-acb1b14db1d2');
define('SHOPPER_IP', '127.0.0.1');
define('SHOPPER_USER_AGENT', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36');
define('SHOPPER_ACCEPT_HEADER', 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8');

define('DOCUMENT_NAME', 'black.gif');
define('DOCUMENT_DATA', 'R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs=');

define('EVIDENCE_SIZE', 4000000);
define('EVIDENCE_INTERVAL', 600);

define('TOKEN_ID', '0015ac73-498b-4636-afce-b3f723b1b772');

define('CARD_NAME', 'John Smith');
define('CARD_EXPIRY_MONTH', '2');
define('CARD_EXPIRY_YEAR', '2015');
define('CARD_NUMBER', '4444 3333 2222 1111');
define('CARD_CVC', '123');

define('CARD_ISSUE_NUMBER', '2');
define('CARD_START_MONTH', '8');
define('CARD_START_YEAR', '2013');

define('CARD_TYPE', 'VISA_CREDIT');
define('CARD_NUMBER_MASKED', '**** **** **** 1111');
define('CARD_SCHEME_TYPE', 'consumer');
define('CARD_SCHEME_NAME', 'VISA CREDIT');
define('CARD_ISSUER', 'LLOYDS BANK PLC');
define('CARD_COUNTRY_CODE', 'GB');
define('CARD_CLASS', 'credit');
define('CARD_PRODUCT_NONC', 'Visa Credit Personal');
define('CARD_PRODUCT_CONT', 'CL Visa Credit Pers');
define('CARD_PREPAID', false);


define('PAYEE_NAME', 'John Smith');
define('APM_NAME', 'paypal');
define('SHOPPER_COUNTRY_CODE', 'GB');

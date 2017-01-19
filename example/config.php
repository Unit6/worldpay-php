<?php
/**
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// set the default timezone
date_default_timezone_set('UTC');

// create new session
session_start();

$path = dirname(__FILE__);

require realpath($path . '/../autoload.php');
require realpath($path . '/../vendor/autoload.php');

use Unit6\HTTP;
use Unit6\Worldpay;

function getKeys($path)
{
    $location = $path . '/.keys';

    if ( ! is_readable($location)) {
        throw new UnexpectedValueException('Worldpay test client and service keys required: ' . $location);
    }

    $data = [];

    foreach (file($location) as $line) {
        list($key, $value) = explode(':', $line);
        $data[$key] = trim($value);
    }

    return $data;
};

$keys = getKeys($path);

if ( ! isset($keys['serviceKey']) || strpos($keys['serviceKey'], 'T_S_') !== 0) {
    throw new UnexpectedValueException('Worldpay test service keys are usually prefixed with "T_S_"');
}

if ( ! isset($keys['clientKey']) || strpos($keys['clientKey'], 'T_C_') !== 0) {
    throw new UnexpectedValueException('Worldpay test client keys are usually prefixed with "T_C_"');
}

$client = new Worldpay\Client($keys);

$request = HTTP\Environment::getRequest();

$input = $request->getParsedBody();

$pages = [
    'create_order' => 'Create Order',
    'create_order_cof' => 'Create Order (CardOnFile)',
    'create_order_apm' => 'Create Order with APM',
    'authorized_order' => 'Authorized Order',
    'refund_order' => 'Refund Order',
    'stored_card' => 'Stored Card',
    'disputed_order' => 'Defend a Disputed Order',
    'get_order' => 'Get Order Details',
    'order_search' => 'Search Orders'
];

$page = null;

if (isset($_GET['page']) && isset($pages[$_GET['page']])) {
    $page = sprintf('pages/%s.php', $_GET['page']);
    if ( ! is_readable($page)) {
        exit('Example page not found: ' . $page);
    }
}


$uri = $request->getURI();

#var_dump((string) $uri); exit;

// parse ngrok URI, if detected.
if (strpos($uri, '.ngrok')) {
    // php -S 127.0.0.1:8000 example/ && ngrok 8000
    // ngrok sends listening port, fix it here.
    $uri = new HTTP\URI($uri->getScheme(), $uri->getHost());
    $rootURL = rtrim($uri, '/');
} else { // local usage may required this.
    list($uri) = explode('/example', (string) $request->getUri());
    $rootURL = $uri . '/example';
}

#var_dump($rootURL); exit;

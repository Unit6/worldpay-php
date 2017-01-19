<?php
/**
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require '../config.php';

use Unit6\HTTP\Client\Response;

#var_dump($input); exit;

$input = array_filter($input);

if ( ! isset($input['pageNumber'])) {
    $input['pageNumber'] = 0;
}

$input['csv'] = (isset($input['csv']) ? 'true' : 'false');

if ($input['csv'] === 'true') {
    $response = $client->getOrders($input);

    if ( ! ($response instanceof Response)) {
        throw new UnexpectedValueException('Orders is not Response');
    }

    $body = $response->getBody();
    $disposition = $response->getHeaderLine('Content-Disposition');
    $type = $response->getHeaderLine('Content-Type');

    header('Content-Type: ' . $type);
    header('Content-Disposition: ' . $disposition);
    header('Cache-Control: max-age=0');
    header('Content-Length: ' . $body->getSize());
    echo $body->getContents();
    exit;
}

$url = $rootURL . '/?page=order_search&' . http_build_query($input);

header('Location: ' . $url);
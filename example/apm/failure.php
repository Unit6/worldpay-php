<?php
/**
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

echo '<h2>Failed</h2>';
echo '<p>There was an error authorising the APM Order:</p><br/>';
echo '<code>' . $_GET['orderCode'] . '</code>';

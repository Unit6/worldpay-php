<?php
/**
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

    session_start();

    $data = $_SESSION['3DS'];
?>
<form id="submitForm" method="post" action="<?php echo $data['IssuerUrl']; ?>">
    <input type="hidden" name="PaReq" value="<?php echo $data['PaReq']; ?>"/>
    <input type="hidden" name="TermUrl" value="<?php echo $data['TermUrl']; ?>"/>
    <input type="hidden" name="MD" value="<?php echo $data['MD']; ?>"/>
    <script>document.getElementById('submitForm').submit();</script>
</form>
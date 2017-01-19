<?php
/**
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
?>
<h1 class="page-header">Disputed Order</h1>

<form id="dispute-form" enctype="multipart/form-data" method="post" action="actions/disputed_order.php">
    <!-- Maximum size is 4MB -->
    <input type="hidden" name="MAX_FILE_SIZE" value="4000000" />

    <h2>Upload Evidence</h2>

    <div class="form-group">
        <label for="order-code">Worldpay Order Code</label>
        <input type="text" class="form-control" id="order-code"
            name="orderCode"
            value="" />
        <p class="help-block">Documents can only be uploaded if the current order state is INFORMATION_REQUESTED or INFORMATION_SUPPLIED.</p>
    </div>

    <div class="form-group">
        <label for="evidence">Evidence</label>
        <input type="file" name="evidence" id="evidence">
        <p class="help-block">There are restrictions on uploading:<br/>
            - Maximum native file size per upload is 4MB or 5.33MB when base64 encoded<br/>
            - File types supported are : zip, doc, docx, jpg, jpeg, png, gif, tiff, pdf, txt<br/>
            - One upload every 10 minutes
        </p>
    </div>

    <div id="dispute-outcome" class="row"></div>

    <button type="submit" class="btn btn-primary" id="dispute-submit">Upload Evidence</button>
</form>
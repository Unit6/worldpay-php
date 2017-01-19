<?php
/**
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

    use Unit6\Worldpay\PaymentStatus;
    use Unit6\Worldpay\Order;

    $statuses = PaymentStatus::getConstants();
    $sortProperties = Order::$sortProperties;

    $params = $_GET;
    unset($params['page']);

    $results = null;
    if (count($params)) {
        $results = $client->getOrders($params);
    }
?>
<h1 class="page-header">Search for Orders</h1>

<form method="post" action="actions/order_search.php">

    <h2>Order Information</h2>

    <div class="row">
        <div class="form-group col-xs-3">
            <label for="environment">Environment</label>
             <select class="form-control" id="environment"
                name="environment">
                <option value="TEST">Test</option>
                <option value="LIVE">Live</option>
            </select>
            <p class="help-block">Order environment for request.</p>
        </div>
        <div class="form-group col-xs-3">
            <label for="environment">Payment Status</label>
             <select class="form-control" id="environment"
                name="paymentStatus">
                <?php foreach ($statuses as $key => $value): ?>
                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                <?php endforeach; ?>
            </select>
            <p class="help-block">State of the orders you want to retrieve.</p>
        </div>
        <div class="form-group col-xs-3">
            <label for="from-date">From Date</label>
            <input type="date" class="form-control" id="from-date"
                name="fromDate"
                value="2016-01-01" />
            <p class="help-block">Start of the date range you want to retrieve orders for as yyyy-mm-dd.</p>
        </div>

        <div class="form-group col-xs-3">
            <label for="to-date">To Date</label>
            <input type="date" class="form-control" id="to-date"
                name="toDate"
                value="2016-12-31" />
            <p class="help-block">End of the date range you want to retrieve orders for as yyyy-mm-dd.</p>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-3">
            <label for="sort-property">Sort Property</label>
             <select class="form-control" id="sort-property"
                name="sortProperty">
                <option value=""></option>
                <?php foreach ($sortProperties as $field): ?>
                <option><?php echo $field; ?></option>
                <?php endforeach; ?>
            </select>
            <p class="help-block">Date to sort with.</p>
        </div>
        <div class="form-group col-xs-3">
            <label for="sort-direction">Sort Direction</label>
             <select class="form-control" id="sort-direction"
                name="sortDirection">
                <option value="desc">Descending</option>
                <option value="asc">Ascending</option>
            </select>
            <p class="help-block">List of orders to be sorted in ascending or descending order. Default is descending.</p>
        </div>
        <div class="form-group col-xs-3">
            <label for="page-number">Page Number</label>
            <input type="number" class="form-control" id="page-number"
                name="pageNumber"
                value="0" />
            <p class="help-block">Specify which page you want to retrieve. Note: the first page is page 0.
        </div>
        <div class="form-group col-xs-3">
            <label for="csv">Comma Separated Values (CSV)</label>
            <div class="checkbox">
                <label><input type="checkbox" id="csv"
                    name="csv">Export</label>
            </div>
            <p class="help-block">Receive the results as a CSV file or not. </p>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Get Orders</button>
</form>
<?php if ($results): ?>
<table class="table">
    <caption>Optional table caption.</caption>
    <thead>
        <tr>
            <th>#</th>
            <th>orderCode</th>
            <th>orderDescription</th>
            <th>amount</th>
            <th>paymentStatus</th>
            <th>creationDate</th>
            <th>modificationDate</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results['orders'] as $i => $row): $num = ($results['currentPageNumber'] * $results['numberOfElements']) + ($i + 1); ?>
        <tr>
            <th scope="row"><?php echo $num; ?></th>
            <td><?php echo $row['orderCode']; ?></td>
            <td><?php echo $row['orderDescription']; ?></td>
            <td><?php echo $row['amount']; ?></td>
            <td><?php echo $row['paymentStatus']; ?></td>
            <td><?php echo date('Y-m-d H:i:s', $row['creationDate'] / 1000); ?></td>
            <td><?php echo date('Y-m-d H:i:s', $row['modificationDate'] / 1000); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<nav>
    <ul class="pagination">
        <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
        <?php for ($i = 0; $i < $results['totalPages']; $i++):
            $num = $i + 1;
            $uri = '.' . preg_replace('/pageNumber=([\d]+)/', 'pageNumber=' . $i, $_SERVER['REQUEST_URI']);
        ?>
        <?php if ($i === $results['currentPageNumber']): ?>
        <li class="active"><a href="<?php echo $uri; ?>"><?php echo $num; ?> <span class="sr-only">(current)</span></a></li>
        <?php else: ?>
        <li><a href="<?php echo $uri; ?>"><?php echo $num; ?></a></li>
        <?php endif;?>
        <?php endfor;?>
        <li><a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
    </ul>
</nav>
<?php endif; ?>
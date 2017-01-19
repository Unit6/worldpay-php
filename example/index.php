<?php
/**
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

    require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Worldpay Example</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="./style.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdn.worldpay.com/v1/worldpay.js"></script>
</head>
<body>
    <div id="wrapper">
        <div class="overlay"></div>

        <!-- Sidebar -->
        <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
            <ul class="nav sidebar-nav">
                <li class="sidebar-brand">unit6/worldpay</li>
                <?php foreach ( $pages as $slug => $title): ?>
                <li><a href="?page=<?php echo $slug; ?>"><?php echo $title; ?></a></li>
                <?php endforeach;?>
                <!--
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Works <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li class="dropdown-header">Dropdown heading</li>
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
                -->
            </ul>
        </nav>
        <!-- #sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <button type="button" class="hamburger is-closed" data-toggle="offcanvas">
                <span class="hamb-top"></span>
                <span class="hamb-middle"></span>
                <span class="hamb-bottom"></span>
            </button>
            <div class="container">
                <?php if ($page) require $page; ?>

                <footer class="row">
                    <hr />
                    <p class="pull-right"><a href="http://www.unit6websites.com/"
                    title="Unit6"
                    target="blank">unit6websites.com</a></p>
                    <p><a href="https://online.worldpay.com/api"
                    title="Open Worldpay API Reference"
                    target="blank">Worldpay API Reference</a></p>
                </footer>
            </div>
        </div>
        <!-- #page-content-wrapper -->

    </div>
    <!-- #wrapper -->
    <script type="text/javascript">
        $(document).ready(function () {
            var hamburger = $('.hamburger');
            var overlay = $('.overlay');
            var isClosed = false;

            hamburger.on('click', function () {
                if (isClosed == true) {
                    overlay.hide();
                    hamburger.removeClass('is-open');
                    hamburger.addClass('is-closed');
                    isClosed = false;
                } else {
                    overlay.show();
                    hamburger.removeClass('is-closed');
                    hamburger.addClass('is-open');
                    isClosed = true;
                }
            });

            $('[data-toggle="offcanvas"]').on('click', function () {
                $('#wrapper').toggleClass('toggled');
            });
        });
    </script>
</body>
</html>
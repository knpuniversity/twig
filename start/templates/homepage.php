<?php
/**
 * An example PHP template. Go to test.php in your browser to see this
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $pageTitle ?> | Penguin Pants Plus!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
    </style>
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="shortcut icon" href="favicon.ico">
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="brand" href="#">Penguin Pants Plus!</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="about">About</a></li>
                    <li><a href="contact">Contact</a></li>
                </ul>
                <form class="navbar-form pull-right">
                    <input class="span2" type="text" placeholder="Email">
                    <input class="span2" type="password" placeholder="Password">
                    <button type="submit" class="btn">Sign in</button>
                </form>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">

    <!-- Main hero unit for a primary marketing message or call to action -->
    <div class="hero-unit">
        <h1><?php echo $pageTitle ?></h1>
        <p>
            You're hip, you're cool, you're a penguin! Now, start dressing like one! Find the latest suits, bow-ties, swim shorts
            and other outfits here!
        </p>
    </div>

    <!-- Example row of columns -->
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="span4">
                <h2><?php echo $product->getName() ?></h2>
                <div class="product-img">
                    <img src="assets/images/<?php echo $product->getImagePath() ?>" class="img-rounded" />
                </div>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                <p><a class="btn" href="#">View details &raquo;</a></p>
            </div>
        <?php endforeach; ?>
    </div>

    <hr>

    <footer>
        <p>&copy; Penguin Pants Plus! 2013</p>
    </footer>

</div> <!-- /container -->

<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="assets/js/jquery.js"></script>

</body>
</html>

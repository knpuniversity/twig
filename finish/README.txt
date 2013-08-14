Twig for Frontend Friends!
==========================

This directory holds the finished product of the screencast.

To get it working, move this directory somewhere under your web server's
document root. For example, if you renamed "finish" to "twig" and copied
directly onder your web root, then you can access things via:

    http://localhost/twig/index.php/
    http://localhost/twig/index.php/contact

You'll need to make sure that PHP 5.3 is installed and configured for your
web server.

Playing Around
--------------

If you want to add some new templates, you can do that! For example, if you
create a templates/play.twig file, then you can access it automatically at:

    http://localhost/twig/index.php/play

All of this is setup in the index.php file. You can also tweak the setup in
that file if you'd like. By default, if you create a new page, the only variable
passed to the Twig template is a `title` variable.

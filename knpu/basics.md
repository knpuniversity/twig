# Twig: The Basics

Welcome to the world of Twig! Twig is a templating language for PHP, which
is a boring way of saying that it's a tool used to output variables inside
HTML. If a project you're working on uses Twig, then you're in luck: it's
easy to learn, powerful and a joy to work with.

To make this interesting, let's build something useful with Twig like a penguin
clothing store! Actually, I've already got us started. I have a small website
setup under my web server's document root and a test page called `test.php`.

***TIP
Want to run this code locally? Download the code from this page, then following
the directions in the `README.md` file inside.
***

Right now, this file prepares some `pageTitle` and `products` variables
and then includes another file:

```php
// test.php

// setup some variables
$pageTitle = 'Suit Up!';
$products = array(
    new Product('Serious Businessman', 'formal.png'),
    new Product('Penguin Dress', 'dress.png'),
    new Product('Sportstar Penguin', 'sports.png'),
    new Product('Angel Costume', 'angel-costume.png'),
    new Product('Penguin Accessories', 'swatter.png'),
    new Product('Super Cool Penguin', 'super-cool.png'),
);

// render out PHP template
include __DIR__.'/templates/homepage.php';
```

The `homepage.php` file is the actual template. It has all the HTML and
we use `foreach` to loop through them and then `echo` to print out some
variables:

```html+php
<!-- templates/homepage.php -->

<!-- ... the rest of the HTML page ... -->
<?php foreach ($products as $product) : ?>
    <div class="span4">
        <h2><?php echo $product->getName() ?></h2>
        <!-- ... -->
    </div>
<?php endforeach; ?>
```

Instead of using PHP, let's write our template using Twig! The goal of the
template is still the same: to print out variables. The only thing that will
change is the syntax.

## Setting up Twig

In a separate file, I've setup all the behind-the-scenes work to use Twig.
Let's start by rendering a `homepage.twig` file and once again passing it
`pageTitle` and `products` variables:

```php
// index.php
// ... code that sets up Twig, and says to look for templates in template/

echo $twig->render('homepage.twig', array(
    'pageTitle' => 'Welcome to Penguins R Us!',
    'products' => array(
        'Tuxedo',
        'Bow tie',
        'Black Boxers',
    ),
));
```

> If you're curious how you actually setup Twig, check out the code download
> and see the [Twig Installation][installation] documentation.

If you're a frontend developer, then you don't need to worry about this step:
all you need to know is where a Twig template is located and what variables
you have access to.

## Your first Twig Template

In our project, Twig is looking for the template files in a `templates/`
directory, so let's create our `homepage.twig` there!

Just like in PHP, you can write anything and it'll just be displayed as HTML
on the page:

```html
<!-- templates/homepage.twig -->
Hello Twig Viewers!
```

To see this amazing message, go to the `index.php` file in your browser.
This works because we made the `index.php` file render the `homepage.twig`
template. Whenever you're creating or editing a page, you'll need to figure
out which Twig template is being used for that page. There's no exact science
to this and it depends on how your application is built.

## Rendering a Variable

Remember that we're passing a `pageTitle` variable to our template. To render
it, write two opening curly braces, the name of the variable without a dollar
sign, then two closing curly braces:

```html+jinja
<!-- templates/homepage.twig -->
<h1>{{ pageTitle }}</h1>
```

When we refresh the page, it works! We've just written our first line of Twig!
Whenever you want to print something, just open Twig with two curly braces,
write the variable name, then close Twig. We'll get fancier in a little while
with some things called [functions][twig_functions] and [filters][twig_filters],
but this is the most fundamental syntax in Twig.

## Looping over Variables

Next, the `products` variable is an array that we need to loop through.
Twig comes with a [for][for] tag that is able to loop through items just like
PHP's `foreach`.

Remember that anything we type here will be printed out raw on the page until
we "open up" Twig. This time, open Twig by typing `{%`. Now that we're in
Twig, use the `for` tag to loop over `products`. `product` will be the
variable name we use for each item as we loop. Close Twig by adding an identical
`%}`. Unlike when we echo'ed the `pageTitle` variable, the `for` tag
needs an `endfor`:

```html+jinja
<!-- templates/homepage.twig -->
<h1>{{ pageTitle }}</h1>

<div class="row">
    {% for product in products %}

    {% endfor %}
</div>
```

Twig will loop over each item in `products` and execute each line between
`for` and `endfor`. Each item in `products` is just a string, so let's
print it out:

```html+jinja
<!-- templates/homepage.twig -->
<h1>{{ pageTitle }}</h1>

<div class="row">
    {% for product in products %}
        <div class="span4">
            <h2>{{ product }}</h2>
        </div>
    {% endfor %}
</div>
```

This works exactly like before. We have a `product` variable, so we can
print it by placing it inside two opening curly braces and two closing curly
braces.

And when we refresh, another Twig success! Before long, we'll have these
penguins looking fly.

## The 2 Syntaxes of Twig: `{{` and `{%`

So we've seen how to print a variable and how to loop over a variable that's
an array or collection. This may not seem like much, but you've already seen
pretty much all of Twig's syntaxes! To start writing Twig code in your HTML,
there are only two different syntaxes:

* `{{     }}` [The "say something" syntax][say_something_syntax]
* `{%     %}` [The "do something" syntax][do_something_syntax]

### The "Say Something" Syntax: `{{ ... }}`

The double-curly-brace (`{{`) is always used to print something. If whatever you
need to do will result in something being printed to the screen, then you'll
use this syntax. I call this the "say something" tag, ya know, because it's
how you "speak" in Twig.

### The "Do Something" Syntax: `{% ... %}`

The curly-percent (`{%`) is the other syntax, which I call the "do something"
syntax. It's used for things like [if][if] and [for][for] tags as well as other things
that "do" something. The `{%` is really easy because there are only
a handful of things that can be used inside of it. If you go to Twig's website
click [Documentation][documentation], and scroll down, you can see a full list of everything
in Twig. The "tags" header shows you everything that can be used inside of
a "do something" tag, with more details about how each of these works. The
only ones you need to worry about now are [if][if] and [for][for]. We'll talk about
a bunch more of these later.

And that's it! Use the `{{` "say something" syntax to print and the `{%`
"do something" when you want to do one of the things on this list.
These are the only two Twig syntaxes and we'll learn more tools that can be
used inside of each of these.

### The Comment Syntax: `{# ... #}`

Actually, we've lied a little. There is a third syntax, used for comments:
`{#`. Just like with the "say something" and "do something" syntaxes, write
the opening `{#` and also the closing `#}` at the end of your comments:

```jinja
{# This template is really starting to get interesting ... #}
{# ... #}
```

***TIP
We'll use the `{# ... #}` syntax in the rest of this tutorial whenever
we're hiding some parts of a Twig template.
***

## Whitespace inside Twig

Inside Twig, whitespace doesn't matter. this means that we can add or remove
spaces whenever we want:

```html+jinja
{%for product    in      products%}
    <div class="span4">
        <h2>{{product}}</h2>
    </div>
{% endfor %}
```

Of course, this looks a bit uglier, so we usually keep just one space between
everything. Outside of Twig (in the final HTML), all the whitespace is kept
just like it appears. There are ways to make Twig [control the whitespace][twig_control_whitespace]
of your file, which we'll talk about later.

[installation]: http://twig.sensiolabs.org/doc/intro.html#installation
[for]: http://twig.sensiolabs.org/doc/tags/for.html
[if]: http://twig.sensiolabs.org/doc/tags/if.html
[documentation]: http://twig.sensiolabs.org/documentation
[twig_control_whitespace]: https://knpuniversity.com/screencast/twig/extra-credit-tricks-escaping#whitespace-control
[say_something_syntax]: https://knpuniversity.com/screencast/twig/basics#the-say-something-syntax-code-code
[do_something_syntax]: https://knpuniversity.com/screencast/twig/basics#the-do-something-syntax-code-code
[twig_functions]: https://knpuniversity.com/screencast/twig/functions-filters#using-a-function
[twig_filters]: https://knpuniversity.com/screencast/twig/functions-filters#using-filters

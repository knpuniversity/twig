# Using Objects and Array Keys

Until now, we've been working with simple values like `pageTitle` or `products`,
which is an array that contains simple values where we loop over and print
each out. Now, let's make things a bit more interesting!

## Using data from an Array

I'm going to pass in a new variable called `pageData`:

```php
// index.php
// ...

echo $twig->render('homepage.twig', array(
    'pageData' => array(
        'title'     => 'Suit Up!',
        'summary'   => "You're hip, you're cool, you're a penguin! Now, start dressing like one! Find the latest suits, bow-ties, swim shorts and other outfits here!",
        'hasSale'   => true,
    ),
    // ...
));
```

If you hadn't seen the PHP code I just used to create this variable, you
could use the handy [dump][dump] function to see that it's an array with a `title`,
`summary` and `hasSale` keys:

```jinja
{{ dump(pageData) }}
```

So how can we get to the data on the keys of the array?

The answer is with the almighty period (`.`). To print the `title`, just
say `pageData.title`. To print the summary, use the same trick!

```html+jinja
<div class="hero-unit">
    <h1>{{ pageData.title }}</h1>
    <p>
        {{ pageData.summary }}
    </p>
</div>
```

This can be used anywhere, like in an `if` statement in exactly the same way:

```html+jinja
{% if pageData.hasSale %}
    <div>We're having a sale!</div>
{% endif %}
```

So if you ever need data from an array, the `.` operator is your answer!

### The much-rarer `[]` Syntax

The `products` variable is also an array, but since it's a collection of
items, we loop over it with the [for][for] tag instead. But if we did need to
manually get the first item, or "zero" key from the array, we can do that.
If you're thinking that you would say `products.0`, you're right!

```jinja
{{ products.0 }}
```

You may sometimes see another syntax for getting items from an array:

```jinja
{{ products[0] }}
```

Don't let this confuse you - you almost *always* want to use the period.
The square bracket syntax is only needed in some uncommon cases when you need
to use a variable as the key:

```jinja
{{ products[random(5)] }}
```

***TIP
The `[]` is used if you want force getting the attribute off of an object
like an array, instead of trying to access the property. That's a very
rare case, so don't worry about it.
***

## Getting Data from an Object

I'm going to complicate things again by changing what the `products` variable
looks like. But first, use our friend the [dump][dump] function to see that `products`
is just a collection of strings right now:

```jinja
{{ dump(products) }}
```

Now, I'll change the `products` variable:

```php
// index.php
// ...

echo $twig->render('homepage.twig', array(
    // ...
    'products' => array(
        new Product('Serious Businessman', 'formal.png'),
        new Product('Penguin Dress', 'dress.png'),
        new Product('Sportstar Penguin', 'sports.png'),
        new Product('Angel Costume', 'angel-costume.png'),
        new Product('Penguin Accessories', 'swatter.png'),
        new Product('Super Cool Penguin', 'super-cool.png'),
    ),
));
```

After my change, refresh the page to see that `products` is now a collection
of `Product` objects. Each `Product` object has a `name` and `imagePath`
property.

If we don't change anything inside Twig, we'll get an error:

> Catchable fatal error: Object of class Product could not be converted
> to string in twig/vendor/twig/twig/lib/Twig/Environment.php(320) : eval()'d
> code on line 30

This means that we can print a string, but not an object. That makes sense.
Each Product object has `name` and `imagePath` properties, and we really
want to print those individually.

***TIP
If an object has a `__toString()` method, then it actually *can* be printed.
***

And guess what?! We can use the period character once again to do this! Even
though `pageData` is an array and each `product` is an object, getting
data off each is exactly the same:

```html+jinja
{% for product in products %}
    <div class="span4">
        <h2>{{ product.name }}</h2>
        <div class="product-img">
            <img src="assets/images/{{ product.imagePath }}" class="img-rounded" />
        </div>
    </div>
{% endfor %}
```

Refresh the page to see that our products have more details!

***TIP
In your project, you'll likely have a Twig function or variable that
you use when referring to static images, CSS or JS files. Check your
documentation to see.
***

Alright! By using the `dump()` function, we can see what a variable looks
like. We can print it, loop over it, or print a child key or property for
it. We're dangerous like a killer whale!

For the more technical folk, behind the scenes, Twig checks to see if the
Product class has a public `name` property. If the property doesn't exist
or isn't public, it looks for a `getName` method and calls it to get the
value. This lets us say `product.name` without really caring how the PHP
code for the class looks.

***TIP
You can also call a method on an object if you need to:
    
```jinja
{{ product.getName() }}
```
***

[dump]: http://twig.sensiolabs.org/doc/functions/dump.html
[for]: http://twig.sensiolabs.org/doc/tags/for.html

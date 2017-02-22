# Mistakes and Macros

Twig is a lot of fun to work with, and if you've made it this far, you know
a lot about it. In this chapter, let's make some mistakes and debug them.
We'll also talk about macros, which are like functions you create in Twig.

## Making Mistakes!

Like with anything, there are a lot of ways to make mistakes in Twig. Fortunately,
Twig usually gives you very clear errors and the line number the error is
on. We saw an error earlier when we [put some content outside of a block][twig_error_content_block]
tag.

Let's a see a few more common errors. Hopefully, some of these will seem
pretty obvious to you. First, putting different Twig syntaxes inside of each
other is definitely not allowed:

```html+jinja
{% if {{ pageTitle }} == 'Hello' %}

{% endif %}
```

When we refresh, Twig yells at us!

> A hash key must be a quoted string, a number, a name, or an expression
> enclosed in parentheses (unexpected token "punctuation" of value "{" in
> `homepage.twig` at line 10'

We now know that you open up Twig just once using `{{` or `{%` and then
you can write inside of it. What's tricky about this error message is the
"a hash key must be a quoted string" part. What the heck is a hash key?

Remember [from earlier][twig_include_hash_variables] that when we used
the [include()][include] function, we passed it a collection, or *hash* of variable
names and values. Whenever you're already inside Twig and you write a `{`
character, Twig thinks this is a hash. That's only really important because
I want you to be able to recognize that when you see a Twig error containing
the word "hash", it's talking about a curly-brace character.

Another common error is when we try to get some data off of a blank object.
Suppose that there's a `featuredProduct` variable that we have access to.
This is supposed to be a Product object, but pretend that someone has made
a mistake and `featureProduct` is actually blank!

```php
// index.php
// ...

echo $twig->render('homepage.twig', array(
    // ...
    'featuredProduct' => null,
));
```

When we try to print the `name` on it, we get a strange error.

> Item `name` for "" does not exist in `homepage.twig` at line 23

What it really means is that `featureProduct` is blank, so you can't try
to get its name of course! The empty double-quotes is a bit deceiving, but
when you see it, it means that you're trying to get something from a non-existent
object. In this case, we'd need to check our PHP code to see why this variable
is missing.

Overall, Twig works hard to give you very clear errors. Your errors will
almost *always* contain a line number where the problem is and a decent message.
For example, if we leave off certain characters or even add extra things,
Twig's messages always tell us exactly what's wrong.

## Macros

If you're printing out the same markup over and over again, you may find
it useful to write your own Twig functions. For example, let's pretend that
we're iterating over 2 different sets of products: `products` and `featureProducts`:

```php
// index.php
// ...

echo $twig->render('homepage.twig', array(
    // ...
    'products' => array(
        new Product('Serious Businessman', 'formal.png'),
        new Product('Penguin Dress', 'dress.png'),
        new Product('Sportstar Penguin', 'sports.png'),
    ),
    'featuredProducts' => array(
        new Product('Angel Costume', 'angel-costume.png'),
        new Product('Penguin Accessories', 'swatter.png'),
        new Product('Super Cool Penguin', 'super-cool.png'),
    ),
));
```

In `homepage.twig`, we certainly don't want to duplicate our `for` loop
and all the markup inside of it. Instead, let's create a macro, which is
just a Twig function!

Start by creating a new "do something" tag called "macro". Let's call our
macro `printProducts` and have it accept two arguments: the array of products
to print and the message in case there are no products. Add a closing `endmacro`
and then copy the `for` loop code from below. The only adjustment we need
to make is to print out the `emptyMessage` variable:

```html+jinja
{% macro printProducts(products, emptyMessage) %}
    {% for product in products %}
        <div class="span4">
            <h2>{{ product.name }}</h2>

            <div class="product-img">
                <img src="../assets/images/{{ product.imagePath }}" alt="{{ product.name }}"/>
            </div>
        </div>

        {% if loop.index is divisible by(3) and not loop.last %}
            </div><div class="row">
        {% endif %}
    {% else %}
        <div class="alert alert-error span12">
            {{ emptyMessage }}
        </div>
    {% endfor %}
{% endmacro %}
```

Ok! To use this, we call it just like any Twig function, except prefixed
with `_self.`:

```html+jinja
<div class="row">
    {{ _self.printProducts(
        products,
        "Oh now! We're all out of super-awesome penguin clothes!")
    }}
</div>
```

When we fresh, the first three products are printed perfectly! Now printing
out the featured products is very easy:

```html+jinja
<div class="row">
    {{ _self.printProducts(
        featuredProducts,
        "Snow storm in the arctic: nothing on sale today :/")
    }}
</div>
```

Macros can be a huge tool when you're building some reusable functionality.
In some ways, using a macro is similar to using the [include()][include] function. Both
allow you to move markup and logic into a separate place and then use it.
The biggest advantage of a macro is that it's very clear what variables you
need to pass to it.

But like with the [include()][include] function, macros can also live in totally different
files. Let's create a new `macros.twig` file and move it there:

```html+jinja
{# templates/macros.twig #}

{% macro printProducts(products, emptyMessage) %}
    {# ... #}
{% endmacro %}
```

To use the macro in `homepage.twig`, add an `imports` "do something" tag.
This tells Twig to "import" the macros from that file and make them available
as `myMacros`:

```html+jinja
{# templates/homepage.twig #}

{% import 'macros.twig' as myMacros %}
```

To use it, just change `_self` to `myMacros`:

```html+jinja
<div class="row">
    {{ myMacros.printProducts(
        featuredProducts,
        "Snow storm in the arctic: nothing on sale today :/")
    }}
</div>

<div class="row">
    {{ myMacros.printProducts(
        products,
        "Oh now! We're all out of super-awesome penguin clothes!")
    }}
</div>
```

***TIP
When we say `_self`, it's a way of referring to this very template.
***

[include]: http://twig.sensiolabs.org/doc/functions/include.html
[twig_error_content_block]: https://knpuniversity.com/screencast/twig/layout-template-inheritance#common-mistake-content-outside-of-a-block
[twig_include_hash_variables]: https://knpuniversity.com/screencast/twig/including-other-templates#passing-variables

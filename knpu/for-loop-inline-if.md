# The for "loop" and inline "if" Syntax

Let's give ourselves a challenge! Our products are printing out a bit weird
right now because they're floating but not breaking correctly. To fix this,
we need to wrap every three products in their very own `row` div.

To do this, we can use a [divisible by()][divisible_by] test to see if the item number
we're on is divisible by three:

```html+jinja
<div class="row">
    {% for product in products %}
        <div class="span4">
            {# ... #}
        </div>

        {% if loopNumber is divisible by(3) %}
            </div><div class="row">
        {% endif %}
    {% endfor %}
</div>
```

> Just like functions and filters, sometimes a "test" also takes one or
> more arguments.

When we refresh, Twig is sad because the `loopNumber` variable is undefined.
Yep, that's totally my fault, I made up that variable out of thin air. So
how *can* we figure out how many items into the loop we are?

### The Magical loop Variable

Twig comes to the rescue here and lets us say `loop.index`.

```html+jinja
{% for product in products %}
    {# ... #}

    {% if loop.index is divisible by(3) %}
        </div><div class="row">
    {% endif %}
{% endfor %}
```

When we refresh, things work awesomely! So where did this magical [loop][loop]
variable come from? Normally in Twig, we have access to a few variables that
were passed to us and that's it. If we use an undefined variable, we see
an error.

This is all 100% true. But when we're inside a [for][for] tag, we magically have
access to a new variable called [loop][loop]. `loop.first` and `loop.last`
tell us if this is the first or last item in the collection while `loop.index`
counts up 1, 2, 3, 4 and so on for each item. Twig has a lot of really slick
features like this, which you can find out by reading further into its docs.

In fact, to avoid an extra row being added if we have exactly 3, 6 or 9 objects,
let's *not* print a new `row` if we're on the last item:

```html+jinja
{% for product in products %}
    {# ... #}

    {% if loop.index is divisible by(3) and not loop.last %}
        </div><div class="row">
    {% endif %}
{% endfor %}
```

And not that it matters for Twig, but let's also move our "even" products message
into its own row where it belongs.

```html+jinja
{# templates/homepage.twig #}
{# ... after the for loop #}

{% if products|length is even %}
    <div class="row">
        <div class="span12">
            There is an even number of products! OMG!
        </div>
    </div>
{% endif %}
```

When we refresh, everything looks good and clean!

## The for-else tag

While we're talking about cool `for` loop features, let's see another one:
the `for-else` trick. Instead of seeing if `products` is empty, we can
add an `else` tag inside of the `for` loop.

```html+jinja
{% for product in products %}
    {# ... #}
{% else %}
    <div class="alert alert-error span12">
        It looks like we're out of really awesome-looking penguin clothes :/.
    </div>
{% endfor %}
```

If `products` is empty, it skips the `for` loop and calls the `else`
section instead. When we try it, it still works great.

## The inline if Syntax

Finally, let's see a really short syntax you can choose to use instead of
the classic `if` tag. Head back to the banner template where we're setting
the `backgroundColor` variable if it's not set and then printing it. Let's
remove all of this and instead put all the logic in the "say something" block:

```html+jinja
<div class="well" style="background-color: {{ backgroundColor is defined ? backgroundColor : 'lightBlue' }};">
    {# ... #}
</div>
```

You may be familiar with this syntax from another language, but if you're
not, don't worry! It looks odd, but is really easy. The first part is a condition
that returns true or false, just like an if statement. If it's true, the first
variable `backgroundColor` is printed. If it's false, the second string
`lightblue` is printed. The result is identical to before.

[loop]: http://twig.sensiolabs.org/doc/tags/for.html#the-loop-variable
[for]: http://twig.sensiolabs.org/doc/tags/for.html
[divisible_by]: http://twig.sensiolabs.org/doc/tests/divisibleby.html

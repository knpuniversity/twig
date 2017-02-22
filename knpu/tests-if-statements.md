# If Statements with "Tests"

But when we try the contact page, it blows up

> Uncaught exception `Twig_Error_Runtime` with message "Variable
> `backgroundColor` does not exist in `_banner.twig` at line 5"

We're not passing a `backgroundColor` in the `include` call in `contact.twig`,
so Twig gets angry! We can of course pass this variable to the template.
But instead, let's check to see if the variable is defined in the `_banner.twig`
template, and default to `lightblue` if it is not. To do this, we can use
an `if` statement and a special [defined][defined] test.

```html+jinja
{# templates/_banner.twig #}

{% if backgroundColor is defined %}
    {# ... do something here #}
{% endif %}
```

Let's also see another new Twig tag called [set][set], which sets a new variable.

```html+jinja
{# templates/_banner.twig #}

{% if backgroundColor is defined %}
    {% set backgroundColor = backgroundColor %}
{% else %}
    {% set backgroundColor = 'lightblue' %}
{% endif %}

<div class="well" style="background-color: {{ backgroundColor }};">
    <h3>Save some Krill!</h3>

    <p>Sale in summer suits all this weekend! {{ pageData.title }}</p>
</div>
```

Try out both pages in the browser. Awesomesauce!

Let's look again at the `if background is defined`. Normally an `if`
statement says something like `if foo != 'bar'` or `if isPublished == true`.
That all works totally fine in Twig.

But in addition to `==` and `!=` and the others, you can say `is` and follow that
by a word like [defined][defined], [even][even], [odd][odd], [empty][empty] or several
other words. These are called tests, and they're listed once again right
back on the main Documentation page of the Twig website.

***SEEALSO
A list of all of the operators (e.g. `/`, `*`, `==`, etc) can be found on
the [Twig Documentation][twig_documentation].
***

For example, instead of using the `length` filter and seeing if the number
of items in the `products` collection is zero, we could say `if products is empty`:

```html+jinja
{# template/homepage.twig #}
{# ... #}

{% if products is empty %}
    <div class="alert alert-error span12">
        It looks like we're out of really awesome-looking penguin clothes :/.
    </div>
{% endif %}
```

If for some reason we wanted to know if the total number of products were even,
we could use the [length][length] filter to get the number, then check that the
number is "even" by using the [even][even] test:

```html+jinja
{% if products|length is even %}
    <p>
        There are an even number of products
    </p>
{% endif %}
```

The tests are easy to use and can shorten the code needed to do some things,
so don't forget about them!

## Negating a Test

You can also negate a test by using the [not][not] keyword. We can use this to
simplify our code from earlier:

```html+jinja
{# templates/_banner.twig #}

{% if backgroundColor is not defined %}
    {% set backgroundColor = 'lightblue' %}
{% endif %}

<div class="well" style="background-color: {{ backgroundColor }};">
    <h3>Save some Krill!</h3>

    <p>Sale in summer suits all this weekend! {{ pageData.title }}</p>
</div>
```

Awesome! At this point, you know a lot of tools in Twig. Let's keep
going and learn some more.

[defined]: http://twig.sensiolabs.org/doc/tests/defined.html
[set]: http://twig.sensiolabs.org/doc/tags/set.html
[twig_documentation]: http://twig.sensiolabs.org/doc/templates.html#expressions
[even]: http://twig.sensiolabs.org/doc/tests/even.html
[odd]: http://twig.sensiolabs.org/doc/tests/odd.html
[empty]: http://twig.sensiolabs.org/doc/tests/empty.html
[length]: http://twig.sensiolabs.org/doc/filters/length.html
[not]: http://twig.sensiolabs.org/doc/templates.html#logic

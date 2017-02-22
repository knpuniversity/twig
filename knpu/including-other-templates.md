# Including other Templates

Using a base layout is very common, and we've just mastered that! Now suppose
that we have a small "sales" banner that we want to include on both the homepage
and the contact page, because penguins care about saving some [krill][krill] too.
If we wanted it on every page, then we should put it in our layout, but pretend 
for now that we only need it on these 2 pages.

## Using the include Function

To avoid duplication, create a new template that will hold the sales banner.
The filename doesn't matter, but I often prefix these files with an underscore
to show that they only contain a small page fragment, not a whole page:

```html+jinja
{# templates/_banner.twig #}

<div class="well">
    <h3>Save some Krill!</h3>

    <p>Sale in summer suits all this weekend!</p>
</div>
```

To include this on the homepage we can use the [include()][include] function. We use
this in a [say something][say_something_syntax] syntax because `include()` renders
the other template, and we want to print its content:

```html+jinja
{# templates/homepage.twig #}

{% block body %}
    {{ include('_banner.twig') }}

    {# ... #}
{% endblock %}
```

Let's add the same code to `contact.twig` and refresh to make sure that
our big sales banner is showing up. Cool!

## Passing Variables

We can also access our variables from within the included template. Since
both pages have a `pageData` variable, we can use it from within the included
template:

```html+jinja
{# templates/_banner.twig #}

<div class="well">
    <h3>Save some Krill!</h3>

    <p>Sale in summer suits all this weekend! {{ pageData.title }}</p>
</div>
```

You can also pass extra variables to the template. The [include()][include] function
takes two arguments: the name of the template to include and a collection
of additional variables to pass. These variables are a key-value list of names
and their values.

In Twig, a key-value array is called a "hash", and uses a syntax that's just
like JavaScript or JSON (i.e. `{"foo": "bar"}`). Let's pass a `backgroundColor`
variable into the template and use it.

```html+jinja
{# templates/homepage.twig #}

{% block body %}
    {{ include('_banner.twig', { 'backgroundColor': 'violet' }) }}

    {# ... #}
{% endblock %}
```

```html+jinja
{# templates/_banner.twig #}

<div class="well" style="background-color: {{ backgroundColor }};">
    <h3>Save some Krill!</h3>

    <p>Sale in summer suits all this weekend! {{ pageData.title }}</p>
</div>
```

When we refresh, we see a beautiful purple background.

[krill]: http://www.seaworld.org/infobooks/penguins/diet.html
[include]: http://twig.sensiolabs.org/doc/functions/include.html
[say_something_syntax]: https://knpuniversity.com/screencast/twig/basics#the-say-something-syntax-code-code

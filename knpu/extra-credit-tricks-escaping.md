# Extra Credit Tricks and HTML Escaping

You're a pro now, so let's have a little fun and see some sweet tricks!

## Expert Control of Blocks with the block Function

We've learned a lot about template inheritance and blocks. Now, let's make
things a bit more interesting. We created a `title` block in our layout
so that individual pages could control the page title. If a page has a `title`
block, it replaces the page title entirely. If it has *no* `title` block,
then the default title is used.

Let me change the `title` block to be a little more interesting:

```html+jinja
<title>
    {% block title %}
        Penguins Pants Plus! Your source for fancy penguin suits
    {% endblock %} | Penguins Pants Plus!
</title>
```

My goal is to suffix the page title with "Penguins Pants Plus!" so that all
of our pages are consistent. The only funny thing here, besides penguin pants,
is that if the `title` block isn't overridden, the suffix is a little redundant.
Is it possible to *only* add the extra text if the `title` block is overridden?

The secret is the [block][block] function, which you can use to return the content
of a block at any time. Replace the traditional `{% block title %}` and
instead use the `block()` function in a [say something][twig_say_something_syntax]
tag:

```html+jinja
{{ block('title') }} | Penguins Pants Plus!
```

When we refresh, this works like before: the `block` function prints out
the content from the `title` block. The only thing we're missing is the
default page title if the `title` block isn't set.

Now that we know about this `block` function, we can do that easily with
an `if` statement:

```html+jinja
{% if block('title') %}
    {{ block('title') }} | Penguins Pants Plus!
{% else %}
    Penguins Pants Plus! Your source for fancy penguin suits
{% endif %}
```

***TIP
The `block()` function throws an exception since Twig 2.0 if there's
no block with specified name. At first, be sure that the block is defined:

```html+jinja
{% if block('title') is defined %}
    {{ block('title') }} | Penguins Pants Plus!
{% else %}
    Penguins Pants Plus! Your source for fancy penguin suits
{% endif %}
```
***

Success! When we override the `title` block on the homepage, we get the
suffix added. But on the contact page, we just get the default page title.
I try to use blocks in their traditional fashion as often as possible. But
when things get more complicated, use the `block` function to do some really
custom things.

***SEEALSO
To get really advanced, you can import blocks from other templates and
use them in this way. See the [use][use_tag] tag for more details.
***

## The Short block Syntax

And while we're on this topic, we can make the `title` block even shorter
in `homepage.twig`:

```jinja
{% block title 'Start looking fly!' %}
```

You're free to choose whatever format you want, but if your block is just
a simple string, you'll often see this version used.

## Concatenating Strings

One apparent drawback to this is that you can't mix static text and
variables like you could before by just writing some text and then using
the "say something" syntax.

For example, suppose we wanted to include the `pageData.title` variable
in the page title. How can we combine it with the static text? The answer
is with the `~` character, which concatenates strings in Twig.

```jinja
{% block title 'Start looking fly! '~pageData.title %}
```

You won't see this too often, but it'll come in handy when you need it.

## Whitespace Control

Normally, the whitespace you put in a Twig file is left completely alone. We can
see this when we view the source. In fact, we have some extra space around
the `title` tag because of the new trick we're using in Twig. Let's see
if we can get rid of it!

On any twig starting or ending tag, you can add a minus sign (`-`):

```html+jinja
<title>
    {%- if block('title') %}
        {{ block('title') }} | Penguins Pants Plus!
    {% else %}
        Penguins Pants Plus! Your source for fancy penguin suits
    {% endif %}
</title>
```

This tells Twig to trim all the whitespace to the left of that tag until
it hits a non-whitespace character. When we view the source, we can see a
slightly smaller amount of whitespace. If we add enough of these, we'll see
all the extra space disappear:

```html+jinja
<title>
    {%- if block('title') -%}
        {{- block('title') -}} | Penguins Pants Plus!
    {%- else -%}
        Penguins Pants Plus! Your source for fancy penguin suits
    {%- endif -%}
</title>
```

### The spaceless Tag

Another way to control whitespace is with the [spaceless][spaceless] tag. The point
of this tag is a little different: it removes all whitespace between HTML
tags, without affecting space inside an HTML tag or inside static text. If
we surround the meta tags with this and refresh, we'll see those meta tags
all print right next to each other on one line:

```html+jinja
{% spaceless %}
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
{% endspaceless %}
```

## Using Undefined Variables with the default Filter

Let's see one more common trick that may look strange when you first see
it. Look back in the `banner.twig` template where we used the
[single-line if syntax][twig_inline_if_syntax]. Actually, there's an
easier way to do this by using the [default][default] filter:

```html+jinja
<div class="well" style="background-color: {{ backgroundColor|default('lightblue') }};">
```

Normally, if you reference an undefined variable in Twig, it blows up! But
when you use the `default` filter, it avoids that error and instead, returns
the default value `lightblue`. You may see this trick quite often when
someone is using a variable that may or may not be defined.

***TIP
Depending on your settings, Twig may just fail silently if you reference
an undefined variable.
***

## Escaping

Ok, one last thing - HTML escaping! Whenever you render content that may
have been filled in by the user, you need to escape it. This prevents people
from writing HTML tags that you don't want or, worse, JavaScript code that
could be used for [cross-site scripting][cross_site_scripting] attacks.
That's scarier than a hungry pack of leopard seals!

Let's try this out by adding some HTML markup to our page summary:

```php
// index.php
// ...

echo $twig->render('homepage.twig', array(
    'pageData' => array(
        'summary'   => "You're <strong>hip</strong>, you're cool ...",
    ),
    // ...
));
```

When we refresh, Twig is automatically escaping these characters and printing them
out safely. Actually, whether or not Twig automatically does this depends
on how it's setup. In your case, try this out and see if Twig is escaping
or not escaping automatically. You can try this easily by printing out a
static string and seeing what happens.

```jinja
{{ '<strong>hallo</strong>'|upper }}
```

In some cases, you may *need* to actually print out some content unescaped.
To do this, just use the handy [raw][raw] filter:

```html+jinja
<p>
    {{ pageData.summary|raw }}
</p>
```

***TIP
If automatic escaping is *off*, then you need to be quite careful and
use the [escape][escape] filter on any strings you print out to make sure they are
escaped.
***

## Happy Trails

Well hello Twig expert! Our time talking about Twig is coming to an end, but the
good news is that you have all the tools you need to be successful and your
penguins are looking dapper. Remember that all the tags, functions, filters and
tests that are available in Twig can be found on the bottom of its
[documentation page][documentation_page].

Also remember that in your project, you may have even more tags, functions,
filters or tests that are specific to you. Your challenge from here is to
find out what those are and what secrets each holds.

Good luck, and seeya next time!

[use_tag]: http://twig.sensiolabs.org/doc/tags/use.html
[spaceless]: http://twig.sensiolabs.org/doc/tags/spaceless.html
[default]: http://twig.sensiolabs.org/doc/filters/default.html
[cross_site_scripting]: https://en.wikipedia.org/wiki/Cross-site_scripting
[escape]: http://twig.sensiolabs.org/doc/filters/escape.html
[raw]: http://twig.sensiolabs.org/doc/filters/raw.html
[documentation_page]: http://twig.sensiolabs.org/documentation
[block]: http://twig.sensiolabs.org/doc/functions/block.html
[twig_inline_if_syntax]: https://knpuniversity.com/screencast/twig/for-loop-inline-if#twig-inline-if-syntax
[twig_say_something_syntax]: https://knpuniversity.com/screencast/twig/basics#the-say-something-syntax-code-code

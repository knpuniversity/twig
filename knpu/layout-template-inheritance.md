# Using a Layout: Template Inheritance

If we view the HTML source of our project so far, we'll see *just* the HTML
tags and printed variables from our `homepage.twig` file. So far, there's
no HTML layout, head or body tags. but since our project has been ugly long
enough, let's add these.

To add the layout, there's nothing technically wrong with including it right
in `homepage.twig`. This is perfectly straightforward and has nothing to
do with Twig. But let's setup a second page: `/contact`. I'll tweak our
setup script to make this work:

```php
// index.php
// ...

case '/contact':
    echo $twig->render('contact.twig', array(
        'pageData' => array(
            'title' => 'Find us in the south pole!',
        )
    ));
    break;
```

Create the new `contact.twig` file in the `templates/` directory and
add `/contact` to the end of your URL:

```html+jinja
{# templates/contact.twig #}

<h1>{{ pageData.title }}</h1>

<p>Make some penguin noises, we're listening...</p>
```

```
http://localhost/twig/index.php/contact
```

Ok, so now that we have two pages, if we put the layout code in `homepage.twig`,
it wouldn't be available on the contact page. Since that would be a bummer,
let's learn how to use a layout across multiple templates.

## Template Inheritance

The key is template inheritance, which is a lot less scary than it sounds.
Before we get into the details, let's create a new Twig template that will
hold the base layout. I'll paste in some starting HTML, which you can get
from the code download. I'm including some CSS and JS files, but this is
pretty boring overall.

Actually, there's only one small piece of Twig code: a block tag. This defines
a block called "body" and its purpose will make more sense in a moment:

```html+jinja
{# templates/layout.twig #}
<!DOCTYPE html>
<html lang="en">
{# ... more stuff ... #}

{% block body %}{% endblock %}

{# ... the rest ... #}
```

Start by adding another Twig tag via the "do something" syntax called [extends][extends].
After `extends`, type `layout.twig`.

```html+jinja
{# templates/homepage.twig #}
{% extends 'layout.twig' %}

{# ... the rest of the template #}
```

This tells Twig that we want to be "dressed" in `layout.twig`, or said
differently, that we want to use `layout.twig` as the layout for the homepage.
We also need to surround all of our content with a `{% block body %}` tag
and a closing `{% endblock %}`.

This looks just like what we have in the layout file, except with our content
inside.

```html+jinja
{# templates/homepage.twig #}
{% extends 'layout.twig' %}

{% block body %}
    <div class="hero-unit">
        {# ... #}
    </div>

    <div class="row">
        {# ... #}
    </div>
{% endblock %}
```

When we refresh the page, it works! By viewing the source, we can see the
HTML layout with the content of the `homepage.twig` file right in the middle
where we expect it.

### Teamwork: extends and block

This works because of a great team effort between the [extends][extends]
and [block][block] tags. When we use `extends`, it says that this template
should be placed inside of `layout.twig`. But Twig is a bit dumb: it doesn't
really know *where* to put the content from the homepage. The `block` tag
fixes that. By putting a block `body` in the layout *and* a block `body`
around our homepage content, Twig knows exactly where the content should
live in the layout.

### Using Multiple Blocks

We can even use multiple blocks. Let's add a `title` block to the layout:

```html+jinja
{# templates/layout.twig #}

<title>{% block title %}{% endblock %}</title>

{# ... #}
```

If we refresh, the title is blank. But now, we can add a `title` block
to our homepage:

```html+jinja
{# templates/homepage.twig #}
{% extends 'layout.twig' %}

{% block title %}50% off of Bow Ties{% endblock %}

{% block body %}
    {# ... #}
{% endblock %}
```

The order of the blocks doesn't matter, whatever lives in the `title` block
will be placed in the `title` block of the layout. The same is true of any
block. Even the names `title` and `body` aren't special. If we rename
`body` to `content`, we just need to also rename the block in any other
templates.

***TIP
If you want to *add* to the content of the parent block instead of completely
replacing it, use the [parent()][parent] function:

```html+jinja
{% block title %}
    Contact us | {{ parent() }}
{% endblock %}
```
***

### Common Mistake: Content outside of a Block

Let's try to write something outside of a block in `homepage.twig`:

```html+jinja
{# templates/homepage.twig #}
{% extends 'layout.twig' %}

Where should this text be placed in the layout?

{% block title %}50% off of Bow Ties{% endblock %}

{% block body %}
    {# ... #}
{% endblock %}
```

When we refresh, we see a nasty error:

> Uncaught exception `Twig_Error_Syntax` with message "A template that extends
> another one cannot have a body in `homepage.twig` at line 4."

Twig knows that we want it to take the content from the `body` tag of homepage
and put it where the `body` tag is in the layout. But when it sees this
new text, it doesn't know what to do with that or where to put it! The error
is saying that if we extend another template, everything must live in a block
so that Twig knows where to put that content in the layout.

### Adding the Layout to the Contact Page

Our homepage looks great, but the contact page still needs a layout. To give
it one, just add the `extends` tag, then surround the content with a block
called `body`, since that's the name of the block in our layout:

```html+jinja
{# templates/contact.twig #}
{% extends 'layout.twig' %}

{% block body %}
    <h1>{{ pageData.title }}</h1>

    {# ... #}
{% endblock %}
```

And just like that, we have a real page!

### Default Content in a block

Of course the contact page doesn't have a title. We could add a `title`
block just like we did on the homepage. Instead, in the layout, we can put
some content inside of the title block:

```html+jinja
{# templates/layout.twig #}
{# ... #}

<title>{% block title %}Penguin Swag{% endblock %}</title>
```

This becomes the default page title, which is used on the contact page since
we don't have a `title` block in `contact.twig`. But when we go to the
homepage, we still see the title from the `title` block in `homepage.twig`.

### Template Inheritance, a Summary!

Phew! Let's review everything we just learned:

* Initially, a Twig template doesn't render anything other than what's actually
  *in* that template file.

* To use a layout, we use the [extends][extends] tag at the top of the template and
  then surround *all* of our content in a block. Because the template and the
  layout have blocks with the same names, Twig takes the content from each block
  and puts it into the layout to build the whole page.

* In the layout, a block can also have some default content. Because `contact.twig`
  doesn't have a `title` block, the default text is used.

[extends]: http://twig.sensiolabs.org/doc/tags/extends.html
[block]: http://twig.sensiolabs.org/doc/tags/block.html
[parent]: http://twig.sensiolabs.org/doc/functions/parent.html

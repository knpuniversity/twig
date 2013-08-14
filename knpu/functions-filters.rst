Functions, Filters and Debugging with dump
==========================================

Just like PHP or JavaScript, Twig has functions that can be used once we're
inside either a :ref:`say something<twig-say-something-syntax>` or
:ref:`do something<twig-do-something-syntax>` Twig delimiter. To see the
built-in functions, once again check out the bottom of the `Twig Documentation`_
page. In your application - especially if you're using Twig inside something
like Symfony or Drupal - you may have even more functions available to you.
Fortunately, the syntax to use a function is always the same: just check out
your project's documentation to see what other goodies you have.

.. _twig-functions:

Using a Function
----------------

Let's check out the `random`_ function... which gives us, shockingly, a random
number! If we want to print out that random number, we can do it using
the "say something" syntax:

.. code-block:: html+jinja

    {# templates/homepage.twig #}
    
    <div class="price">
        {{ random() }}
    </div>

You can also use a function inside a "do something" tag, like checking to
see if the random number is less than 5:

.. code-block:: html+jinja

    {# templates/homepage.twig #}

    <div class="price">
        {{ random() }}
    
        {# random(10) returns a number from 0 to 10 #}
        {% if random(10) < 5 %}
            Really cheap!
        {% endif %}
    </div>

This works exactly the same, except that it lives inside the ``if`` statement.
Like we've done here, a function can have zero or many arguments, each separated
by a comma.

.. _twig-filters:

Using Filters
-------------

This is nice, but Twig has something even cooler: filters! Like with everything,
you can find a filter list in the `Twig Documentation`_, though you may
have even more in your project. Let's check out the `upper`_ filter. We can
use the upper filter to uppercase each product by placing a pipe (``|``)
to the right of the variable and using the filter:

.. code-block:: html+jinja

    {# ... #}
    <h2>{{ product|upper }}</h2>

This works just like a function, except with a different syntax. Whatever
you have to the left of the pipe is passed to the filter, which in this example,
upper-cases everything.

Heck, you can use as many filters as you want!

.. code-block:: html+jinja

    {# ... #}
    <h2>{{ product|upper|reverse }}</h2>

Now we're upper-casing the name and then reversing the text. 

Using Functions, Filters and Math
---------------------------------

Filters can be used after functions too. Instead of printing out the random
number, let's divide it by 100 to get a decimal, then use the `number_format`_
to show only one decimal place:

.. code-block:: jinja

    {{ (random() / 100)|number_format(1) }}

Getting the Length of a Collection
----------------------------------

In fact, functions and filters can be used anywhere. Let's use the `length`_
filter to print a message if there are no penguin products for sale:

.. code-block:: html+jinja

    {% if products|length == 0 %}
        <div class="alert alert-error span12">
            It looks like we're out of really awesome-looking penguin clothes :/.
        </div>
    {% endif %}

This filter takes an array or collection to the left and transforms it into
a number, which represents the number of items in the collection. We can use
this to see if there are no products. I'll temporarily pass in zero
so we can check this out.

Filters and Arguments using "date"
----------------------------------

Just like functions, sometimes a filter has one or more arguments. A really
common filter is `date`_. This takes a date string or PHP `DateTime`_ object
and changes it into a string. We can go to `php.net/date`_ to look up the
letters used in the date format. To try this out, we can just hardcode a 
string to start:

.. code-block:: html+jinja

    {# templates/homepage.twig #}
    
    <div class="sale-ends-at">
        {{ 'tomorrow noon'|date('D M jS ga') }}
    </div>

The "tomorrow noon" part is just a valid input to PHP's :phpfunction:`strtotime`
function, which accepts all sorts of interesting strings as valid dates.
The Twig `date`_ filter takes that string and renders it in the new format that
we want. Of course, we can also send a variable through the date filter.
Let's pass in a ``saleEndsAt`` variable into the template and render it the
same way::

    // index.php
    // ... 

    echo $twig->render('homepage.twig', array(
        // ...
        'saleEndsAt' => new \DateTime('+1 month')
    ));

.. code-block:: html+jinja

    {# templates/homepage.twig #}

    <div class="sale-ends-at">
        {{ saleEndsAt|date('D M jS ga') }}
    </div>

We can even use the date filter to print out the current year. For the value
to the left of the filter, I'll use ``now``. I'll use the ``Y`` string to
print out the 4-digit year. Sweet!

.. code-block:: jinja

    {{ 'now'|date('Y') }}

Use functions and especially filters to do cool stuff in Twig, and look at
the documentation for each to see if what you're using has any arguments.

The dump Function for Debugging
-------------------------------

Before we move on, let's talk about the `dump`_ function. If you don't know
what a variable looks like, use the ``dump`` function to see its details:

.. code-block:: jinja

    {{ dump(products) }}

Even better, use the ``dump`` function with no arguments to see all the variables
that you have access to:

.. code-block:: jinja

    {{ dump() }}

With this function, there's not much you won't be able to do!

We experimented a lot in this section. I'll use the ``{#`` syntax to comment
out some of the things we've done so that our page makes a bit more sense.

.. note::

    To clean things up, we removed the ``upper`` and ``reverse`` filters,
    the entire spot where we print the random numbers, and the printing of
    the current year.

.. _`Twig Documentation`: http://twig.sensiolabs.org/documentation
.. _`random`: http://twig.sensiolabs.org/doc/functions/random.html
.. _`upper`: http://twig.sensiolabs.org/doc/filters/upper.html
.. _`number_format`: http://twig.sensiolabs.org/doc/filters/number_format.html
.. _`length`: http://twig.sensiolabs.org/doc/filters/length.html
.. _`date`: http://twig.sensiolabs.org/doc/filters/date.html
.. _`php.net/date`: http://www.php.net/date
.. _`DateTime`: http://www.phptherightway.com/#date_and_time
.. _`dump`: http://twig.sensiolabs.org/doc/functions/dump.html
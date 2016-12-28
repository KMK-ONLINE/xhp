Introduction
============

XHP is a PHP extension which augments the syntax of the language such
that XML document fragments become valid PHP expressions. This allows
you to use PHP as a stricter templating engine and offers much more
straightforward implementation of reusable components.

This repository contains the PHP class library, which is required to use
XHP both under PHP7. The PHP7 extension is available at
https://github.com/KMK-ONLINE/xhp-php7-extension

If you want a high-level XHP UI library, you might
want to take a look at https://github.com/hhvm/xhp-bootstrap/

Installation
============

[Composer] is the recommended installation method. To add XHP to your
project, add the following to your `composer.json` then re-run composer:

    <code>
      "require": {
        "kmklabs/xhp": "1.6.*"
      }

</code>

Simple Example
==============

    <?php
    $href = 'http://www.facebook.com';
    echo <a href={$href}>Facebook</a>;

Take note of the syntax on line 3, this is not a string. This is the
major new syntax that XHP introduces to PHP.

Anything that’s in {}’s is interpreted as a full PHP expression. This
differs from {}’s in double-quoted strings; double-quoted strings can
only contain variables.

You can define arbitrary elements that can be instantiated in PHP. Under
the covers each element you create is an instance of a class. To define
new elements you just define a new class. XHP comes with a set of
predefined elements which implement most of HTML for you.

Complex Structures
==================

Note that XHP structures can be arbitrarily complex. This is a valid XHP
program:

    <?php
    $post =
      <div class="post">
        <h2>{$post}</h2>
        <p><span>Hey there.</span></p>
        <a href={$like_link}>Like</a>
      </div>;

One advantage that XHP has over string construction is that it enforces
correct markup structure at compile time. That is, the expression
`$foo = <h1>Header</h2>;` is not a valid expression, because you can not
close an `h1` tag with a `/h2`. When building large chunks of markup it
can be difficult to be totally correct. With XHP the compiler now checks
your work and will refuse to run until the markup is correct.

Dynamic Structures
==================

Sometimes it may be useful to create a bunch of elements and dynamically
add them as children to an element. All XHP objects support the
`appendChild` method which behaves similarly to the same Javascript
method. For example:

    <?php
    $list = <ul />;
    foreach ($items as $item) {
      $list->appendChild(<li>{$item}</li>);
    }

In the code, `<ul />` creates a ul with no children. Then we dynamically
append children to it for each item in the `$items` list.

Escaping
========

An interesting feature of XHP is the idea of automatic escaping. In
vanilla PHP if you want to render input from the user you must manually
escape it. This practice is error-prone and has been proven over time to
be an untenable solution. It increases cod

  [Composer]: https://getcomposer.org/

Enumeration type for PHP
========================

This component provides an actual enumeration type for PHP.


Installation
------------

This component relies on `Composer <https://getcomposer.org/>`_
for its installation.

To use the EnumTrait in your project, just add a requirements on the package:

..  sourcecode:: bash

    $ php composer.php require fpoirotte/enum-trait


Usage
-----

Use the following snippet to declare a new enumeration:

..  sourcecode:: php

    <?php
        final class FavoriteColor implements Serializable
        {
            use EnumTrait;

            private $RED;
            private $BLUE;
            private $GREEN;
        }

        $red    = FavoriteColor::RED();
        $red2   = FavoriteColor::RED();
        $red3   = unserialize(serialize($red));
        $red4   = clone $red;
        $blue   = FavoriteColor::BLUE();

        // Compare two distinct values
        var_dump($red == $blue); // False

        // Compare various instances of the same value
        var_dump($red == $red2); // True
        var_dump($red == $red3); // True
        var_dump($red == $red4); // True

        // Get the enum's value name
        var_dump($red->getName()); // "RED"
    ?>


Goals
-----

This component was designed to achieve the following goals:

*   Define a true type for enumerations, so they can serve as type-hints :

    ..  sourcecode:: php

        <?php
            function displayUsingFavoriteColor(FavoriteColor $color, $message) {
                // ...
            }
        ?>

*   Make it possible to extend an existing enum to add new values.
    This is made dead easy by class inheritance.
    Preventing this is also easy thanks to the ``final`` keyword.

*   Remove the need for an actual value. Intrisically, a label
    ought to be enough to figure out the meaning of a specific
    enum instance. This also avoids repetition.

    Say goodbye to the following idiom:

    ..  sourcecode:: php

        <?php
            class MyEnum extends SomeInferiorEnum
            {
                const VALUE1 = 'value1';
            }
        ?>

*   Turn enumeration values into opaque values.
    This is actually a consequence from the previous goal.

    This prevents developers from using the value directly
    (``if ($enumValue == 1) { /* ... */ }``), which in turn
    prevents subtle bugs whenever the underlying value/type evolves.

*   Make it possible to compare enumeration values directly,
    without the need for helper methods.
    See `Usage`_ for an example of that.

*   Make it possible to easily serialize/unserialize enumerations
    (without requiring some helper functions).

*   Make it possible to copy (clone) an enumeration value without
    a specific support function.

*   Add no requirements on additional PHP extensions (eg. ``SplTypes``).


Contributions
-------------

To contribute a patch:

* `Fork this project <https://github.com/fpoirotte/EnumTrait/fork>`_
* Prepare your patch
* `Submit a pull request <https://github.com/fpoirotte/EnumTrait/pull/new/>`_


License
-------

This project is released under the MIT license.
See the `LICENSE <https://github.com/fpoirotte/EnumTrait/blob/master/LICENSE>`_
file for more information.

.. vim: ts=4 et


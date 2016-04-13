WPSocial Counter (v.0.10)
========================

This plugin allows the user to display the amount of times that an URL have been shared on different social networks. Right now it supports Facebook, Google Plus and LinkedIn. (based from GusFune's work https://github.com/gusfune/shares-counter)

This repository contains the open source PHP and is licensed under the Apache Licence, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0.html).

Usage
=====

Install the plugin as usual, and activate it. In Settings > Share Counter, you can choose if you want to count or don't count a social network (by default, all networks are counted).

Now, open the single.php file of your theme (or any other file) and add this code in any place that you want to show the count:

      <?php sharesCounter(); ?>

It's possible to use CSS. For example:

      <div class="sharecounter"><strong>This post has been shared </strong> <?php sharesCounter(); ?> <strong> times </strong> </div>

You can also return the result as a variable instead of echoing the number:

      <?php $variable = sharesCounter('', false); ?>

Contributing
============

When commiting, try to follow the existing style.

Before creating a pull request, squash your commits into a single commit.

Add the comments where needed, and provide ample explanation in the commit message.

Report Issues/Bugs
==================

Please, don't. This plugin is perfect as-is :P

ok, ok... send a email to phalkmin@gmail.com if you find a bug. But, really, you won't.

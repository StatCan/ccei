This file contains instructions for updating your CCEI-based Drupal site.

CCEI has a two-pronged update process. Out of the box, it provides a great
deal of default configuration for your site, but once it's installed, all that
configuration is "owned" by your site and CCEI cannot safely modify it
without potentially changing your site's behavior or, in a worst-case scenario,
causing data loss.

As it evolves, CCEI's default configuration may change. In certain limited
cases, CCEI will attempt to safely update configuration that it depends on
(which will usually be locked anyway to prevent you from modifying it).
Otherwise, CCEI will leave your configuration alone, respecting the fact
that your site owns it. So, to bring your site fully up-to-date with the latest
default configuration, you must follow the appropriate set(s) of instructions in
the "Manual update steps" section of this file.

## Updating CCEI

### Composer
If you've installed CCEI using our
[Composer-based project template][wxt-project], all you need to do is:

* ```cd /path/to/YOUR_PROJECT```
* ```composer update```
* Run ```drush updatedb``` or visit ```update.php``` to perform db updates.
* Perform any necessary manual updates (see below).

### Tarball
**Do not use ```drush pm-update``` or ```drush up``` to update CCEI!**
CCEI includes specific, vetted, pre-tested versions of modules, and
occasionally patches for those modules (and Drupal core). Drush's updater
totally disregards all of that and may therefore break your site.

To update CCEI safely:

1. Download the latest version of CCEI from
   https://github.com/statcan/ccei and extract it.
2. Replace your ```profiles/od``` directory with the one included in the
   fresh copy of CCEI.
3. Replace your ```core``` directory with the one included in the fresh copy
   CCEI.
4. Visit ```update.php``` or run ```drush updatedb``` to perform any necessary
   database updates.
5. Perform any necessary manual updates (see below).

## Manual update steps

These instructions describe how to update your site's configuration to bring
it in line with a newer version of CCEI. These changes are never made
automatically by CCEI because they have the potential to change the way
your site works.


<!-- Links Referenced -->

[wxt-project]:                https://github.com/drupalwxt/wxt-project

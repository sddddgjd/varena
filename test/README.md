Testing procedure:

One-time setup
==============

1. Install the [Selenium IDE addon for Firefox](https://addons.mozilla.org/en-US/firefox/addon/selenium-ide/).
2. Configure your testing database in the `[testing]` section of `varena2.conf`.

Running the test suite
======================

1. Set `[testing] enabled = true` in `varena2.conf`.
2. Run `php scripts/resetTestingDatabase.php` to, well, reset the testing database.
3. Open Firefox.
4. Type Ctrl+Alt+S to open the Selenium IDE.
5. Select _File -> Open test suite..._ and open `test/varena2.xml`.
6. Click the nice green button.

# Simple PHP Login AND Register

A Simple Login/Register developed in [PHP](https://secure.php.net/) with [Google's reCaptcha](https://www.google.com/recaptcha/admin).

## Download

You can download the latest version or view all of the releases [here](https://github.com/henry7720/PHP-Login-AND-Register/releases).

## Requirements

* PHP ≥ 5.4

## How To:

1. [Download all the files](https://github.com/henry7720/PHP-Login-AND-Register/archive/master.zip)
2. Create a table called users by importing [`createtable.sql`](createtable.sql) into a MySQL Database:
3. Open the [`register.php`](register.php) file and fill in your database details (including Google reCaptcha sitekey and secret).
4. Finally, open [`login.php`](login.php) and also fill in your database details.

## Note

This Login and Register is made for password protecting pages.

Reference [`addthistoeachpage.txt`](addthistoeachpage.txt) for instructions and [`protectedpage.php`](protectedpage.php) for the template.<br> If you'd like to use advanced options such as displayal of the logged in users username, and the number of users registered, view [`advancedoptions.php`](advancedoptions.php).

## License

[GPLv3](LICENSE)

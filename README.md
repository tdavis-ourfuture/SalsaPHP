SalsaPHP
===============

A little PHP sugar for the Salsa Labs API.  This is a project in very early development.  Use at your own risk and send feedback.

##Installation

The easiest way is to use Composer. Add this to your composer.json:

```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/tdavis-ourfuture/SalsaPHP.git"
        }
    ],
    "minimum-stability": "dev",
    "require": {
        "SalsaPHP": "master"
    }
}
```

Then install via:

```
composer.phar install
```

To use the bindings, either user Composer's autoload:

```
require_once('vendor/autoload.php');
```

##Getting started.

You're going need to get your api url.  Read <a href="https://help.salsalabs.com/entries/23514381-Definitions-for-common-terms#API_HOST">this guide</a> for help using it.

You will also need a username and password with api permissions. It's preferable to create a specific user in your management page to interact with the API programmatically.

####Connect to the API

```php
SalsaPHP\SalsaPHP::connect('myemail@organization.org','yourpassword','https://api.yoursalsadomain.org');
```

##Emails

####Send an email blast
Sending an email blast is a multistep process, but SalsaPHP makes it simple.  

1.  Create the email blast using EmailBlast::create
2.  Set your targeting by assigning a pre-existing query. Find your query by going to <a href="https://hq.salsalabs.com/salsa/hq/p/salsa/supporter/common/query/hq/manager.sjs">this page</a> and selecting the key next to the targeting you want.  Alternatively, you can use Query::listQuery() to look it up.
3.  Assign a date and time for the blast to go out.

```php
$refname = 'TEST EMAIL';
$subject = 'Clever Subject Line';
$html = "<html><body>I sent this with the Salsa API!</body></html>";
$text = "";
$fromname = 'Test';
$fromaddress = 'tech@test.org';
$replyto = 'no-reply@test.org';


$key = SalsaPHP\EmailBlast::create($refname,$subject,$html,$text,$fromname,$fromaddress,$replyto);
$res = SalsaPHP\EmailBlast::setQuery($key,2387);  // The 2387 is your target query.  See Salsa interface.
$res = SalsaPHP\EmailBlast::scheduleEmail($key,'2015-10-10 10:10:10);
```

####Get Email Blast Statistics
```php
$stats = SalsaPHP\EmailBlast::Statistics(121112); // Use your email_blast_KEY
```

####List Email Blasts
```php
$stats = SalsaPHP\EmailBlast::listEmails(); 

```
####Get results from an email test set.
TODO

##Supporters

###Unsubscribe a user from everything

```php
$res = SalsaPHP\Supporter::unsubscribeAll(1289323); 
```
####Add a supporter to a group

```php
$res = SalsaPHP\Supporter::addSupporterToGroup(1289323,12); 
```

####Remove a supporter from a group

```php
$res = SalsaPHP\Supporter::deleteSupporterFromGroup(1289323,12); 
```

##Actions

TODO

##Groups

TODO

##Donation 

TODO




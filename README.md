SalsaPHP
===============

A little PHP sugar for the Salsa Labs API.



##Initiate the client

```php
SalsaPHP::setUserName('yourusername');
SalsaPHP::setPassword('yourpassword');
SalsaPHP::setApiBase('http:/api.yoursalsaurl.com');
SalsaPHP::initClient();
```

##Send an email blast

```php
$refname = 'TEST EMAIL';
$subject = 'Clever Subject Line';
$html = "<html><body>I sent this with the Salsa API!</body></html>";
$text = "";
$fromname = 'Test';
$fromaddress = 'tech@test.org';
$replyto = 'no-reply@test.org';


$key = EmailBlast::create($refname,$subject,$html,$text,$fromname,$fromaddress,$replyto);
$res = EmailBlast::setQuery($key,2387);  // The 2387 is your target query.  See Salsa interface.
$res = EmailBlast::scheduleEmail($key,'2015-10-10 10:10:10);
```

##Unsubscribe a user from everything

```php
$res = Supporter::unsubscribeAll(1289323); 
```
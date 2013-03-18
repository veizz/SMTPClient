SMTP Client
==========
Just a powerful SMTP client to send e-mail messages.

How i could use/test ?
----------------------
Firstly, clone the repository.
```
$ git clone git://github.com/andreyknupp/SMTPClient.git
$ cd SMTPClient/
```
Switch the current directory to SMTPClient directory.
```
$ cd SMTPClient/
```
Install dependencies via composer.
```
$ curl -s http://getcomposer.org/installer | php
$ php composer.phar install
```
Perform unit testing is not yet possible, but the same will be provided in future.

How it works.
---------------------------
With this library you can send mail messages via SMTP in a intuitive and robust way. <br />
Initial example, how to connect and perform an authentication with *Google's GMail* SMTP server using a **SSL** connection.

```PHP
<?php

require_once "config/bootstrap.php";

use utils\net\SMTP\Client;
use utils\net\SMTP\Client\Connection\SSLConnection;

$client = new Client(new SSLConnection("smtp.gmail.com", 465));
$client->close();
```
It's simple, huh ? this is the code to connection with the **GMail** SMTP server using SSL protocol. <br />
Maybe, you want to do client authentication, then you can simply do:
```PHP
// Or another authentication method/mechanism
use utils\net\SMTP\Client\Authentication\Login;
// Just tell to authenticate with provided method, and us do the rest.
$client->authenticate(new Login("user", "pswd"));
```

How to create a client via **factory** ? <br />
It's simple, you only need that: 
```PHP
use utils\net\SMTP\ClientFactory;
$client = ClientFactory::create("ssl://smtp.gmail.com:465");
```
With that, you create a connection using SSL, with **smtp.gmail.com** as your SMTP server, that is listening on port **465**. <br />
Ok, but how to perform **authentication** to an user ? <br />
Simply, you can do it in same line, by this way:
```PHP
use utils\net\SMTP\ClientFactory;
$client = ClientFactory::create("ssl://user@gmail.com:pswd@smtp.gmail.com:465#login");
```
Where **#login** is the authentication mechanism to be used to authenticate, you can use **plain** <br />
And in the future, more SASL mechanisms.
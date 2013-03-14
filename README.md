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

    $client = new Client();
    $client->open(new SSLConnection("smtp.gmail.com", 465));
    $client->close();
```
It's simple, huh ? this is the code to connection with the **GMail** SMTP server using SSL protocol. <br />
Maybe, you want to do client authentication, then you can simply do:
```PHP
//Or another authentication method/mechanism
use utils\net\SMTP\Authentication\Login; 
//Just tell to authenticate with provided method, and us do the rest.
$client->authenticate(new Login("user", "pswd")); 
```

Some basics about the State on the Client.
------------------------------------------
The **SMTPClientStateClosed** is the initial state because we aren't connected with a SMTP server, and in this state 
we can call the "open" method to connect with the mail server, if the connection was successfully established, 
the client state will change to **SMTPClientStateEstablished**, in this state, we can authenticate the client, 
or just send mails (in a unauthenticated way), if choose to authenticate and the authentication was successful, 
the state becomes **SMTPClienStateAuthenticated**, in this state we can send mails too, but with client 
authenticated by the server. And finally the state **SMTPClientStateConnected**, and is in this state is that we can 
execute commands on the server to send e-mails.

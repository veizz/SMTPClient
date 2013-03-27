SMTP Client
==========
Just a powerful *Service Mail Transfer Protocol* (SMTP) client to send mail messages.

How it works.
---------------------------
In a simple and robustly way, you could send mail messages using SMTP servers, 
firstly you need to create an connection, and perform authentication for the user, after that, send the message, and done.

Firstly we need a SMTP server, we'll use the gmail server. 
Using the settings from "Outgoing Mail (SMTP) Server" from [Google](https://support.google.com/mail/answer/13287), 
we'll perform the connection and authentication for the user.

```PHP
<?php

require_once "config/bootstrap.php";

use utils\net\SMTP\Client; // SMTP client
use utils\net\SMTP\Client\Authentication\Login; // authentication mechanism
use utils\net\SMTP\Client\Connection\SSLConnection; // the connection
use utils\net\SMTP\Message; // the message

$client = new Client(new SSLConnection("smtp.gmail.com", 465));
$client->authenticate(new Login("user@gmail.com", "pswd"));
```
With that, we're connected and probably authenticated (if you replaced the **user@gmail.com** and **pswd** with valid credentials). <br />
Then, just send the message.
```PHP
$message = new Message();
$message->from("user@gmail.com") // sender
        ->to("other-user@domain.com") // receiver
        ->subject("Hello") // message subject
        ->body("Hello World"); // message content

echo $client->send($message) ? "Message sent" : "Opz";
```
Be happy if the message was sent. <br />
Otherwise, you can open a issue [here](https://github.com/andreyknupp/SMTPClient/issues/new) if you can't identify the problem type (e.g: credentials, connection [host, port]) to resolve it.

How i could use/test ?
----------------------
Firstly, clone the repository.
```bash
$ git clone git://github.com/andreyknupp/SMTPClient.git
$ cd SMTPClient/
```
Switch the current directory to SMTPClient directory.
```bash
$ cd SMTPClient/
```
Install dependencies via composer.
```bash
$ curl -s http://getcomposer.org/installer | php
$ php composer.phar install
```
Performing unit tests isn't possible currently, but in the future, you can. <br />
Thanks, you're free to contribute with anything you think can.

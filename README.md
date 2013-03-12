SMTP Client
==========
\- Just a powerful SMTP client to send e-mail messages.

With this library you can send mail messages via SMTP in a intuitive and robust way. <br />
Initial example, how to connect and perform an authentication with *Google's GMail* SMTP server using a **SSL** connection.

```PHP
<?php

    use utils\Net\SMTP\SMTPClient;
    use utils\Net\SMTP\Authentication\Login;
    use utils\Net\SMTP\Connection\SSLConnection;

    $client = new SMTPClient();
    $client->open(new SSLConnection("smtp.gmail.com", 465));
    $client->authenticate(new Login("username@gmail.com", "password"));
    $client->close();
    
```

It's simple, huh ? every action you execute in the client, you are triggering an action in the current state, so if you aren't in the state to perform the action, you simply gets an exception.

Some basics about the State on the Client.
==============================================
The **SMTPClientStateClosed** is the initial state because we aren't connected with a SMTP server, and in this state 
we can call the "open" method to connect with the mail server, if the connection was successfully established, 
the client state will change to **SMTPClientStateEstablished**, in this state, we can authenticate the client, 
or just send mails (in a unauthenticated way), if choose to authenticate and the authentication was successful, 
the state becomes **SMTPClienStateAuthenticated**, in this state we can send mails too, but with client 
authenticated by the server. And finally the state **SMTPClientStateConnected**, and is in this state is that we can 
execute commands on the server to send e-mails.

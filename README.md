SMTPClient
==========

Simple SMTP Client written using PHP.
Just a powerful, SMTP client library.

With this library you can perform a mail messages sending via SMTP, via sockets in a intuitive and robust way.
Initial example, connecting and performing a authentication with gmail SMTP server over SSL.

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

Some basics about the State in the SMTPClient.
==============================================
- **SMTPClientStateClosed**: is the initial state, we aren't connected with a SMTP server, and in this state we can perform "open" action, to connect with.
- **SMTPClientStateConnected**: the connection was opened successfully, in this state we can perform authentication depending on base state, so then, the server can recognize the user, and can perform "close", and in future we're able to do more things.
- **SMTPClientStateEstablished**: it's sounds like state Connected, but isn't, an internal policy of SMTP server which describes that authentication cannot be performed twice, in this case, the "Authenticated" state, doesn't is a state "Established", we're just connected, but in this state we can perform a client authentication and move forward to "Authenticated" state, where we can't authenticate again and able to perform things in authenticated way.
- **SMTPClientStateAuthenticated**: this is more complex, user can sending mails in local network (same network as SMTP server), this means he doesn't need a authentication, but external servers like "gmail", "yahoo" needs an client authentication.

If you debug using *var_dump* on every call, you can see the current state, and can see the state changes, or not, but firstly you can see, the initial state of a client, and it is a **SMTPClientStateClosed**, 
because don't have a connection established with the server, but, after you provides an connection method and calls "open" with the provided connection method, if all was successfully performed, the state changes to **SMTPClientStateEstablished**, 
in this state, you can perform a authentication (if performed, the state changes to **SMTPClientStateAuthenticated**) or can proceed with the process to send a message :)

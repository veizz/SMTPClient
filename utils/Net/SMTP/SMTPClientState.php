<?php

    /**
     * @package utils.Net.SMTP
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\Net\SMTP\SMTPConnection.php
     */

    namespace utils\Net\SMTP;

    use utils\Net\SMTP\SMTPClient;
    use utils\Net\SMTP\SMTPConnection;
    use utils\Net\SMTP\SMTPAuthenticator;

    interface SMTPClientState
    {

        public function open(SMTPConnection $connection, SMTPClient $context);
        public function close(SMTPClient $context);
        public function authenticate(SMTPAuthenticator $authenticator, SMTPClient $context);
        
    }
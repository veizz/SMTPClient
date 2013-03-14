<?php

    /**
     * @package utils.net.SMTP.Client
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\State.php
     */
    namespace utils\net\SMTP\Client;
    use utils\net\SMTP\Client;
    use utils\net\SMTP\Client\Connection;
    use utils\net\SMTP\Client\Authentication;

    interface State
    {

        public function open(Connection $connection, Client $context);
        public function close(Client $context);
        public function authenticate(Authentication $authentication, Client $context);
        
    }
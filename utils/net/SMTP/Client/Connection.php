<?php

    /**
     * @package utils.net.SMTP.Client
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\Connection.php
     */
    namespace utils\net\SMTP\Client;
    use utils\net\SMTP\Client\ConnectionState;
    use utils\net\SMTP\Client\Authentication;
    
    interface Connection
    {
        public function authenticate(Authentication $authentication);
        public function open($protocol, $hostname, $port, $timeout = 30);
        public function changeState(ConnectionState $connectionState);
        public function close();
        public function write($data);
        public function read();
    }

    
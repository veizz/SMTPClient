<?php

    /**
     * @package utils.net.SMTP.Client
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\ConnectionState.php
     */
    namespace utils\net\SMTP\Client;
    use utils\net\SMTP\Client\Connection;

    interface ConnectionState
    {
        public function read();
        public function write($data);
        public function close(Connection $context);
        public function open($protocol, $hostname, $port, $timeout = 30, Connection $context);
        public function authenticate(Authentication $authentication, Connection $context);
        public function getStream();
        public function getLatestMessage();
        public function getExchangedMessages();
    }
<?php

    /**
     * @package utils.net.SMTP.Client
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\Connection.php
     */
    namespace utils\net\SMTP\Client;

    interface Connection
    {
        public function isEstablished();
        public function open($host, $port, $timeout = 30);
        public function close();
    }

    
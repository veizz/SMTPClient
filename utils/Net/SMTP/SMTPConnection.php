<?php

    /**
     * @package utils.Net.SMTP
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\Net\SMTP\SMTPConnection.php
     */
    namespace utils\Net\SMTP;
    use utils\Net\SMTP\SMTPAuthenticator;

    interface SMTPConnection
    {
        public function isEstablished();
        public function open($host, $port, $timeout = 30);
        public function close();
    }

    
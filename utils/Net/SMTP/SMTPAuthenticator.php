<?php

    /**
     * @package utils.Net.SMTP
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\Net\SMTP\SMTPAuthenticator.php
     */
    namespace utils\Net\SMTP;

    interface SMTPAuthenticator
    {
        /**
         * Response code that means a authentication was performed successfully.
         * @const integer
         */
        const AUTHENTICATION_PERFORMED = 235;
        /**
         * Acording IETF-RFC 2554 - Page 1 (March 1999)
         * When a SMTP server cannot perform authentication with specified mechanism
         * it would be return a message with response code 504 (Unrecognized authentication type)
         * @const integer
         */
        const UNRECOGNIZED_AUTHENTICATION_TYPE = 504;
        /**
         * When authentication step was accepted or provided authentication mechanism
         * was accepted by the server, a response with code 334 was returned meaning the server acceptance.
         * @const integer
         */
        const ACCEPTED = 334;
        /**
         * Perform authentication for the client in SMTP server.
         * @param SMTPConnection $connection the connection to authenticate.
         * @return void|boolean
         */
        public function authenticate(SMTPConnection $connection);
    }
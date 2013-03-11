<?php

    /**
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\Net\SMTP\Authentication\AbstractSMTPAuthentication.php
     * @package utils.Net.SMTP.Authentication
     */
    namespace utils\Net\SMTP\Authentication;

    abstract class AbstractSMTPAuthentication
    {

        /**
         * Constructor.
         * Sets user credentials to perform AUTH LOGIN authentication.
         * @param string $username the username to perform authentication.
         * @param string $password the password to authenticate.
         */
        public function __construct($username, $password)
        {
            $this->setUsername($username);
            $this->setPassword($password);
        }
        
        /**
         * Username to perform authentication
         * @var string|NULL
         */
        private $username = NULL;

        /**
         * Password to authenticate
         * @var string|NULL
         */
        private $password = NULL;

        /**
         * Sets the username to perform an authentication.
         * @param string $username the username to use
         * @return void
         */
        public function setUsername($username)
        {
            $this->username = $username;
        }

        /**
         * Sets a password to authenticate.
         * @param string $password the password to authenticate
         * @return void
         */
        public function setPassword($password)
        {
            $this->password = $password;
        }

        /**
         * Retrieve username to be used in authentication.
         * @return string|NULL
         */
        public function getUsername()
        {
            return $this->username;
        }

        /**
         * Retrieve password to be used in authentication.
         * @return string|NULL
         */
        public function getPassword()
        {
            return $this->password;
        }
        
        /**
         * Extract response code from received reply message.
         * @param string $response response with code.
         * @return integer
         */
        protected function getResponseCode($response) {
            return intval(substr($response, 0, 3));
        }

    }
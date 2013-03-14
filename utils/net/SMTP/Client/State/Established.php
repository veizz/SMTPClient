<?php

    /**
     * @package utils.net.SMTP.Client.State
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\State\Established.php
     */
    namespace utils\net\SMTP\Client\State;
    use utils\net\SMTP\Client\State\Connected;
    use utils\net\SMTP\Client\State\Authenticated;
    use utils\net\SMTP\Client\Authentication;
    use utils\net\SMTP\Client;
    use \RuntimeException;

    class Established extends Connected
    {

        /**
         * Authenticates the client with specified authentication method.
         * 
         * @param Authentication $authentication the authentication method to authenticate
         * @param Client $context client that expects be a authenticated one
         * @throws RuntimeException if the authentication process wasn't a success
         * @return boolean
         */
        public function authenticate(Authentication $authentication, Client $context)
        {
            if (!$authentication->authenticate($this->connection)) {
                $message = "Couldn't authenticate the client.";
                throw new RuntimeException($message);
            }
            
            $this->changeState(new Authenticated(), $context);
            return true;
        }

    }
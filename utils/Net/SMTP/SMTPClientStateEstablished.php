<?php

    /**
     * @package utils.Net.SMTP
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\Net\SMTP\SMTPClientStateEstablished.php
     */
    namespace utils\Net\SMTP;
    use utils\Net\SMTP\SMTPClientStateConnected;
    use utils\Net\SMTP\AbstractSMTPClientState;
    use utils\Net\SMTP\SMTPAuthenticator;
    use utils\Net\SMTP\SMTPClient;
    use utils\Net\SMTP\SMTPClientStateAuthenticated;
    use \RuntimeException;

    class SMTPClientStateEstablished extends SMTPClientStateConnected
    {

        /**
         * Authenticates the client connection with specified authentication method.
         * @param \utils\Net\SMTP\SMTPAuthenticator $authenticator the authenticator provided to authenticate the client.
         * @param \utils\Net\SMTP\SMTPClient $context client context, that expects a authenticated state.
         * @throws RuntimeException if the authentication process wasn't a success.
         * @return boolean
         */
        public function authenticate(SMTPAuthenticator $authenticator, SMTPClient $context)
        {
            if (!$authenticator->authenticate($this->connection)) {
                $message = "Couldn't authenticate the connection.";
                throw new RuntimeException($message);
            }
            
            $this->changeState(new SMTPClientStateAuthenticated(), $context);
            return true;
        }

    }
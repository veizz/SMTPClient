<?php

    /**
     * @package utils.Net.SMTP
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\Net\SMTP\AbstractSMTPClientState.php
     */
    namespace utils\Net\SMTP;
    use utils\Net\SMTP\SMTPAuthenticator;
    use utils\Net\SMTP\SMTPClientState;
    use utils\Net\SMTP\SMTPClient;
    use \BadMethodCallException;

    abstract class AbstractSMTPClientState implements SMTPClientState
    {

        /**
         * SMTP client connection with server.
         * @var AbstractSMTPClientConnection
         */
        protected $connection;

        /**
         * Opens a connection with an SMTP server using specified connector.
         * @param \utils\Net\SMTP\SMTPConnection $connection
         * @param \utils\Net\SMTP\SMTPClient $context
         * @throws BadMethodCallException
         * @return void|boolean
         */
        public function open(SMTPConnection $connection, SMTPClient $context)
        {
            throw new BadMethodCallException("Method not implemented.");
        }

        /**
         * Authenticates an established connection with a SMTP server
         * using the specified authenticator to authenticate.
         * 
         * @param \utils\Net\SMTP\SMTPAuthenticator $connection
         * @param \utils\Net\SMTP\SMTPClient $context
         * @throws BadMethodCallException
         * @return void|boolean
         */
        public function authenticate(SMTPAuthenticator $connection, SMTPClient $context)
        {
            throw new BadMethodCallException("Method not implemented.");
        }

        /**
         * Closes a established connection with SMTP server.
         * @param \utils\Net\SMTP\SMTPClient $context
         * @throws BadMethodCallException
         */
        public function close(SMTPClient $context)
        {
            throw new BadMethodCallException("Method not implemented.");
        }

        /**
         * Changes the context state with the specified one.
         * @param \utils\Net\SMTP\SMTPClientState $state
         * @param \utils\Net\SMTP\SMTPClient $context
         * @return void
         */
        public function changeState(SMTPClientState $state, SMTPClient $context)
        {
            $state->connection = $this->connection;
            $context->changeState($state);
        }

    }
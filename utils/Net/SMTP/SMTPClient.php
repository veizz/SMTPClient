<?php

    /**
     * @package utils.Net.SMTP
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\Net\SMTP\SMTPClient.php
     */
    namespace utils\Net\SMTP;
    use utils\Net\SMTP\SMTPClientState;
    use utils\Net\SMTP\SMTPConnection;

    class SMTPClient
    {
        
        /**
         * Current SMTPClient state.
         * @var AbstractSMTPClientState 
         */
        private $state;

        /**
         * Constructor
         * Sets the initial STMPClient state.
         * @return \utils\Net\SMTP\SMTPClient
         */
        public function __construct()
        {
            $this->state = new SMTPClientStateClosed();
        }

        /**
         * Opens a connection with SMTP server using a valid connection interface.
         * @param \utils\Net\SMTP\SMTPConnection $connection
         * @return void|boolean
         */
        public function open(SMTPConnection $connection)
        {
            $this->state->open($connection, $this);
        }

        /**
         * Authenticates the connection with SMTP server using a specified authenticator.
         * @param \utils\Net\SMTP\SMTPAuthenticator $authenticator
         * @return void|boolean
         */
        public function authenticate(SMTPAuthenticator $authenticator)
        {
            $this->state->authenticate($authenticator, $this);
        }

        /**
         * Closes connection with SMTP server.
         * @return void|boolean
         */
        public function close()
        {
            $this->state->close($this);
        }

        /**
         * Changes the current SMTPClient state.
         * @param \utils\Net\SMTP\SMTPClientState $state
         * @return void
         */
        public function changeState(SMTPClientState $state)
        {
            $this->state = $state;
        }

    }
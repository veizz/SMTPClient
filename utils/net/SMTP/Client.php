<?php

    /**
     * @package utils.net.SMTP.Client
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client.php
     */
    namespace utils\net\SMTP;
    use utils\net\SMTP\Client\State;
    use utils\net\SMTP\Client\Authentication;
    use utils\net\SMTP\Client\State\Closed;
    use utils\net\SMTP\Client\Connection;

    class Client
    {
        
        /**
         * The client state
         * @var State 
         */
        private $state;

        /**
         * Sets the initial client state
         * @return Client
         */
        public function __construct()
        {
            $this->state = new Closed();
        }

        /**
         * Opens a connection with SMTP server.
         * 
         * @param Connection $connection
         * @return Client
         */
        public function open(Connection $connection)
        {
            $this->state->open($connection, $this);
            return $this;
        }

        /**
         * Authenticates the client in server.
         * 
         * @param Authentication $authentication
         * @return Client
         */
        public function authenticate(Authentication $authentication)
        {
            $this->state->authenticate($authentication, $this);
            return $this;
        }

        /**
         * Closes an opened connection with SMTP server.
         * @return Client
         */
        public function close()
        {
            $this->state->close($this);
            return $this;
        }

        /**
         * Changes the client state with the specified one.
         * 
         * @param State $state the new state
         * @return void
         */
        public function changeState(State $state)
        {
            $this->state = $state;
        }

    }

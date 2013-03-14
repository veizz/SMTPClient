<?php

    /**
     * @package utils.net.SMTP.Client
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\AbstractState.php
     */
    namespace utils\net\SMTP\Client;
    use utils\net\SMTP\Client\Connection;
    use utils\net\SMTP\Client\Authentication;
    use utils\net\SMTP\Client\State;
    use utils\net\SMTP\Client;
    use \BadMethodCallException;

    abstract class AbstractState implements State
    {

        /**
         * Client connection with SMTP server
         * @var Connection
         */
        protected $connection;

        /**
         * Opens a connection with an SMTP server using specified connector
         * 
         * @param Connection $connection the connection mode to be used to connect.
         * @param Client $context the client that expects an openned connection with server
         * @throws BadMethodCallException
         * @return void
         */
        public function open(Connection $connection, Client $context)
        {
            throw new BadMethodCallException("Method not implemented.");
        }

        /**
         * Authenticates the client in the SMTP server
         * 
         * @param Authenticator $authenticator the authentication method to authenticate the client
         * @param Client $context the client that expects an authentication
         * @throws BadMethodCallException
         * @return void
         */
        public function authenticate(Authentication $authentication, Client $context)
        {
            throw new BadMethodCallException("Method not implemented.");
        }

        /**
         * Closes an established connection with SMTP server.
         * 
         * @param Client $context the client that expects to close the connection
         * @throws BadMethodCallException
         */
        public function close(Client $context)
        {
            throw new BadMethodCallException("Method not implemented.");
        }

        /**
         * Changes the context state with the specified one.
         * 
         * @param State $state the new state of the client
         * @param Client $context client that expects an state change
         * @return void
         */
        public function changeState(State $state, Client $context)
        {
            $state->connection = $this->connection;
            $context->changeState($state);
        }

    }
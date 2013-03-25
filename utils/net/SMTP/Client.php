<?php

    /**
     * @package utils.net.SMTP
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client.php
     */
    namespace utils\net\SMTP;
    use utils\net\SMTP\Client\Connection;
    use utils\net\SMTP\Client\Authentication;

    class Client
    {

        /**
         * The connection used to talk with the server.
         * @var Connection 
         */
        private $connection = NULL;

        /**
         * - Constructor
         * @param Connection $connection the connection used to talk with server
         * @return Client
         */
        public function __construct(Connection $connection)
        {
            $this->connection = $connection;
        }

        /**
         * Authenticates a user with provided authentication mechanism.
         * @param Authentication $authentication the authentication mechanism
         * @return boolean
         */
        public function authenticate(Authentication $authentication)
        {
            return $this->connection->authenticate($authentication);
        }

        /**
         * Closes an opened connection with the SMTP server.
         * @return void
         */
        public function close()
        {
            $this->connection->close();
        }

        /**
         * Retrieves the latest exchanged message with the server.
         * @see AbstractConnection::getLatestMessage()
         * @return Message
         */
        public function getLatestMessage()
        {
            return $this->connection->getLatestMessage();
        }

        /**
         * Retrieves all exchanged messages with the server.
         * @see AbstractConnection::getExchangedMessages()
         * @return array[Message]
         */
        public function getExchangedMessages()
        {
            return $this->connection->getExchangedMessages();
        }

    }
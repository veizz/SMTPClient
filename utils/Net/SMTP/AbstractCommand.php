<?php

    /**
     * @package utils.Net.SMTP
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\Net\SMTP\AbstractCommand.php
     */
    namespace utils\Net\SMTP;
    use utils\Net\SMTP\SMTPConnection;
    use utils\Net\SMTP\Command;

    abstract class AbstractCommand implements Command
    {

        /**
         * Connection with SMTP server.
         * @var SMTPConnection
         */
        protected $connection;

        /**
         * - Constructor
         * Defines the SMTP server connection to perform commands on it.
         * @param \utils\Net\SMTP\SMTPConnection $connection the SMTP server connection.
         */
        public function __construct(SMTPConnection $connection)
        {
            $this->connection = $connection;
        }

        /**
         * Extracts the response code from a message reply from SMTP server.
         * @param string $response the SMTP server response with code.
         * @return integer
         */
        protected function getResponseCode($response)
        {
            return intval(substr($response, 0, 3));
        }
        
    }
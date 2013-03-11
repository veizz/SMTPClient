<?php

    /**
     * @package utils.Net.SMTP.Command
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\Net\SMTP\Command\QUITCommand.php
     */
    namespace utils\Net\SMTP\Command;
    use utils\Net\SMTP\AbstractCommand;
    use \RuntimeException;

    class QUITCommand extends AbstractCommand
    {

        /**
         * Performs an correctly abortion on SMTP server.
         * @throws RuntimeException if the abortion wasn't a success.
         */
        public function execute()
        {
            if ($this->connection->write("QUIT\r\n")) {
                $response = $this->connection->read();
                if(($responseCode = $this->getResponseCode($response)) !== 221) {
                    $message = "QUIT wasn't successfully performed.";
                    throw new RuntimeException($message, $responseCode);
                }
            }
        }

    }
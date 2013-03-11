<?php

    /**
     * @package utils.Net.SMTP.Command
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\Net\SMTP\Command\STARTTLSCommand.php
     */
    namespace utils\Net\SMTP\Command;
    use utils\Net\SMTP\AbstractCommand;
    use \RuntimeException;

    class STARTTLSCommand extends AbstractCommand
    {

        /**
         * Tells the SMTP server we will being using an encrypted connection, using TLS.
         * @throws RuntimeException if the client connection can't be encrypted by server or client.
         */
        public function execute()
        {
            if ($this->connection->write("STARTTLS\r\n")) {
                $startTLSResponse = $this->connection->read();
                if(($responseCode = $this->getResponseCode($startTLSResponse)) !== 220) {
                    $message = "Couldn't perform STARTTLS command.";
                    throw new RuntimeException($message, $responseCode);
                } else {
                    $type = STREAM_CRYPTO_METHOD_TLS_CLIENT;
                    if(!stream_socket_enable_crypto($this->connection->getStream(), true, $type)){
                        $message = "Cannot encrypt the connection.";
                        throw new RuntimeException($message);
                    }
                }
            }
        }

    }
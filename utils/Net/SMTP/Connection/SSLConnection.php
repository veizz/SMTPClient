<?php

    /**
     * @package utils.Net.SMTP.Connection
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\Net\SMTP\Connection\TCPConnection.php
     */
    namespace utils\Net\SMTP\Connection;
    use utils\Net\SMTP\SMTPAuthenticator;
    use utils\Net\SMTP\AbstractSMTPClientConnection;

    class SSLConnection extends AbstractSMTPClientConnection
    {

        /**
         * Opens a connection with smtp server using ssl protocol.
         * @param string $host valid smtp server hostname.
         * @param integer $port the smtp server port.
         * @param integer $timeout timeout in seconds for wait a connection.
         */
        public function open($host, $port, $timeout = 30)
        {
            if ($this->createStreamSocketClient("ssl", $host, $port, $timeout)) {
                $this->established = true;
            }
        }
        
    }
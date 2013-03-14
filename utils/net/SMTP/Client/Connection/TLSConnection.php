<?php

    /**
     * @package utils.net.SMTP.Client.Client.Connection
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\Connection\TLSConnection.php
     */
    namespace utils\net\SMTP\Client\Connection;
    use utils\net\SMTP\Client\Command\STARTTLSCommand;
    use utils\net\SMTP\Client\Command\EHLOCommand;
    use utils\net\SMTP\Client\Command\HELOCommand;
    use utils\net\SMTP\Client\CommandInvoker;
    use utils\net\SMTP\Client\AbstractConnection;

    class TLSConnection extends AbstractConnection
    {

        /**
         * Opens a connection with SMTP server using TLS protocol.
         * 
         * @param string $host valid SMTP server hostname
         * @param integer $port the SMTP server port
         * @param integer $timeout timeout in seconds for wait a connection.
         * @link http://www.ietf.org/rfc/rfc3207.txt
         */
        public function open($host, $port, $timeout = 30)
        {
            if ($this->createStreamSocketClient("tcp", $host, $port, $timeout)) {
                $commandInvoker = new CommandInvoker();
                $commandInvoker->invoke(new EHLOCommand($this));
                $commandInvoker->invoke(new HELOCommand($this));
                $commandInvoker->invoke(new STARTTLSCommand($this));
                $this->established = true;
            }
        }
        
    }
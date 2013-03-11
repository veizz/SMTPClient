<?php

    /**
     * @package utils.Net.SMTP.Connection
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\Net\SMTP\Connection\TLSConnection.php
     */
    namespace utils\Net\SMTP\Connection;
    use utils\Net\SMTP\SMTPAuthenticator;
    use utils\Net\SMTP\AbstractSMTPClientConnection;
    use utils\Net\SMTP\SMTPClientCommandInvoker;
    use utils\Net\SMTP\Command\HELOCommand;
    use utils\Net\SMTP\Command\EHLOCommand;
    use utils\Net\SMTP\Command\STARTTLSCommand;

    class TLSConnection extends AbstractSMTPClientConnection
    {

        /**
         * Opens a connection with SMTP server using TLS protocol.
         * @param string $host valid smtp server hostname.
         * @param integer $port the smtp server port.
         * @param integer $timeout timeout in seconds for wait a connection.
         */
        public function open($host, $port, $timeout = 30)
        {
            if ($this->createStreamSocketClient("tcp", $host, $port, $timeout)) {
                $commandInvoker = new SMTPClientCommandInvoker();
                /**
                 * Its necessary to perform "EHLO" and "HELO" before the connection being encrypted. 
                 * So, invoke them and the "EHLO" and "HELO" will be performed after TLS negotiation normally.
                 * @link http://www.ietf.org/rfc/rfc3207.txt
                 */
                $commandInvoker->invoke(new EHLOCommand($this));
                $commandInvoker->invoke(new HELOCommand($this));
                $commandInvoker->invoke(new STARTTLSCommand($this));
                $this->established = true;
            }
        }
        
    }
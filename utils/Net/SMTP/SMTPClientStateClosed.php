<?php

    /**
     * @package utils.Net.SMTP
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\Net\SMTP\SMTPClientStateClosed.php
     */

    namespace utils\Net\SMTP;
    use utils\Net\SMTP\AbstractSMTPClientState;
    use utils\Net\SMTP\SMTPConnection;
    use utils\Net\SMTP\SMTPClient;
    use utils\Net\SMTP\SMTPClientStateEstablished;
    use utils\Net\SMTP\SMTPClientCommandInvoker;
    use utils\Net\SMTP\Command\HELOCommand;
    use utils\Net\SMTP\Command\EHLOCommand;

    class SMTPClientStateClosed extends AbstractSMTPClientState
    {

        /**
         * Opens a connection with an SMTP server using specified connector.
         * @param \utils\Net\SMTP\SMTPConnection $connection
         * @param \utils\Net\SMTP\SMTPClient $context
         * @see \utils\Net\SMTP\AbstractSMTPState::open()
         * @return void|boolean
         */
        public function open(SMTPConnection $connection, SMTPClient $context)
        {
            if ($connection->isEstablished()) {
                $this->connection = $connection;
                $commandInvoker = new SMTPClientCommandInvoker();
                $commandInvoker->invoke(new EHLOCommand($connection));
                $commandInvoker->invoke(new HELOCommand($connection));
                
                $this->changeState(new SMTPClientStateEstablished(), $context);
            }
        }

    }
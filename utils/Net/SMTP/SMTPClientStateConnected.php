<?php

    /**
     * @package utils.net.SMTP
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\Net\SMTP\SMTPClientStateConnected.php
     */
    namespace utils\Net\SMTP;
    use utils\Net\SMTP\AbstractSMTPClientState;
    use utils\Net\SMTP\SMTPConnection;
    use utils\Net\SMTP\SMTPClientStateClosed;
    use utils\Net\SMTP\Command\QUITCommand;
    use utils\Net\SMTP\SMTPClientCommandInvoker;

    class SMTPClientStateConnected extends AbstractSMTPClientState
    {

        /**
         * Closes a established connection with SMTP server.
         * @param \utils\Net\SMTP\SMTPClient $context
         * @return void|boolean
         */
        public function close(SMTPClient $context)
        {
            $commandInvoker = new SMTPClientCommandInvoker();
            $commandInvoker->invoke(new QUITCommand($this->connection));
            
            $this->changeState(new SMTPClientStateClosed(), $context);
            $this->connection->close();
        }

    }
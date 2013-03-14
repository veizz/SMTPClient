<?php

    /**
     * @package utils.net.SMTP.Client.State
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\State\Connected.php
     */
    namespace utils\net\SMTP\Client\State;
    use utils\net\SMTP\SMTPConnection;
    use utils\net\SMTP\Client\State\Closed;
    use utils\net\SMTP\Client\CommandInvoker;
    use utils\net\SMTP\Client\AbstractState;
    use utils\net\SMTP\Client\Command\QUITCommand;
    use utils\net\SMTP\Client;
    
    class Connected extends AbstractState
    {

        /**
         * Closes an established connection with SMTP server.
         * 
         * @param Client $context
         * @return void
         */
        public function close(Client $context)
        {
            $commandInvoker = new CommandInvoker();
            $commandInvoker->invoke(new QUITCommand($this->connection));
            
            $this->changeState(new Closed(), $context);
            $this->connection->close();
        }

    }
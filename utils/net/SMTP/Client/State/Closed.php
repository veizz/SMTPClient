<?php

    /**
     * @package utils.net.SMTP.Client.State
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\State\Closed.php
     */
    namespace utils\net\SMTP\Client\State;
    use utils\net\SMTP\Client\AbstractState;
    use utils\net\SMTP\Client\CommandInvoker;
    use utils\net\SMTP\Client\Command\HELOCommand;
    use utils\net\SMTP\Client\Command\EHLOCommand;
    use utils\net\SMTP\Client\Connection;
    use utils\net\SMTP\Client\State\Established;
    use utils\net\SMTP\Client;

    class Closed extends AbstractState
    {

        /**
         * Opens a connection with SMTP server and changes the client state
         * if the connection was successfully established with the server
         * 
         * @param Connection $connection the connection method to connect
         * @param Client $context the client that expects an opened connection with server
         * @return void
         */
        public function open(Connection $connection, Client $context)
        {
            if ($connection->isEstablished()) {
                $this->connection = $connection;
                
                $commandInvoker = new CommandInvoker();
                $commandInvoker->invoke(new EHLOCommand($connection));
                $commandInvoker->invoke(new HELOCommand($connection));
                $this->changeState(new Established(), $context);
            }
        }

    }
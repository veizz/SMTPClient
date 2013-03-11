<?php

    /**
     * @package utils.Net.SMTP.Command
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\Net\SMTP\Command\HELOCommand.php
     */
    namespace utils\Net\SMTP\Command;
    use utils\Net\SMTP\Command\HELLOCommand;
    use \RuntimeException;

    class HELOCommand extends HELLOCommand
    {

        /**
         * Performs a "HELO" command in the SMTP server.
         * @throws RuntimeException if the command wasn't executed successfully.
         * @return boolean
         */
        public function execute()
        {
            if (!$this->performEhloHelo("HELO")) {
                $message = "Couldn't perform HELO command in SMTP server.";
                throw new RuntimeException($message);
            }
        }

    }
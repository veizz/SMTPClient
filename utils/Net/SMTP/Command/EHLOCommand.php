<?php

    /**
     * @package utils.Net.SMTP.Command
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\Net\SMTP\Command\EHLOCommand.php
     */
    namespace utils\Net\SMTP\Command;
    use utils\Net\SMTP\Command\HELLOCommand;
    use \RuntimeException;

    class EHLOCommand extends HELLOCommand
    {

        /**
         * Performs a "EHLO" command in the SMTP server.
         * @throws RuntimeException if the command wasn't executed successfully.
         * @return boolean
         */
        public function execute()
        {
            if (!$this->performEhloHelo("EHLO")) {
                $message = "Couldn't perform EHLO command in SMTP server.";
                throw new RuntimeException($message);
            }
        }

    }
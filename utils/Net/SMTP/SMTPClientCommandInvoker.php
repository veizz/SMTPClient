<?php

    /**
     * @package utils.Net.SMTP
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\Net\SMTP\SMTPClientCommandInvoker.php
     */

    namespace utils\Net\SMTP;
    use utils\Net\SMTP\Command;

    class SMTPClientCommandInvoker
    {

        /**
         * Invokes an specified command.
         * @param \utils\Net\SMTP\Command $command
         */
        public function invoke(Command $command)
        {
            $command->execute();
        }

    }
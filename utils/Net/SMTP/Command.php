<?php
    
    /**
     * @package utils.Net.SMTP
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\Net\SMTP\Command.php
     */
    namespace utils\Net\SMTP;
    
    interface Command
    {
        /**
         * Executes an command.
         * @return void
         */
        public function execute();
    }
<?php

    /**
     * @package utils.Net.SMTP.Command
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\Net\SMTP\Command\HELLOCommand.php
     */
    namespace utils\Net\SMTP\Command;
    use utils\Net\SMTP\AbstractCommand;
    use \RuntimeException;

    abstract class HELLOCommand extends AbstractCommand
    {

        /**
         * Performs a "specified" (HELO, EHLO) command in the SMTP server.
         * @throws RuntimeException if the command wasn't executed successfully.
         * @return boolean
         */
        public function performEhloHelo($command) 
        {
            if ($this->connection->write(sprintf("%s %s\r\n", $command, $this->connection->getHostname()))) {
                $response = $this->connection->read();
                /**
                 * @todo Based on IETF-RFC specification, an EHLO or HELO command 
                 * would return success reply code equals 250, as successfully connection established state.
                 * @link http://www.ietf.org/rfc/rfc2821.txt Standards Track - Page 48 (April 2001)
                 */
                if ($this->getResponseCode($response) === 250) {
                    return true;
                }
            }
        }

    }
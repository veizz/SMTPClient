<?php

    /**
     * @package utils.net.SMTP.Client.Authentication
     * @author Tiago de Souza Ribeiro <tiago.sr@msn.com>
     * @filesource utils\net\SMTP\Client\Authentication\CramMd5.php
     */

    namespace utils\net\SMTP\Client\Authentication;

    use utils\net\SMTP\Client\Connection;
    use utils\net\SMTP\Client\Authentication\AbstractAuthentication;
    use utils\net\SMTP\Client\Authentication;
    use \RuntimeException;
    use utils\net\SMTP\Client\CommandInvoker;
    use utils\net\SMTP\Client\Command\InputCommand;
    use utils\net\SMTP\Client\Command\AUTHCommand;

    class CramMd5 extends AbstractAuthentication implements Authentication
    {

        /**
         * Perform an AUTH PLAIN in SMTP server to authenticate the user.
         * @param Connection $connection the connection with SMTP server
         * @link http://www.ietf.org/rfc/rfc2554.txt
         * @return boolean
         */
        public function authenticate(Connection $connection)
        {
            $username = $this->getUsername();
            $password = $this->getPassword();

            $invoker = new CommandInvoker();
            $invoker->invoke(new AUTHCommand($connection, "CRAM-MD5"));
            $invoker->invoke(new InputCommand(
                $connection,
                base64_encode(
                    sprintf("%s %s",
                        $username,
                        bin2hex(hash_hmac(
                            "md5",
                            base64_decode($connection->read()->getMessage()),
                            $password))
                        )
                )
            ));
            return $connection->read()->getCode() === Authentication::AUTHENTICATION_PERFORMED;
        }

    }
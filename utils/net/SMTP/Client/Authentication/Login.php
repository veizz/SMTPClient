<?php

    /**
     * @package utils.net.SMTP.Client.Authentication
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\Authentication\Login.php
     */
    namespace utils\net\SMTP\Client\Authentication;
    use utils\net\SMTP\Client\Connection;
    use utils\net\SMTP\Client\Authentication;
    use utils\net\SMTP\Client\Authentication\AbstractAuthentication;
    use \RuntimeException;

    class Login extends AbstractAuthentication implements Authentication
    {
        /**
         * Perform an AUTH LOGIN in SMTP server to authenticate the client.
         * 
         * @link http://www.ietf.org/rfc/rfc2554.txt
         * @param Connection $connection the client connection to authenticate
         * @return boolean
         */
        public function authenticate(Connection $connection)
        {
            $username = $this->getUsername();
            $password = $this->getPassword();

            if ($connection->write("AUTH LOGIN\r\n")) {
                $response = $connection->read();
                if ($response->getCode() === Authentication::ACCEPTED) {
                    $connection->write(sprintf("%s\r\n", base64_encode($username)));
                    $response = $connection->read();
                    if ($response->getCode() === Authentication::ACCEPTED) {
                        $connection->write(sprintf("%s\r\n", base64_encode($password)));
                        return $connection->read()->getCode() === Authentication::AUTHENTICATION_PERFORMED;
                    }
                } else {
                    if($response->getCode() === Authentication::UNRECOGNIZED_AUTHENTICATION_TYPE) {
                        $message = "Couldn't authenticate using the AUTH LOGIN mechanism.";
                        throw new RuntimeException($message, $unrecognized);
                    }
                }
            }
        }
    }
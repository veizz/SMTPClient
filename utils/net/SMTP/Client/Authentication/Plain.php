<?php

    /**
     * @package utils.net.SMTP.Client.Authentication
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\Authentication\Plain.php
     */
    namespace utils\net\SMTP\Client\Authentication;
    use utils\net\SMTP\Client\Connection;
    use utils\net\SMTP\Client\Authentication\AbstractAuthentication;
    use utils\net\SMTP\Client\Authentication;
    use \RuntimeException;

    class Plain extends AbstractAuthentication implements Authentication
    {
        
        /**
         * Perform client authentication using AUTH PLAIN mechanism.
         * 
         * @link http://www.ietf.org/rfc/rfc2554.txt
         * @param \utils\net\SMTP\SMTPConnection $connection the client connection to authenticate
         * @return boolean
         */
        public function authenticate(Connection $connection)
        {
            $username = $this->getUsername();
            $password = $this->getPassword();

            if($connection->write("AUTH PLAIN\r\n")) {
                $response = $connection->read();
                if ($response->getCode() === Authentication::ACCEPTED) {
                    $connection->write(sprintf("%s\r\n",base64_encode(sprintf("\0%s\0%s", $username, $password))));
                    return $connection->read()->getCode() === Authentication::AUTHENTICATION_PERFORMED;
                } else {
                    $unrecognized = Authentication::UNRECOGNIZED_AUTHENTICATION_TYPE;
                    if($response->getCode() === $unrecognized) {
                        $message = "Couldn't authenticate using the AUTH PLAIN mechanism.";
                        throw new RuntimeException($message, $unrecognized);
                    }
                }
            }
        }
    }
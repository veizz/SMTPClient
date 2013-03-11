<?php

    /**
     * @package utils.Net.SMTP.Authentication
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\Net\SMTP\Authentication\Login.php
     */
    namespace utils\Net\SMTP\Authentication;
    use utils\Net\SMTP\SMTPAuthenticator;
    use utils\Net\SMTP\Authentication\AbstractSMTPAuthentication;
    use utils\Net\SMTP\SMTPConnection;
    use \RuntimeException;

    class Plain extends AbstractSMTPAuthentication implements SMTPAuthenticator
    {
        
        /**
         * Perform client authentication using AUTH PLAIN mechanism.
         * @param \utils\Net\SMTP\SMTPConnection $connection the client connection to authenticate.
         * @return boolean|void
         * @link http://www.ietf.org/rfc/rfc2554.txt
         */
        public function authenticate(SMTPConnection $connection)
        {
            if($connection->isEstablished()) {
                $username = $this->getUsername();
                $password = $this->getPassword();
                
                if($connection->write("AUTH PLAIN\r\n")) {
                    $response = $connection->read();
                    if ($this->getResponseCode($response) === SMTPAuthenticator::ACCEPTED) {
                        $connection->write(sprintf("%s\r\n",base64_encode(sprintf("\0%s\0%s", $username, $password))));
                        return $this->getResponseCode($connection->read()) === SMTPAuthenticator::AUTHENTICATION_PERFORMED;
                    } else {
                        $unrecognized = SMTPAuthenticator::UNRECOGNIZED_AUTHENTICATION_TYPE;
                        if($this->getResponseCode($response) === $unrecognized) {
                            $message = "Couldn't authenticate using the AUTH PLAIN mechanism.";
                            throw new RuntimeException($message, $unrecognized);
                        }
                    }
                }
            }
        }
    }
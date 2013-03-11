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

    class Login extends AbstractSMTPAuthentication implements SMTPAuthenticator
    {
        /**
         * Perform an AUTH LOGIN in SMTP server to authenticate the client.
         * @param \utils\Net\SMTP\SMTPConnection $connection the client connection to authenticate.
         * @return void|boolean
         * @link http://www.ietf.org/rfc/rfc2554.txt
         */
        public function authenticate(SMTPConnection $connection)
        {
            if ($connection->isEstablished()) {
                $username = $this->getUsername();
                $password = $this->getPassword();
                
                if ($connection->write("AUTH LOGIN\r\n")) {
                    $response = $connection->read();
                    if ($this->getResponseCode($response) === SMTPAuthenticator::ACCEPTED) {
                        $connection->write(sprintf("%s\r\n", base64_encode($username)));
                        $response = $connection->read();
                        if ($this->getResponseCode($response) === SMTPAuthenticator::ACCEPTED) {
                            $connection->write(sprintf("%s\r\n", base64_encode($password)));
                            return $this->getResponseCode($connection->read()) === SMTPAuthenticator::AUTHENTICATION_PERFORMED;
                        }
                    } else {
                        $unrecognized = SMTPAuthenticator::UNRECOGNIZED_AUTHENTICATION_TYPE;
                        if($this->getResponseCode($response) === $unrecognized) {
                            $message = "Couldn't authenticate using the AUTH LOGIN mechanism.";
                            throw new RuntimeException($message, $unrecognized);
                        }
                    }
                }
            }
        }
    }
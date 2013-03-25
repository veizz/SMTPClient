<?php

    /**
     * @package utils.net.SMTP
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Factory.php
     */
    namespace utils\net\SMTP;
    use utils\net\SMTP\Client\Connection\TLSConnection;
    use utils\net\SMTP\Client\Connection\TCPConnection;
    use utils\net\SMTP\Client\Connection\SSLConnection;
    use utils\net\SMTP\Client\Authentication\Login;
    use utils\net\SMTP\Client\Authentication\Plain;
    use utils\net\SMTP\Client;
    use UnexpectedValueException;

    class Factory
    {

        const PROTOCOL_SSL = "ssl";
        const PROTOCOL_TLS = "tls";
        const PROTOCOL_TCP = "tcp";
        const MECHANISM_LOGIN = "LOGIN";
        const MECHANISM_PLAIN = "PLAIN";

        /**
         * Creates a client with provided components from the URL.
         * @param string $url the url and their components to create a client and connection or make an authentication
         * @throws UnexpectedValueException if the provided scheme or authentication method wasn't valid
         * @return Client
         */
        public static function createClient($url)
        {
            $parsed = parse_url($url);
            if (isset($parsed["scheme"], $parsed["host"], $parsed["port"])) {
                $host = $parsed["host"];
                $port = $parsed["port"];
                $protocol = $parsed["scheme"];

                switch ($protocol) {
                    case Factory::PROTOCOL_SSL:
                        $connection = new SSLConnection($host, $port);
                        break;

                    case Factory::PROTOCOL_TCP:
                        $connection = new TCPConnection($host, $port);
                        break;

                    case Factory::PROTOCOL_TLS:
                        $connection = new TLSConnection($host, $port);
                        break;

                    default:
                        $message = "Invalid or unsupported to connect over %s";
                        throw new UnexpectedValueException(sprintf($message, $protocol));
                        break;
                }

                if (isset($parsed["user"], $parsed["pass"], $parsed["fragment"])) {
                    $user = $parsed["user"];
                    $pswd = $parsed["pass"];
                    $mechanism = strtoupper($parsed["fragment"]);

                    switch ($mechanism) {
                        case Factory::MECHANISM_LOGIN:
                            $authentication = new Login($user, $pswd);
                            break;

                        case Factory::MECHANISM_PLAIN:
                            $authentication = new Plain($user, $pswd);
                            break;
                    }

                    $client = new Client($connection);
                    $client->authenticate($authentication);
                    return $client;
                }
            }
        }

        /**
         * Creates an message to be sended over SMTP
         * @param string $from the sender email address
         * @param string $receiver the receiver email address
         * @param string $subject the mail subject
         * @param string $content the content of message
         */
        public function createMessage($from, $receiver, $subject, $content)
        {
            
        }

    }
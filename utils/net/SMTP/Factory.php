<?php

    /**
     * @package utils.net.SMTP
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\ClientFactory.php
     */
    namespace utils\net\SMTP;
    use utils\net\SMTP\Client\Connection\TLSConnection;
    use utils\net\SMTP\Client\Connection\TCPConnection;
    use utils\net\SMTP\Client\Connection\SSLConnection;
    use utils\net\SMTP\Client\Authentication\Login;
    use utils\net\SMTP\Client\Authentication\Plain;
    use utils\net\SMTP\Client;
    use UnexpectedValueException;

    class ClientFactory
    {

        /**
         * Creates a client with provided components from the URL.
         * @param string $url the url and their components to create a client and connection or make an authentication
         * @throws UnexpectedValueException if the provided scheme or authentication method wasn't valid
         * @return Client
         */
        public static function create($url)
        {
            $parsed = parse_url($url);
            if (isset($parsed["scheme"], $parsed["host"], $parsed["port"])) {
                $host = $parsed["host"];
                $port = $parsed["port"];

                switch ($parsed["scheme"]) {
                    case "tls": $connection = new TLSConnection($host, $port); break;
                    case "ssl": $connection = new SSLConnection($host, $port); break;
                    case "tcp": $connection = new TCPConnection($host, $port); break;

                    default:
                        $message = "Invalid connection type, the scheme must be tls, ssl or tcp";
                        throw new UnexpectedValueException($message);
                        break;
                }

                $client = new Client($connection);
                if (isset($parsed["user"], $parsed["pass"], $parsed["fragment"])) {
                    $user = $parsed["user"];
                    $pswd = $parsed["pass"];
                    $mechanism = strtolower($parsed["fragment"]);

                    switch ($mechanism) {
                        case "login": $authentication = new Login($user, $pswd); break;
                        case "plain": $authentication = new Plain($user, $pswd); break;

                        default:
                            $message = "Invalid provided authentication mechanism %s";
                            throw new UnexpectedValueException(sprintf($message, $mechanism));
                            break;
                    }

                    $client->authenticate($authentication);
                }
                
                return $client;
            }
        }

    }
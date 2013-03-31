<?php

    /**
     * @package tests.utils.net.SMTP.Client.Connection
     * @filesource tests\utils\net\SMTP\Client\Connection\StreamWrapper.php
     * @author Tiago de Souza Ribeiro <tiago.sr@msn.com>
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    namespace tests\utils\net\SMTP\Client\Connection;
    use \ReflectionClass;

    class StreamWrapper
    {

        /**
         * Stream wrapper registered to a specified protocol
         * @var object
         */
        private static $streamWrapper;

        /**
         * Calls an function in the streamWrapper
         * @param string $name function to be called
         * @param array $args arguments to the function
         * @return null|mixed
         */
        public function __call($name, array $args)
        {
            if (!is_callable(array(self::$streamWrapper, $name))) {
                return NULL;
            } else {
                $reflection = new ReflectionClass(self::$streamWrapper);
                return $reflection->getMethod($name)->invokeArgs(self::$streamWrapper, $args);
            }
        }

        /**
         * Registers stream wrapper with specified protocol
         * @param object $streamWrapper the stream wrapper that handles the protocol stream
         * @param string $protocol the protocol to be registred
         * @return boolean
         */
        public static function register($streamWrapper, $protocol)
        {
            if (in_array($protocol, stream_get_wrappers())) {
                stream_wrapper_unregister($protocol);
            }
            
            self::$streamWrapper = $streamWrapper;
            return stream_wrapper_register($protocol, __CLASS__);
        }

    }
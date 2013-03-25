<?php

    /**
     * @package utils.net.SMTP.Message
     * @filesource \utils\net\SMTP\Message\Composer.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    namespace utils\net\SMTP\Message;
    use utils\net\SMTP\Message AS MailMessage;
    
    class Composer
    {
        
        /**
         * Message to be composed
         * @var Message
         */
        private $message;
        
        /**
         * - Constructor
         * Defines the mail message provided to compose.
         * @param Message $message the message to be composed
         */
        public function __construct(MailMessage $message)
        {
            $this->message = $message;
        }
        
        /**
         * Composes headers from the headers set
         * @param HeaderSet $headerSet the headers to compose
         * @return string
         */
        private function composeHeaders(HeaderSet $headerSet)
        {
            $headers = array();
            foreach($headerSet AS $header) {
                $headerString = (string) $header;
                if(!empty($headerString)) {
                    $headers[] = $header;
                }
            }
            
            return implode("\r\n", $headers);
        }
        
        /**
         * Composes the message
         * @return string
         */
        public function compose()
        {
            $headers = $this->composeHeaders($this->message->getHeaderSet());
            return $headers;
        }
        
    }
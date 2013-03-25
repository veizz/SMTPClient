<?php

    /**
     * @package utils.net.SMTP
     * @filesource \utils\net\SMTP\Message.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    namespace utils\net\SMTP;
    use utils\net\SMTP\Message\AddressList;
    use utils\net\SMTP\Message\HeaderSet;
    use utils\net\SMTP\Message\Address;
    use utils\net\SMTP\Message\Header\To;
    use utils\net\SMTP\Message\Header\Cc;
    use utils\net\SMTP\Message\Header\Bcc;
    use utils\net\SMTP\Message\Header\From;
    use utils\net\SMTP\Message\Header\Subject;
    use utils\net\SMTP\Message\Encodable;

    class Message implements Encodable
    {

        /**
         * Message encoding (default: ASCII)
         * @var string
         */
        private $encoding = "ASCII";
        
        /**
         * An set of message headers
         * @var HeaderSet
         */
        private $headerSet;

        /**
         * - Constructor
         * Adds some mail lists, to be filled in the future if necessary.
         * @return void
         */
        public function __construct()
        {
            $this->headerSet = new HeaderSet();
            $this->headerSet->insert(new To())
                            ->insert(new Cc())
                            ->insert(new Bcc())
                            ->insert(new From());
        }

        /**
         * Adds an email address to a header that is composed by email(s)
         * @param string $header the header name that is an AddressList
         * @param string $email the email address to be added in the list
         * @param string $name the author name of email address
         * @return Message
         */
        private function addAddressToList($header, $email, $name = NULL)
        {
            if (($header = $this->headerSet->getHeader($header)) instanceof AddressList) {
                $header->addAddress(new Address($email, $name));
                return $this;
            }
        }

        /**
         * Adds email address of a recipient.
         * @param string $email the email address to be added
         * @param string $name the author name of email address
         * @return Message
         */
        public function to($email, $name = NULL)
        {
            return $this->addAddressToList("To", $email, $name);
        }
        
        /**
         * Adds an carbon copy to specified email address
         * @param string $email the email address to be added
         * @param string $name the author name of email address
         * @return Message
         */
        public function cc($email, $name = NULL)
        {
            return $this->addAddressToList("Cc", $email, $name);
        }

        /**
         * Adds an blind carbon copy to specified email address
         * @param string $email the email address to be added
         * @param string $name the author name of email address
         * @return Message
         */
        public function bcc($email, $name = NULL)
        {
            return $this->addAddressToList("Bcc", $email, $name);
        }

        /**
         * Adds email address(es) of from sender(s) 
         * @param string $email the email address to be added
         * @param string $name the author name of email address
         * @return Message
         */
        public function from($email, $name = NULL)
        {
            return $this->addAddressToList("From", $email, $name);
        }
        
        /**
         * Defines the message subject
         * @param string $subject the subject of message
         * @return Message
         */
        public function subject($subject) {
            $this->headerSet->insert(new Subject($subject));
            return $this;
        }
        
        /**
         * @see Encodable::setEncoding()
         * @return void
         */
        public function setEncoding($encoding)
        {
            $this->encoding = $encoding;
            
            foreach($this->getHeaderSet() AS $header) {
                $header->setEncoding($encoding);
            }
        }
        
        /**
         * @see Encodable::getEncoding()
         * @return string
         */
        public function getEncoding()
        {
            return $this->encoding;
        }

        /**
         * Retrieves the headers of message
         * @return HeaderSet
         */
        public function getHeaderSet()
        {
            return $this->headerSet;
        }

    }
<?php

    /**
     * @package utils.net.SMTP.Message.Header
     * @filesource \utils\net\SMTP\Message\Header\From.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    namespace utils\net\SMTP\Message\Header;
    use utils\net\SMTP\Message\AbstractHeader;
    use utils\net\SMTP\Message\HeaderEncoder;
    use utils\net\SMTP\Message\Address;
    
    class From extends AbstractHeader
    {
        
        /**
         * Constructs a representation of mail header "From"
         * @param string $email the "sender" email address
         * @param string $name the author name of email address
         * @param string $encoding header encoding (if null it will be ASCII)
         */
        public function __construct($email, $name = NULL, $encoding = NULL)
        {
            $this->setEncoding($encoding);
            
            if(!is_null($name)) {
                $name = HeaderEncoder::encode($name, $this->getEncoding());
                $value = sprintf("%s <%s>", $name, $email);
            }
            
            parent::__construct("From", isset($value) ? $value : $email);
        }
    
    }
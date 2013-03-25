<?php

    /**
     * @package utils.net.SMTP.Message
     * @filesource utils\net\SMTP\Message\HeaderWrapper.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    namespace utils\net\SMTP\Message;
    use utils\net\SMTP\Message;
    use utils\net\SMTP\Message\Header;
    use utils\net\SMTP\Message\Header\Type\Structured;
    use utils\net\SMTP\Message\Header\Type\Unstructured;
    use utils\net\SMTP\Message\HeaderEncoder;
    use utils\net\SMTP\Message\Encoder;
    use \InvalidArgumentException;
    
    abstract class HeaderWrapper
    {

        /**
         * Wraps an structured header line
         * @param Header $header the structured header to wrap
         * @return string
         */
        private function wrapStructured(Structured $header)
        {
            $tmp = NULL;
            $lines = array();
            $value = $header->getValue();

            for ($i = 0, $l = strlen($value); $i < $l; ++$i) {
                $tmp .= $value[$i];
                
                if ($value[$i] === $header->getDelimiter()) {
                    array_push($lines, $tmp);
                    $tmp = NULL;
                }
            }
            
            return implode("\r\n", $lines);
        }
        
        /**
         * Wraps an unstructured header line
         * @param Unstructured $header the unstructured header to be wrapped
         * @param Encoder $encoder an encoder to encode the header (default: QuotedPrintable)
         * @return type
         */
        private function wrapUnstructured(Unstructured $header, Encoder $encoder = NULL)
        {
            $encoding = $header->getEncoding();
            return ($encoding === "ASCII") 
                   ? wordwrap($header->getValue(), 78, "\r\n") 
                   : HeaderEncoder::encode($header->getValue(), $encoding, $encoder);
        }
        
        /**
         * Wraps an structured/unstructured header line
         * @param Header $header the header to be wrapped
         * @param Encoder $encoder the encoder to encode an unstructured header (default: QuotedPrintable)
         * @throws \InvalidArgumentException if the provided header is not an instance of Structured or Unstructured
         * @return string
         */
        public function wrap(Header $header, Encoder $encoder = NULL)
        {
            if($header instanceof Structured) {
                return self::wrapStructured($header);
            } elseif($header instanceof Unstructured) {
                return self::wrapUnstructured($header, $encoder);
            } 
            
            $message = "We can wrap only Structured or Unstructured headers";
            throw new InvalidArgumentException($message);
        }

    }
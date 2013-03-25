<?php

    /**
     * @package utils.net.SMTP.Message.Encoder
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Message\Encoder\QuotedPrintable.php
     */
    namespace utils\net\SMTP\Message\Encoder;
    use utils\net\SMTP\Message\Encoder;

    class QuotedPrintable implements Encoder
    {

        /**
         * Encodes the provided data in quoted printable format
         * @param string $data the data to be encoded
         * @see Encoder::encodeString()
         * @return string
         */
        public function encodeString($data)
        {
            return quoted_printable_encode($data);
        }

        /**
         * Encodes a header in quoted printable format
         * @param string $header the header to be encoded
         * @see Encoder::encodeHeader()
         * @return string
         */
        public function encodeHeader($header, $charset = "UTF-8")
        {
            return mb_encode_mimeheader($header, $charset, "Q", "\r\n", 0);
        }

    }
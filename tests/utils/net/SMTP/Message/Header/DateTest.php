<?php

    /**
     * @filesource tests\utils\net\SMTP\Message\Header\DateTest.php
     * @author Tiago de Souza Ribeiro <tiago.sr@msn.com>
     */
    use utils\net\SMTP\Message\Header\Date;

    class DateTest extends PHPUnit_Framework_TestCase
    {

        public function testDateHeaderValueIsSameAsProvided()
        {
            $time = "Wed, 27 Mar 2013 17:20:17 -0300";
            $datetime = DateTime::createFromFormat(DateTime::RFC2822, $time);
            $date = new Date($datetime);
            $this->assertEquals($time, $date->getValue());
        }

        public function testDateHeaderNameIsEqualToDate()
        {
            $date = new Date();
            $this->assertEquals("Date", $date->getName());
        }

        public function testDateHeaderEncodingIsEmpty()
        {
            $date = new Date();
            $this->assertEmpty($date->getEncoding());
        }

        public function testDateHeaderToString()
        {
            $time = "Wed, 27 Mar 2013 17:20:17 -0300";
            $datetime = DateTime::createFromFormat(DateTime::RFC2822, $time);
            $date = new Date($datetime);
            $this->assertEquals("Date: " . $time, (string) $date);
        }

    }
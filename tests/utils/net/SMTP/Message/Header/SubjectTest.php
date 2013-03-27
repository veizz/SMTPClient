<?php

    /**
     * @filesource \tests\utils\net\SMTP\Message\Header\SubjectTest.php
     * @author Tiago de Souza Ribeiro <tiago.sr@msn.com>
     */
    use utils\net\SMTP\Message\Header\Subject;

    class SubjectTest extends PHPUnit_Framework_TestCase
    {

        public function testHeaderNameEqualsToSubject()
        {
            $subject = new Subject("test");
            $this->assertEquals("Subject", $subject->getName());
        }

        public function testHeaderInitialEncodingIsEqualToAscii()
        {
            $subject = new Subject("test");
            $this->assertEquals("ASCII", $subject->getEncoding());
        }

        public function testHeaderValueEqualsToProvidedSubject()
        {
            $subject = new Subject("test");
            $this->assertEquals("test", $subject->getValue());
        }

        public function testSubjectHeaderToStringWithOnlySubject()
        {
            $subject = new Subject("test");
            $this->assertEquals("Subject: test", (string) $subject);
        }

        public function testSubjectHeaderToStringWithSubjectAndUTF8Encoding()
        {
            $subject = new Subject("test", "UTF-8");
            $this->assertEquals("Subject: =?UTF-8?Q?test?=", (string) $subject);
        }

        /**
         * @expectedException BadMethodCallException
         */
        public function testSubjectHeaderWithNoSubjectProvided()
        {
            $subject = new Subject();
        }

    }
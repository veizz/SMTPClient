<?php

    use utils\net\SMTP\Message\Encoder\QuotedPrintable;

    class QuotedPrintableTest extends PHPUnit_Framework_TestCase
    {

        public function testQuotedPrintableEOL()
        {
            // Just test EOL.
            $this->assertEquals("=20", QuotedPrintable::EOL);
        }
        
        public function testEncodeString()
        {
            $quotedPrintable = new QuotedPrintable();
            $encodedString = $quotedPrintable->encodeString("Test áéíóúñãõê");
            $this->assertEquals("Test =C3=A1=C3=A9=C3=AD=C3=B3=C3=BA=C3=B1=C3=A3=C3=B5=C3=AA", $encodedString);
        }
        
        public function testEncodeHeaderWithDefaultEncodingAndItShouldBeWrapped()
        {
            $header = "X-Test: just testing áááíííóóóúúúãããõõõÊÊÊÊêêêê";
            $quotedPrintable = new QuotedPrintable();
            $encodedHeader = $quotedPrintable->encodeHeader($header);
            
            $parts  = "=?UTF-8?Q?X-Test:=20just=20testing=20=C3=A1=C3=A1=C3=A1=C3=AD=C3=AD=C3=AD=C3=B3=C3=B3=C3=";
            $parts .= "\r\n=B3=C3=BA=C3=BA=C3=BA=C3=A3=C3=A3=C3=A3=C3=B5=C3=B5=C3=B5=C3=8A=C3=8A=C3=";
            $parts .= "\r\n=8A=C3=8A=C3=AA=C3=AA=C3=AA=C3=AA?=";
            $this->assertEquals($parts, $encodedHeader);
        }
        
        public function testEncodeHeaderWithSmallHeader()
        {
            $header = "X-Test: hello world";
            $quotedPrintable = new QuotedPrintable();
            $encodedHeader = $quotedPrintable->encodeHeader($header);
            $this->assertEquals("=?UTF-8?Q?X-Test:=20hello=20world?=", $encodedHeader);
        }
        
    }
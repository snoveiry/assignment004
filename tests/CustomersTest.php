<?php

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;

class CustomersTest extends KernelTestCase {

    protected function assertIsValidJson($json)
    {
        $data = json_decode($json);
        return null !== $data;
    }

    protected function assertJsonResponse(Response $response, $status)
    {
        $content = $response->getContent();
        $this->assertIsValidJson($content);
    }

    public function testPOST() {
        $content = `{"social_security_number" : "654321","name" : "sebastian","address" : "Oslo 0560"}`;
        $this->assertIsValidJson($content);
    }

    public function testGET() {
        echo "this is test!!!!!!!";
    }

}

?>
<?php

namespace App\Tests\Controller;

use App\Controller\ApiController;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiControllerTest extends TestCase
{

    public function testHandleResponseAndRespondTypeError ()
    {
        $this->expectException('TypeError');
        $api = new ApiController();
        $api->handleResponseAndRespond('wrong type');
    }

    public function testHandleResponseAndRespond ()
    {
        $api = new ApiController();
        $response = new Response(200);

        $myResponse = $api->handleResponseAndRespond($response);
        $this->assertInstanceOf(JsonResponse::class, $myResponse);
        $this->assertEquals($response->getStatusCode(), 200);

        $response2 = new Response(404);
        $myResponse2 = $api->handleResponseAndRespond($response2);
        $this->assertEquals($myResponse2->getStatusCode(), 404);
    }
}
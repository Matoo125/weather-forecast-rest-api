<?php
/**
 * Created by PhpStorm.
 * User: matevrza
 * Date: 10/3/2018
 * Time: 11:00 AM
 */

namespace App\Controller;


use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends BaseController
{
    public function forecast (Request $request)
    {
        $location = $request->get('location') ?? '';
        $days = $request->get('days') ?? 1;
        $response = $this->http('forecast', $location, $days);
        return $this->handleResponseAndRespond($response);
    }

    public function current (Request $request)
    {
        $location = $request->get('location') ?? '';
        $response = $this->http('current', $location);
        return $this->handleResponseAndRespond($response);
    }

    public function http ($endpoint, $location, $days = null)
    {
        /** @var \GuzzleHttp\Client $client */
        $client   = $this->get('eight_points_guzzle.client.weather');
        $queryParams = [
            'key' => $this->container->getParameter('apixu_key'),
            'q' => $location,
        ];
        if ($days) $queryParams['days'] = $days;

        $response = $client->get('/v1/' . $endpoint . '.json', [
            'query' => $queryParams
        ]);

        return $response;

    }

    public function handleResponseAndRespond (Response $response)
    {
        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents());
            return new JsonResponse($data);
        } else {
            return new JsonResponse(['404' => 'not found'], 404);
        }
    }

}
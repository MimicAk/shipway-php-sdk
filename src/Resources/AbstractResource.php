<?php

namespace MimicAk\ShipwayPhpSdk\Resources;

use MimicAk\ShipwayPhpSdk\Client\HttpClient;
use MimicAk\ShipwayPhpSdk\Client\ResponseHandler;

/**
 * Base class for all API resources
 */
abstract class AbstractResource
{
    protected HttpClient $httpClient;
    protected ResponseHandler $responseHandler;
    protected string $resourcePath;


    public function __construct(HttpClient $httpClient, string $resourcePath)
    {
        $this->httpClient = $httpClient;
        $this->responseHandler = new ResponseHandler();
        $this->resourcePath = $resourcePath;
    }

    protected function get(string $endpoint = '', array $query = []): array
    {
        $uri = $this->resourcePath . $endpoint;


        $query = array_filter($query, fn($v) => $v !== null);

        if (!empty($query)) {
            $uri .= '?' . http_build_query($query);
        }

        // var_dump($uri);
        $response = $this->httpClient->request('GET', $uri);

        // var_dump($response);
        return $this->responseHandler->handle($response);
    }

    protected function post(array $data, string $endpoint = ''): array
    {
        $uri = $this->resourcePath . $endpoint;
        $response = $this->httpClient->request('POST', $uri, [
            'json' => $data,
        ]);
        return $this->responseHandler->handle($response);
    }

    protected function put(string $endpoint, array $data): array
    {
        $uri = $this->resourcePath . $endpoint;
        $response = $this->httpClient->request('PUT', $uri, [
            'json' => $data,
        ]);
        return $this->responseHandler->handle($response);
    }

    protected function delete(string $endpoint): array
    {
        $uri = $this->resourcePath . $endpoint;
        $response = $this->httpClient->request('DELETE', $uri);
        return $this->responseHandler->handle($response);
    }
}

<?php

namespace App;

use GuzzleHttp\Exception\ClientException;

class Runtime
{

    private function getNextRequest()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get(sprintf('http://%s/2018-06-01/runtime/invocation/next', getenv('AWS_LAMBDA_RUNTIME_API')));

        return [
            'invocationId' => $response->getHeader('Lambda-Runtime-Aws-Request-Id')[0],
            'payload' => json_decode((string)$response->getBody(), true)
        ];
    }

    private function sendResponse(string $invocationId, APIResponse $response)
    {
        $client = new \GuzzleHttp\Client();
        $client->post(
            sprintf('http://%s/2018-06-01/runtime/invocation/%s/response', getenv('AWS_LAMBDA_RUNTIME_API'), $invocationId),
            [
                'body' => json_encode($response)
            ]
        );
    }

    public function run()
    {
        $client = new \GuzzleHttp\Client();

        while (true) {
            $event = $this->getNextRequest();
            $request = new APIRequest($event['payload']);

            try {
                $response = $client->request(
                    $request->getMethod(),
                    $request->getUrl(),
                    [
                        "form_params" => $request->getBody(),
                        "headers" => $request->getHeaders()
                    ]
                );
                $response = new APIResponse(
                    $response->getStatusCode(),
                    $response->getHeaders(),
                    $response->getBody()->getContents()
                );
            } catch (ClientException $exception) {
                $response = new APIResponse(
                    $exception->getResponse()->getStatusCode(),
                    $exception->getResponse()->getHeaders(),
                    $exception->getResponse()->getBody()->getContents()
                );
            }

            $this->sendResponse($event['invocationId'], $response);
        }
    }
}
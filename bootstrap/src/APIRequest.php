<?php

namespace App;

class APIRequest
{
    const LOCAL_SERVER = "127.0.0.1:8080";

    private string $httpMethod;
    private string $url;
    private array $headers;
    private array $body;

    public function __construct(array $event)
    {
        $this->httpMethod = $event["httpMethod"];
        $query = $event['queryStringParameters'] ? '?' . http_build_query($event['queryStringParameters']) : "";
        $this->url = $event['path'] . $query;
        $this->headers = $event["headers"];
        $this->body = json_decode($event["body"], true) ?? [];
    }

    public function getMethod(): string
    {
        return $this->httpMethod;
    }

    public function getUrl(): string
    {
        return self::LOCAL_SERVER . "/" . $this->url;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
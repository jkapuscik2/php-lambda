<?php

namespace App;

class APIResponse implements \JsonSerializable
{
    const STATUS_CODE = "statusCode";
    const HEADERS = "headers";
    const BODY = "body";

    private int $statusCode;
    private array $headers = [];
    private string $body;

    public function __construct(int $statusCode, array $headers, string $body)
    {
        $this->statusCode = $statusCode;
        foreach ($headers as $name => $values) {
            $this->headers[$name] = implode(", ", $values);
        }
        $this->body = $body;
    }

    public function jsonSerialize()
    {
        return [
            self::BODY => $this->body,
            self::STATUS_CODE => (string)$this->statusCode,
            self::HEADERS => $this->headers
        ];
    }
}
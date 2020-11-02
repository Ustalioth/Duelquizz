<?php
namespace App\Core\Routing;

class Route
{
    protected string $path;

    protected $action;

    protected string $method;


    public function __construct(
        string $path,
        string $action,
        ?string $method = 'GET'
    ) {
        $this->path = $path;
        $this->action = $action;
        $this->method = strtoupper($method);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getMethod(): ?string
    {
        return $this->method ?: 'GET';
    }
}

<?php
namespace App\Core\Routing;

class Route
{
    protected string $path;

    protected $action;

    protected array $method;


    public function __construct(
        string $path,
        string $action,
        $method = 'GET'
    ) {
        $method = (array)$method;
        $method = array_map('strtoupper', $method);

        $this->path = $path;
        $this->action = $action;
        $this->method = $method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getMethod(): array
    {
        return $this->method ?: ['GET'];
    }
}

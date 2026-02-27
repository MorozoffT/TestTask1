<?php

namespace app\Utils;

class Request
{
    private array $request;
    private mixed $content;

    public function __construct()
    {
        $this->request = $_REQUEST;
        $this->content = file_get_contents('php://input');
    }

    public function getParam(string $paramName, bool $isRequired = false)
    {
        return $isRequired ? $this->request[$paramName] : $this->request[$paramName] ?? null;
    }

    public function getParamFromBody(string $paramName, bool $isRequired = false)
    {

        $data = json_decode($this->content, true);
        $param = $data[$paramName];
        if (!$isRequired) {
            return $param;
        } elseif (is_null($param)) {
            throw new \Exception("$paramName is required");
        } else {
            return $param;
        }
    }
}

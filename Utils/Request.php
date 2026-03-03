<?php

namespace app\Utils;

use app\Dto\StudentDto;

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
            throw new \Exception("Нет требуемого параметра.");
        } else {
            return $param;
        }
    }

    public function getAllParams(): array
    {
        return $this->request;
    }

    public function completeStudentDto(StudentDto $studentDto): StudentDto
    {
        $studentDto->setId($this->getParamFromBody('studentId', true));
        $studentDto->setGroupId($this->getParamFromBody('groupId', true));
        $studentDto->setFullName($this->getParamFromBody('fullName'));

        return $studentDto;
    }
}

<?php

namespace app\Controllers;

use app\Services\StudentService;
use app\Utils\Request;
use app\Dto\StudentDto;

class StudentController
{
    private StudentService $studentService;

    public function __construct($entityManager)
    {
        $this->studentService = new StudentService($entityManager);
    }

    public function create(Request $request): void
    {
        $this->studentService->create(
            $request->getParamFromBody('fullName', true),
            $request->getParamFromBody('groupId', true)
        );
    }

    public function delete(Request $request): void
    {
        $this->studentService->delete($request->getParamFromBody('studentId', true));
    }

    public function get(Request $request): array
    {
        return $this->studentService->get($request->getParam('studentId'));
    }

    public function update(Request $request): void
    {
        $this->studentService->update($request->completeStudentDto(new StudentDto()));
    }
}
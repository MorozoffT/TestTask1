<?php

namespace app\Controllers;

use app\Services\StudentService;
use app\Utils\Request;

class StudentController
{
    private StudentService $studentService;

    public function __construct($entityManager)
    {
        $this->studentService= new StudentService($entityManager);
    }

    public function create(Request $request): void
    {
        $fullName = $request->getParamFromBody('fullName', true);
        $groupId = $request->getParamFromBody('groupId', true);

        $this->studentService->create($fullName, $groupId);
    }

    public function delete(Request $request): void
    {
        $studentId = $request->getParamFromBody('studentId', true);

        $this->studentService->delete($studentId);
    }

    public function get(Request $request): void
    {
        $studentId = $request->getParamFromBody('studentId');

        $this->studentService->get($studentId);
    }

    public function update(Request $request): void
    {
        $studentId = $request->getParamFromBody('studentId', true);
        $groupId = $request->getParamFromBody('groupId', true);
        $fullName = $request->getParamFromBody('fullName');

        $this->studentService->update($studentId, $groupId, $fullName);
    }
}
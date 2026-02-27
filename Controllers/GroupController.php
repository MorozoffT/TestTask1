<?php

namespace app\Controllers;

use app\Services\GroupService;
use app\Utils\Request;

class GroupController
{
    private GroupService $groupService;

    public function __construct($entityManager)
    {
        $this->groupService= new GroupService($entityManager);
    }

    public function create(Request $request): void
    {
        $title = $request->getParamFromBody('title', true);
        $course = $request->getParamFromBody('course', true);

        $this->groupService->create($title, $course);
    }

    public function delete(Request $request): void
    {
        $groupId = $request->getParamFromBody('groupId', true);

        $this->groupService->delete($groupId);
    }

    public function deleteStudents(Request $request): void
    {
        $groupId = $request->getParamFromBody('groupId', true);

        $this->groupService->deleteStudents($groupId);
    }

    public function get(Request $request): void
    {
        $groupId = $request->getParamFromBody('groupId');

        $this->groupService->get($groupId);
    }

    public function getStudents(Request $request): void
    {
        $groupId = $request->getParamFromBody('groupId', true);

        $this->groupService->getStudents($groupId);
    }

    public function update(Request $request): void
    {
        $groupId = $request->getParamFromBody('groupId', true);
        $title = $request->getParamFromBody('title');
        $course = $request->getParamFromBody('course');

        $this->groupService->update($groupId, $title, $course);
    }

    public function updateStudents(Request $request): void
    {
        $fromGroupId = $request->getParamFromBody('fromGroupId', true);
        $toGroupId = $request->getParamFromBody('toGroupId', true);

        $this->groupService->updateStudents($fromGroupId, $toGroupId);
    }

    public function getAllStudentsToPDF(Request $request): void
    {
        $groupId = $request->getParam('groupId', true);

        $this->groupService->getAllStudentsToPDF($groupId);
    }
}

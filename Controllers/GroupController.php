<?php

namespace app\Controllers;

use app\Dto\GroupDto;
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
        $this->groupService->create(
            $request->getParamFromBody('title', true),
            $request->getParamFromBody('course', true)
        );
    }

    public function delete(Request $request): void
    {
        $this->groupService->delete($request->getParamFromBody('groupId', true));
    }

    public function deleteStudents(Request $request): void
    {
        $this->groupService->deleteStudents($request->getParamFromBody('groupId', true));
    }

    public function get(Request $request): array
    {
        return $this->groupService->get($request->getParam('groupId'));
    }

    public function getStudents(Request $request): void
    {
        $this->groupService->getStudents($request->getParamFromBody('groupId', true));
    }

    public function update(Request $request): void
    {
        $group = (new GroupDto())
            ->setId($request->getParamFromBody('groupId', true))
            ->setTitle($request->getParamFromBody('title', true))
            ->setCourseNumber($request->getParamFromBody('course', true));

        $this->groupService->update($group);
    }

    public function updateStudents(Request $request): void
    {
        $this->groupService->updateStudents(
            $request->getParamFromBody('fromGroupId', true),
            $request->getParamFromBody('toGroupId', true)
        );
    }

    public function createStudentsListPDF(Request $request): void
    {
        $this->groupService->createStudentsListPDF($request->getParam('groupId', true));
    }
}

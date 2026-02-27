<?php

namespace app\Services;

use app\Entities\Group;
use Doctrine\ORM\EntityManager;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use TCPDF;


class GroupService
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function create(string $title, string $course): void
    {
        $newGroup = new Group();
        $newGroup->setTitle($title);
        $newGroup->setCourse($course);

        $this->entityManager->persist($newGroup);
        $this->entityManager->flush();
    }

    public function delete(string $groupId): void
    {
        $group = $this->entityManager->find(Group::class, $groupId);
        if (is_null($group)) {
            echo json_encode([
                'success' => false,
                'rows' => "Group $groupId does not exists"
            ]);
            exit;
        }

        $this->entityManager->remove($group);
        $this->entityManager->flush();
    }

    public function deleteStudents(string $groupId): void
    {
        $group = $this->entityManager->find(Group::class, $groupId);
        if (is_null($group)) {
            echo json_encode([
                'success' => false,
                'rows' => "Group $groupId does not exists"
            ]);
            exit;
        }

        $students = $group->getStudents();
        foreach ($students as $student) {
            $student->setGroup(null);
        }
    }

    public function get(string $groupId = null): void
    {
        $repository = $this->entityManager->getRepository(Group::class);

        if (is_null($groupId)) {
            $rows = $repository->findAll();
        } else {
            $rows = $repository->findBy(['id' => $groupId]);
        }

        echo json_encode([
            'success' => true,
            'rows' => $rows
        ], JSON_UNESCAPED_UNICODE);
    }

    public function getStudents(string $groupId): void
    {

        $group = $this->entityManager->find(Group::class, $groupId);
        if (is_null($group)) {
            echo json_encode([
                'success' => false,
                'rows' => "Group $groupId does not exists"
            ]);
            exit;
        }

        $students = $group->getStudents()->toArray();


        echo json_encode([
            'success' => true,
            'rows' => $students
        ], JSON_UNESCAPED_UNICODE);
    }

    public function update(string $groupId, string $title = null, string $course = null): void
    {
        $group = $this->entityManager->find(Group::class, $groupId);
        if (is_null($group)) {
            echo json_encode([
                'success' => false,
                'rows' => "Group $groupId does not exists"
            ]);
            exit;
        }

        if (!is_null($title)) {
            $group->setTitle($title);
        }
        if (!is_null($course)) {
            $group->setCourse($course);
        }

        $this->entityManager->flush();
    }

    public function updateStudents(string $fromGroupId, string $toGroupId): void
    {
        $fromGroup = $this->entityManager->find(Group::class, $fromGroupId);
        if (is_null($fromGroup)) {
            echo json_encode([
                'success' => false,
                'rows' => "Group $fromGroupId does not exists"
            ]);
            exit;
        }
        $toGroup = $this->entityManager->find(Group::class, $toGroupId);
        if (is_null($toGroup)) {
            echo json_encode([
                'success' => false,
                'rows' => "Group $toGroupId does not exists"
            ]);
            exit;
        }

        $students = $fromGroup->getStudents();
        foreach ($students as $student) {
            $student->setGroup($toGroup);
        }

        $this->entityManager->flush();
    }

    public function getAllStudentsToPDF(string $groupId): void
    {
        $group = $this->entityManager->find(Group::class, $groupId);
        if (is_null($group)) {
            echo json_encode([
                'success' => false,
                'rows' => "Group $groupId does not exists"
            ]);
            exit;
        }
        $students = $group->getStudents()->toArray();

        $loader = new FilesystemLoader(__DIR__ . '/../Templates');
        $twig = new Environment($loader);
        $html = $twig->render('students.html.twig', [
            'group_name' => $group->getTitle(),
            'students'   => $students
        ]);

        $pdf = new TCPDF();
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        $pdf->SetFont('dejavusans', '', 12);

        $pdf->writeHTML($html, true, false, true, false, '');

        $fileName = 'group_' . $group->getTitle() . '.pdf';
        $pdf->Output($fileName, 'D');
        exit;
    }
}

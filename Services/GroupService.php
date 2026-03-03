<?php

namespace app\Services;

use app\Dto\GroupDto;
use app\Entities\Group;
use app\Utils\MyException;
use Doctrine\ORM\EntityManager;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use TCPDF;

require_once __DIR__ . '/../Utils/ExistenceChecker.php';

class GroupService
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function create(string $title, int $course): void
    {
        $conn = $this->entityManager->getConnection();
        $conn->beginTransaction();
        try {
            $newGroup = (new Group())
                ->setTitle($title)
                ->setCourse($course);

            $this->entityManager->persist($newGroup);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function delete(int $groupId): void
    {
        $conn = $this->entityManager->getConnection();
        $conn->beginTransaction();
        try {
            $group = existenceGroupChecker($this->entityManager, $groupId);

            $this->entityManager->remove($group);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function deleteStudents(int $groupId): void
    {
        $conn = $this->entityManager->getConnection();
        $conn->beginTransaction();
        try {
            $group = existenceGroupChecker($this->entityManager, $groupId);

            $students = $group->getStudents();
            foreach ($students as $student) {
                $student->setGroup(null);
            }
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function get(int $groupId = null): array
    {
        $conn = $this->entityManager->getConnection();
        $conn->beginTransaction();
        try {
            $repository = $this->entityManager->getRepository(Group::class);

            if (is_null($groupId)) {
                $rows = $repository->findAll();
            } else {
                $rows = existenceGroupChecker($this->entityManager, $groupId)->toArray();
            }

            return $rows;
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function getStudents(int $groupId): array
    {
        $conn = $this->entityManager->getConnection();
        $conn->beginTransaction();
        try {
            $group = existenceGroupChecker($this->entityManager, $groupId);

            return $group->getStudents()->toArray();
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function update(GroupDto $groupDto): void
    {
        $conn = $this->entityManager->getConnection();
        $conn->beginTransaction();
        try {
            $group = existenceGroupChecker($this->entityManager, $groupDto->getId());

            $group->setTitle($groupDto->getTitle());
            $group->setCourse($groupDto->getCourseNumber());

            $this->entityManager->flush();
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function updateStudents(int $fromGroupId, int $toGroupId): void
    {
        $conn = $this->entityManager->getConnection();
        $conn->beginTransaction();
        try {
            $fromGroup = existenceGroupChecker($this->entityManager, $fromGroupId);
            $toGroup = existenceGroupChecker($this->entityManager, $toGroupId);

            $students = $fromGroup->getStudents();
            foreach ($students as $student) {
                $student->setGroup($toGroup);
            }

            $this->entityManager->flush();
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function createStudentsListPDF(int $groupId): void
    {
        $conn = $this->entityManager->getConnection();
        $conn->beginTransaction();
        try {
            $qb = $this->entityManager->createQueryBuilder();
            $qb->select('g')
                ->from(Group::class, 'g')
                ->where("g.id = $groupId");
            $group = $qb->getQuery()->getOneOrNullResult();

            if (is_null($group)) {
                throw new MyException('Группа не существует.');
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
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }
}

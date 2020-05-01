<?php

namespace App\Services;

use App\Entity\Task;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;

class Filter
{
    public function checkQueryNeedle(Request $request) :void
    {
        if ($request->request->get('name_asc')) {
            $_SESSION['query'] = ['user_name'=>'ASC'];
        } elseif ($request->request->get('name_desc')) {
            $_SESSION['query'] = ['user_name'=>'DESC'];
        }
        if ($request->request->get('email_asc')) {
            $_SESSION['query'] = ['email'=>'ASC'];
        } elseif ($request->request->get('email_desc')) {
            $_SESSION['query'] = ['email'=>'DESC'];
        }
        if ($request->request->get('completed_asc')) {
            $_SESSION['query'] = ['completed'=>'ASC'];
        } elseif ($request->request->get('completed_desc')) {
            $_SESSION['query'] = ['completed'=>'DESC'];
        }
    }

    public function getQueryIndexPage(EntityManager $entityManager, Request $request):Query
    {
        $this->checkQueryNeedle($request);
        $taskRepository = $entityManager->getRepository(Task::class);
        $filterKey = $_SESSION['query'];
        if ($filterKey) {
            $tasks = $taskRepository->createQueryBuilder('t')->orderBy('t.'.key($filterKey), $filterKey[key($filterKey)])->getQuery();
        } else {
            $tasks = $taskRepository->createQueryBuilder('t')->getQuery();
        }

        return $tasks;
    }

    public function getQueryAdminPage(EntityManager $entityManager) :Query
    {
        $taskRepository = $entityManager->getRepository(Task::class);

        return $taskRepository->createQueryBuilder('t')->getQuery();
    }
}

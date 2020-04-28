<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 2020-04-28
 * Time: 12:10
 */

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;

class TaskController
{
    public function index($twig, $manager, $paginator)
    {
        /** @var EntityManager $manager */
        $taskRepository = $manager->getRepository(Task::class);
        $filterKey = $_SESSION['query'];
        if($filterKey){
            $tasks = $taskRepository->findBy([],$filterKey);
        }
        else{
            $tasks = $taskRepository->findAll();
        }

        $request = Request::createFromGlobals();

        /** @var  Paginator $paginator */
        $pagination = $paginator->paginate(
            $tasks, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );
        $pages = ceil($pagination->getTotalItemCount()/3);
        $links = [];
        for ($i = 1;$i<=$pages;$i++){
            $links[$i]['link'] = $request->getBaseUrl().'/?page='.$i;
            $links[$i]['page'] = $i;
        }

        echo $twig->render('index.html.twig',[
            'tasks' => $pagination,
            'pages' => $links
        ]);
    }

    public function addTask(array $data, $manager)
    {
        $task = new Task();
        $task->setEmail($data['email']);
        $task->setText($data['text']);
        $task->setUserName($data['name']);
        $task->setEdited(false);
        $task->setCompleted(false);
        /** @var EntityManager $manager */
        $manager->persist($task);
        $manager->flush();
        header('Location:http://127.0.0.1');

    }
}

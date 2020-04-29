<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 2020-04-28
 * Time: 12:10
 */

namespace App\Controller;

use App\Entity\Task;
use App\Services\EmObject;
use App\Services\Filter;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController
{
    protected $manager;
    protected $paginator;
    protected $twig;
    protected $filterService;

    public function __construct()
    {
        $this->manager = new EmObject();
        $this->paginator = new Paginator();
        $loader = new \Twig\Loader\FilesystemLoader('../templates');
        $twig = new \Twig\Environment($loader);
        $this->twig = $twig;
        $this->filterService = new Filter();
    }

    public function index()
    {
        $request = Request::createFromGlobals();
        $this->filterService->checkQueryNeedle($request);
        $entityManager = $this->manager->getEm();
        $taskRepository = $entityManager->getRepository(Task::class);
        $filterKey = $_SESSION['query'];
        if($filterKey){
            $tasks = $taskRepository->findBy([],$filterKey);
        }
        else{
            $tasks = $taskRepository->findAll();
        }

        /** @var  Paginator $paginator */
        $pagination = $this->paginator->paginate(
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

        return new Response($this->twig->render('index.html.twig',[
            'tasks' => $pagination,
            'pages' => $links,
            'addTaskLink' => $request->getBaseUrl().'/addTask',
            'indexLink' => $request->getBaseUrl(),
            'loginLink' => $request->getBaseUrl().'/login'
        ]));

    }

    public function addTask()
    {
        $request = Request::createFromGlobals();
        $task = new Task();
        $task->setEmail($request->request->get('email'));
        $task->setText($request->request->get('text'));
        $task->setUserName($request->request->get('name'));
        $task->setEdited(false);
        $task->setCompleted(false);
        $manager = $this->manager->getEm();
        /** @var EntityManager $manager */
        $manager->persist($task);
        $manager->flush();
        header('Location:http://'.$request->getBaseUrl());
    }
}

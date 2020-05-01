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
use App\Services\Pagination;
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
    protected $paginationService;

    public function __construct()
    {
        $this->manager = new EmObject();
        $this->paginator = new Paginator();
        $loader = new \Twig\Loader\FilesystemLoader('../templates');
        $twig = new \Twig\Environment($loader);
        $this->twig = $twig;
        $this->filterService = new Filter();
        $this->paginationService = new Pagination();
    }

    public function index()
    {
        $request = Request::createFromGlobals();
        $entityManager = $this->manager->getEm();
        /** @var  Paginator $paginator */
        $pagination = $this->paginator->paginate(
            $this->filterService->getQueryIndexPage($entityManager, $request), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );
        $message = $_SESSION['Success_message'];
        unset($_SESSION['Success_message']);
        return new Response($this->twig->render('index.html.twig', [
            'tasks' => $pagination,
            'pages' => $this->paginationService->getLinks($pagination, $request),
            'addTaskLink' => '/addTask',
            'indexLink' => '',
            'loginLink' => '/login',
            'message' => $message
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
        $_SESSION['Success_message'] = 'Your request is successful!';
        header('Location:http://'.$_SERVER['HTTP_HOST']);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 2020-04-28
 * Time: 18:24
 */

namespace App\Controller;


use App\Entity\Task;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends TaskController
{
    public function index()
    {
        $request = Request::createFromGlobals();
        if($_SESSION['login'] === 'logged_in'){
            header('Location:http://'.$_SERVER['HTTP_HOST'].'/admin');
        }
        if($request->getMethod() === 'POST') {
            $login = $request->request->get('login');
            $password = $request->request->get('password');
            if ($login === 'admin' && $password === '123') {
                $_SESSION['login'] = 'logged_in';
                header('Location:http://'.$_SERVER['HTTP_HOST'].'/admin');
            } else {
                $message = 'Wrong Credentials!';
            }
        }
        return new Response($this->twig->render('login.html.twig',[
            'loginCheckLink' => $_SERVER['HTTP_HOST'].'/login',
            'errorMessage' => $message,
            'baseLink' => $_SERVER['HTTP_HOST'].'/'
        ]));
    }

    public function edit()
    {
        $request = Request::createFromGlobals();
        if($_SESSION['login'] !== 'logged_in'){
            header('Location:http://'.$_SERVER['HTTP_HOST'].'/login');
        }
        $entityManager = $this->manager->getEm();
        $taskRepository = $entityManager->getRepository(Task::class);
        $tasks = $taskRepository->findAll();

        /** @var  Paginator $paginator */
        $pagination = $this->paginator->paginate(
            $tasks, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );
        $pages = ceil($pagination->getTotalItemCount()/3);
        $links = [];
        for ($i = 1;$i<=$pages;$i++){
            $links[$i]['link'] = $request->getBaseUrl().'/admin?page='.$i;
            $links[$i]['page'] = $i;
        }

        return new Response($this->twig->render('admin.html.twig',[
            'tasks' => $pagination,
            'pages' => $links,
            'indexLink' => $_SERVER['HTTP_HOST'],
            'logoutLink' => $_SERVER['HTTP_HOST'].'/logout',
            'updateLink' => $_SERVER['HTTP_HOST'].'/update',
            'baseLink' => $_SERVER['HTTP_HOST'].'/'
        ]));
    }

    public function logout()
    {
        unset($_SESSION['login']);
        $request = Request::createFromGlobals();
        header('Location:http://'.$_SERVER['HTTP_HOST']);
    }

    public function update()
    {
        $request = Request::createFromGlobals();
        if($_SESSION['login'] !== 'logged_in'){
            header('Location:http://'.$_SERVER['HTTP_HOST'].'/login');
            die();
        }
        $entityManager = $this->manager->getEm();
        /* @var \App\Entity\Task $task*/
        $task = $entityManager->getRepository(Task::class)->find($request->request->get('submit'));
        $text = $request->request->get('text');
        $approve = $request->request->get('approve');
        if($approve == true){
            $task->setCompleted(true);
        }
        if($task->getText() !== $text){
            $task->setText($text);
            $task->setEdited(true);
        }
        $entityManager->persist($task);
        $entityManager->flush();
        header('Location:http://'.$_SERVER['HTTP_HOST'].'/admin');
    }
}

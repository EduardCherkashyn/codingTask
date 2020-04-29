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
            header('Location:http://'.$request->getBaseUrl().'/admin');
        }
        if($request->getMethod() === 'POST') {
            $login = $request->request->get('login');
            $password = $request->request->get('password');
            if ($login === 'admin' && $password === '123') {
                $_SESSION['login'] = 'logged_in';
                header('Location:http://'.$request->getBaseUrl().'/admin');
            } else {
                $message = 'Wrong Credentials!';
            }
        }
        return new Response($this->twig->render('login.html.twig',[
            'loginCheckLink' => $request->getBaseUrl().'/login',
            'errorMessage' => $message,
            'baseLink' => $request->getBaseUrl().'/'
        ]));
    }

    public function edit()
    {
        $request = Request::createFromGlobals();
        if($_SESSION['login'] !== 'logged_in'){
            header('Location:http://'.$request->getBaseUrl().'/login');
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
            'indexLink' => $request->getBaseUrl(),
            'logoutLink' => $request->getBaseUrl().'/logout',
            'updateLink' => $request->getBaseUrl().'/update',
            'baseLink' => $request->getBaseUrl().'/'
        ]));
    }

    public function logout()
    {
        unset($_SESSION['login']);
        $request = Request::createFromGlobals();
        header('Location:http://'.$request->getBaseUrl());
    }

    public function update()
    {
        $request = Request::createFromGlobals();
        if($_SESSION['login'] !== 'logged_in'){
            header('Location:http://'.$request->getBaseUrl().'/login');
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
        header('Location:http://'.$request->getBaseUrl().'/admin');
    }
}

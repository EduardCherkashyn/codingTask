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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends TaskController
{
    public function index(Request $request)
    {
        unset($_SESSION['query']);
        if ($_SESSION['login'] === 'logged_in') {
            return new RedirectResponse('/admin');

        }
        if ($request->getMethod() === 'POST') {
            $login = $request->request->get('login');
            $password = $request->request->get('password');
            if ($login === 'admin' && $password === '123') {
                $_SESSION['login'] = 'logged_in';
                return new RedirectResponse('/admin');
            }
            $message = 'Wrong Credentials!';
        }

        return new Response($this->twig->render('login.html.twig', [
            'loginCheckLink' => '/login',
            'errorMessage' => $message,
            'baseLink' => '/'
        ]));
    }

    public function edit(Request $request)
    {
        if ($_SESSION['login'] !== 'logged_in') {
            return new RedirectResponse('/login');
        }
        $entityManager = $this->manager->getEm();
        /** @var  Paginator $paginator */
        $pagination = $this->paginator->paginate(
            $this->filterService->getQueryAdminPage($entityManager), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );

        return new Response($this->twig->render('admin.html.twig', [
            'tasks' => $pagination,
            'pages' => $this->paginationService->getLinksAdmin($pagination, $request),
            'indexLink' => '',
            'logoutLink' => '/logout',
            'updateLink' => '/update',
            'baseLink' => '/'
        ]));
    }

    public function logout(Request $request)
    {
        session_destroy();
        if (!$request->isXmlHttpRequest()) {
            return new RedirectResponse('/');
        }
    }

    public function update(Request $request)
    {
        if ($_SESSION['login'] !== 'logged_in') {
            return new RedirectResponse('/login');
        }
        $entityManager = $this->manager->getEm();
        /* @var \App\Entity\Task $task*/
        $task = $entityManager->getRepository(Task::class)->find($request->request->get('submit'));
        $text = $request->request->get('text');
        $approve = $request->request->get('approve');
        if ($approve == true) {
            $task->setCompleted(true);
        } else{
            $task->setCompleted(false);
        }
        if ($task->getText() !== $text) {
            $task->setText($text);
            $task->setEdited(true);
        }
        $entityManager->persist($task);
        $entityManager->flush();

        return new RedirectResponse('/admin');
    }
}

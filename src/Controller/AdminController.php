<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 2020-04-28
 * Time: 18:24
 */

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends TaskController
{
    public function index()
    {
        $request = Request::createFromGlobals();
        if($request->getMethod() === 'POST') {
            $login = $request->request->get('login');
            $password = $request->request->get('password');
            if ($login === 'admin' && $password === '123') {
                header('Location:http://' . $request->getBaseUrl());
            } else {
                $message = 'Wrong Credentials!';
            }
        }
        return new Response($this->twig->render('login.html.twig',[
            'loginCheckLink' => $request->getBaseUrl().'/login',
            'errorMessage' => $message
        ]));
    }
}

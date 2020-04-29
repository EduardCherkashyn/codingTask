<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

class Filter
{
    public function checkQueryNeedle(Request $request)
    {
        session_start();
        if($request->request->get('name_asc')){
            $_SESSION['query'] = ['user_name'=>'ASC'];
        }elseif ($request->request->get('name_desc')){
            $_SESSION['query'] = ['user_name'=>'DESC'];
        }
        if($request->request->get('email_asc')){
            $_SESSION['query'] = ['email'=>'ASC'];
        }elseif ($request->request->get('email_desc')){
            $_SESSION['query'] = ['email'=>'DESC'];
        }
        if($request->request->get('completed_asc')){
            $_SESSION['query'] = ['completed'=>'ASC'];
        }elseif ($request->request->get('completed_desc')){
            $_SESSION['query'] = ['completed'=>'DESC'];
        }
    }
}

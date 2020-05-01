<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 2020-05-01
 * Time: 09:36
 */

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

class Pagination
{
    public function getLinks($pagination, Request $request) :array
    {
        $pages = ceil($pagination->getTotalItemCount()/3);
        $links = [];
        for ($i = 1;$i<=$pages;$i++) {
            $links[$i]['link'] = '/?page='.$i;
            $links[$i]['page'] = $i;
            if ($request->getRequestUri() === $links[$i]['link']) {
                $links[$i]['active'] = true;
            }
            if ($request->getRequestUri() === '/') {
                $links[1]['active'] = true;
            }
        }

        return $links;
    }

    public function getLinksAdmin($pagination, Request $request) :array
    {
        $pages = ceil($pagination->getTotalItemCount()/3);
        $links = [];
        for ($i = 1;$i<=$pages;$i++) {
            $links[$i]['link'] = '/admin?page='.$i;
            $links[$i]['page'] = $i;
            if ($request->getRequestUri() === $links[$i]['link']) {
                $links[$i]['active'] = true;
            }
            if ($request->getRequestUri() === '/admin') {
                $links[1]['active'] = true;
            }
        }

        return $links;
    }
}

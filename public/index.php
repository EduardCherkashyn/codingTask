<?php
session_start();
use App\Controller\TaskController;

require_once '../vendor/autoload.php';
require_once '../bootstrap.php';

$container = new DI\Container();
$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader);
$task = $container->get(TaskController::class);

if ($_POST['name'] && $_POST['email'] && $_POST['text']){
    $task->addTask($_POST, $entityManager);
}
if($_POST['name_asc']){
    $_SESSION['query'] = ['user_name'=>'ASC'];
}elseif ($_POST['name_desc']){
    $_SESSION['query'] = ['user_name'=>'DESC'];
}
if($_POST['email_asc']){
    $_SESSION['query'] = ['email'=>'ASC'];
}elseif ($_POST['email_desc']){
    $_SESSION['query'] = ['email'=>'DESC'];
}
if($_POST['completed_asc']){
    $_SESSION['query'] = ['completed'=>'ASC'];
}elseif ($_POST['completed_desc']){
    $_SESSION['query'] = ['completed'=>'DESC'];
}
$paginator = new Knp\Component\Pager\Paginator();
$task->index($twig, $entityManager, $paginator);

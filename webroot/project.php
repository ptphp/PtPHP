<?php
include_once "../app/init.php";
Model_Session::session_start();

if(!empty($_GET['action']) && $_GET['action'] == 'session_destroy'){
    session_destroy();
    header("Location:/project.php");
}

$project = new Controller\Project();
$project->view_index(empty($_GET['app']) ? "":$_GET['app']);

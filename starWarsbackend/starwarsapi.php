<?php
error_reporting(E_ALL  & ~E_NOTICE & ~E_WARNING & ~E_COMPILE_WARNING & ~E_DEPRECATED & ~E_STRICT);
//If Some One Directly Access this Page Dont allow
if (empty($_POST)) {
    echo "Look like you are accessing this page directly. Please use api to access this page";
    exit;
}
require_once 'StarWars.php';

if ($_POST['findanswers']){
    $starWars = new StarWars();
    //Get The Answers
    $starWars->FindAllAnswers();
    $error = "Something went Wrong";
    if ($starWars->error != ''){
        $answers['errors'] = $starWars->error;
    } else {
        $answers['firstAnswer'] = ($starWars->firstAnswer != '') ? $starWars->firstAnswer : $error;
        $answers['secondAnswer'] = ($starWars->secondAnswer != '') ? $starWars->secondAnswer : $error;
        $answers['thirdAnswer'] = ($starWars->thirdAnswer != '') ? $starWars->thirdAnswer : $error;
    }
    echo json_encode($answers);
}

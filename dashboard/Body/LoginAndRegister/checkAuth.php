<?php
$adminget = new AdminAuthForm();
$data = json_decode($_POST['data'], true);
if (isset($data['AdminUsername']) && !empty($data['AdminUsername'])) {

    if ($adminget->authenticateUser($data['AdminUsername'], $data['AdminPassword'])) {
        $responseData = $adminget->getResponseData();
        echo $responseData;
    } else {
        $responseData = $adminget->getResponseData();
        echo $responseData;
    }
} else {
    json_encode($message = "empty post");
} ?>
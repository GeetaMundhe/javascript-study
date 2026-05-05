<?php

$data = json_decode(file_get_contents("php://input"), true);

$users = json_decode(file_get_contents("users.json"), true);


echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
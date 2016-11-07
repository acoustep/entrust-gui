<?php
return [
    "layout" => "entrust-gui::app",
    "route-prefix" => "entrust-gui",
    "pagination" => [
        "users" => 5,
        "roles" => 5,
        "permissions" => 5,
    ],
    "middleware" => 'entrust-gui.admin',
    "unauthorized-url" => '/login',
    "middleware-role" => 'admin',
    "confirmable" => false,
    "users" => [
        'deletable' => true,
        'fieldSearchable' => [],
    ],
];

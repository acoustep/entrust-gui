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
    "middleware-role" => 'admin',
    "confirmable" => false,
];

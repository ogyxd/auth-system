<?php

return [
    "database" => [
        "host" => getenv("DB_HOST"),
        "user" => getenv("DB_USER"),
        "pass" => getenv("DB_PASS"),
        "dbname" => getenv("DB_NAME"),
        "port" => getenv("DB_PORT")
    ]
];
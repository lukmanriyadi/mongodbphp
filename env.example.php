<?php
$variables = [
    'DB_USERNAME' => '',
    'DB_PASSWORD' => '',
];

foreach ($variables as $key => $value) {
    putenv("$key=$value");
}

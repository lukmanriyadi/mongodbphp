<?php
$variables = [
    'DB_USERNAME' => 'user_admin',
    'DB_PASSWORD' => 'Lukman123.',
];

foreach ($variables as $key => $value) {
    putenv("$key=$value");
}

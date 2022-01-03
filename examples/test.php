<?php

echo json_encode(['method' => $_SERVER['REQUEST_METHOD']]);

echo '<br>';

print_r(getallheaders());

echo '<br>';

if ($_REQUEST) {
    print_r($_REQUEST);
}
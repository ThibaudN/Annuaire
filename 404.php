<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";
header("HTTP/1.0 404 Not Found");
echo '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="robots" content="noindex"><title>404</title><link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"><link rel="shortcut icon" href="'.URLSITE.'favicon.ico"><meta name="viewport" content="width=device-width, initial-scale=1"></head><body>404</body></html>';
?>
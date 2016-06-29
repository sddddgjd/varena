<?php
require_once '../lib/Util.php';

$cookie_name = 'locale';
setcookie('locale', Request::get('locale'));
Http::redirect(Request::get('url'));
?>
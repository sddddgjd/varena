<?php
require_once '../lib/Util.php';

$locale=Request::get('locale');
$availableLocales=Util::$availableLocales;
$validLocale=false;

foreach($availableLocales as $curLocale) {
  if($curLocale == $locale)
  	$validLocale=true;
}
if($validLocale==false){
  FlashMessage::add(_('Invalid locale. '));
  Http::redirect('index.php');
}

setcookie('locale', $locale);
Http::redirect(Request::get('url'));
?>
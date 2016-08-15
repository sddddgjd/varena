<?php

class UserDesc extends BaseObject {
  function getHtml() {
      $this->html = StringUtil::textile($this->description,true);
    return $this->html;
  }
}

?>

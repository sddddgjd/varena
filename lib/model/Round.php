<?php

class Round extends BaseObject {

  private $user = null;
  private $html = null;

  const STATUS_EXPIRED = 0;
  const STATUS_ONGOING = 1;
  const STATUS_UPCOMING = 2;

  function getUser() {
    if (!$this->user) {
      $this->user = User::get_by_id($this->userId);
    }
    return $this->user;
  }

  function getHtml() {
    if ($this->html === null) {
      $this->html = StringUtil::textile($this->description);
    }
    return $this->html;
  }

  function getStatus() {
    $now = time();
    $valid_until = $this->start + ($this->duration * 60);

    if ($now < $this->start) {
      return $this::STATUS_UPCOMING;
    }
    else if ($now > $this->start && $now < $valid_until) {
      return $this::STATUS_ONGOING;
    }
    else if ($now > $valid_until) {
      return $this::STATUS_EXPIRED;
    }
  }

  function getProblems() {
    return Model::factory('Problem')
      ->table_alias('p')
      ->select('p.*')
      ->join('round_problem', ['p.id', '=', 'rp.problemId'], 'rp')
      ->where('rp.roundId', $this->id)
      ->order_by_asc('rp.rank')
      ->find_many();
  }

  /**
   * Validates a round for correctness. Returns an array of { field => array of errors }.
   **/
  function validate() {
    $errors = [];

    if (mb_strlen($this->name) < 3) {
      $errors['name'][] = _('The name must be at least three characters long.');
    }

    $other = Model::factory('Round')
           ->where('name', $this->name)
           ->where_not_equal('id', (int) $this->id) // could be "" when adding a new problem
           ->find_one();
    if ($other) {
      $errors['name'] = _('There already exists a round with this name.');
    }

    if (!$this->start) {
      $errors['start'] = _('The start date/time cannot be empty.');
    }

    if ($this->duration <= 0) {
      $errors['duration'] = _('The duration must be positive.');
    }

    return $errors;
  }

}

?>

<?php
abstract class RankingStrategy {

  protected $orderBy = 'score';
  protected $rankingProperty = 'ranking';
  protected $last_rankable = null;

  public function setOrderBy($property) {
    $this->orderBy = $property;
  }
 
  /**
   * Set the property to store the ranking in.
   * @param String $property Ranking property
   */
  public function setRankingProperty($property) {
    $this->rankingProperty = $property;
  }

  public function rank($sortedRankables) {
    $this->last_rankable = null;
    foreach ($sortedRankables as $ranking_index => $rankable) {
      $this->assignRanking($rankable, $ranking_index);
      $this->last_rankable = $rankable;
    }
  }

  protected function assignRanking($rankable, $ranking_index) {
    $property = $this->orderBy;
    if ($this->last_rankable == null) {
      $this->whenFirst($rankable, $ranking_index);
    } else if ($rankable->$property == $this->last_rankable->$property) {
      $this->whenEqual($rankable, $ranking_index);
    } else {
      $this->whenDifferent($rankable, $ranking_index);
    }
  }

  protected function whenFirst($rankable, $ranking_index) {
    $this->setRanking($rankable, 1);
  }

  abstract protected function whenEqual($rankable, $ranking_index);

  abstract protected function whenDifferent($rankable, $ranking_index);
  
  protected function setRanking(&$rankable, $ranking) {
    $rankable->{$this->rankingProperty} = $ranking;
  }
}

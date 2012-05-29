<?php
/**
 * Dense ranking strategy ( 1223 )
 */ 
class DenseStrategy extends RankingStrategy {

  protected function whenEqual($rankable, $ranking_index) {
    $this->setRanking($rankable, $this->last_rankable->ranking);
  }

  protected function whenDifferent($rankable, $ranking_index) {
    $this->setRanking($rankable, $this->last_rankable->ranking + 1);
  }

} 

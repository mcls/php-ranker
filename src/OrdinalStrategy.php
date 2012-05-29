<?php
/**
 * Ordinal ranking strategy ( 1234 )
 */ 
class OrdinalStrategy extends RankingStrategy {

  protected function assignRanking($rankable, $ranking_index) {
    $this->setRanking($rankable, $ranking_index + 1);
  }
  
  // Not used
  protected function whenEqual($rankable, $ranking_index) {}
  protected function whenDifferent($rankable, $ranking_index) {}

}


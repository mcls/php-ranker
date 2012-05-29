<?php
/**
 * Standard competition strategy ( 1224 )
 */ 
class StandardCompetitionStrategy extends RankingStrategy {
  
  protected function whenEqual($rankable, $ranking_index) {
    $this->setRanking($rankable, $this->last_rankable->ranking);
  }

  protected function whenDifferent($rankable, $ranking_index) {
    $this->setRanking($rankable, $ranking_index + 1);
  }

}

<?php
/**
 * Standard competition strategy ( 1224 )
 */ 
class StandardCompetitionStrategy extends RankingStrategy {
  
  protected function whenEqual($rankable, $ranking_index) {
    $rankable->ranking = $this->last_rankable->ranking;
  }

  protected function whenDifferent($rankable, $ranking_index) {
    $rankable->ranking = $ranking_index + 1;
  }

}

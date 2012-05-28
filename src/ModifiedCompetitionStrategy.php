<?php
/**
 * Modified competition strategy ( 1334 )
 * Leaves the gaps in the ranking numbers before the sets of equal-ranking 
 * items (rather than after them as in standard competition ranking)
 */
class ModifiedCompetitionStrategy extends RankingStrategy {

  private $equalRanking = array();

  protected function whenEqual($rankable, $ranking_index) {
    $this->updateEqualRankingItems($rankable, $ranking_index);
  }
  
  private function updateEqualRankingItems($rankable, $ranking_index) {
    $this->equalRanking[] = $rankable;
    // ranking = value after gap = $ranking_index + 1
    foreach ($this->equalRanking as $r) {
      $r->ranking = $ranking_index + 1;
    }
  }

  protected function whenDifferent($rankable, $ranking_index) {
    $rankable->ranking = $ranking_index + 1;
    $this->equalRanking = array($rankable);
  }

}

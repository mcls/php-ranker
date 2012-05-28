<?php
/**
 * Modified competition strategy ( 1334 )
 */
class ModifiedCompetitionStrategy extends RankingStrategy {

  private $equally_ranked = array();

  protected function whenEqual($rankable, $ranking_index) {
    $this->equally_ranked[] = $rankable;
    $this->updateEquallyRanked($ranking_index);
  }
  
  private function updateEquallyRanked($ranking_index) {
    foreach ($this->equally_ranked as $r) {
      $r->ranking = $ranking_index + 1;
    }
  }

  protected function whenDifferent($rankable, $ranking_index) {
    $rankable->ranking = $ranking_index + 1;
    $this->equally_ranked = array($rankable);
  }

}

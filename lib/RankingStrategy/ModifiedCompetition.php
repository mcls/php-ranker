<?php
/**
 * Copyright 2012 Maarten Claes
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Ranker\RankingStrategy;

/**
 * Modified competition strategy ( 1334 )
 * Leaves the gaps in the ranking numbers before the sets of equal-ranking
 * items (rather than after them as in standard competition ranking)
 */
class ModifiedCompetition extends Base {

  private $equalRanking = array();

  protected function whenEqual($rankable, $ranking_index) {
    $this->updateEqualRankingItems($rankable, $ranking_index);
  }

  private function updateEqualRankingItems($rankable, $ranking_index) {
    $this->equalRanking[] = $rankable;
    // ranking = value after gap = $ranking_index + 1
    foreach ($this->equalRanking as $r) {
      $this->setRanking($r, $ranking_index + 1);
    }
  }

  protected function whenDifferent($rankable, $ranking_index) {
    $this->setRanking($rankable, $ranking_index + 1);
    $this->equalRanking = array($rankable);
  }

}

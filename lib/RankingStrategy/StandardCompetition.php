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
 * Standard competition strategy ( 1224 )
 */
class StandardCompetition extends Base {

  protected function whenEqual($rankable, $ranking_index) {
    $this->setRanking($rankable, $this->last_rankable->ranking);
  }

  protected function whenDifferent($rankable, $ranking_index) {
    $this->setRanking($rankable, $ranking_index + 1);
  }

}

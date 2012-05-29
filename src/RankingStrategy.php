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

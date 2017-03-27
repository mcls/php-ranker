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

namespace Ranker;

use Ranker\RankingStrategy\Ordinal;
use Ranker\RankingStrategy\StandardCompetition;
use Ranker\RankingStrategy\ModifiedCompetition;
use Ranker\RankingStrategy\Dense;

class Ranker {

  // Ranking strategy
  private $strategyName = 'ordinal';
  private $strategy;
  // Options
  private $rankingProperty = 'ranking';
  private $orderBy = 'score';
  private $descending = TRUE;

  public function __construct() {
    $this->strategy = new Ordinal();
  }

  /**
   * Sets a ranking strategy. The possibilities are:
   * - 'competition' : Standard Competition Ranking ( 1224 )
   * - 'modified'    : Modified Competition Ranking ( 1334 )
   * - 'dense'       : Dense Ranking                ( 1223 )
   * - 'ordinal'     : Ordinal Ranking              ( 1234 )
   *
   * @param String  $strategyName  Name of the strategy. ('competition', 'modified', 'dense' or 'ordinal')
   */
  public function useStrategy($strategyName) {
    switch ($strategyName) {
      case 'competition':
        $this->strategy = new StandardCompetition();
        break;
      case 'modified':
        $this->strategy = new ModifiedCompetition();
        break;
      case 'dense':
        $this->strategy = new Dense();
        break;
      case 'ordinal':
        $this->strategy = new Ordinal();
        break;
      default:
        throw new \Exception("Ranking strategy '$strategyName' not found!");
    }
    $this->strategyName = $strategyName;
    return $this;
  }

  public function getRankingStrategy() {
    return $this->strategyName;
  }

  /**
   * Set the property to store the ranking in.
   * @param String $property Ranking property
   */
  public function storeRankingIn($property) {
    $this->rankingProperty = $property;
    return $this;
  }

  /**
   * Set the name of the property on which the objects' ranking will be based on ( default is 'score' ).
   * @param String  The name of the property to base the ranking on.
   * @param Boolean $descending Ascending or descending.
   */
  public function orderBy($property, $descending = TRUE) {
    $this->orderBy = $property;
    $this->descending = $descending;
    return $this;
  }

  /**
   * Ranks and sorts the provided array. The ranking number will be added to
   * the 'ranking' property of the objects.
   * @param Array &$rankables Array of objects to rank
   * @param Boolean $sortBeforeRanking Whether or not to sort the rankables before asigning ranks
   */
  public function rank(&$rankables, $sortBeforeRanking = TRUE) {
    if ($sortBeforeRanking) {
      $this->sort($rankables);
    }
    $this->strategy->setOrderBy($this->orderBy);
    $this->strategy->setRankingProperty($this->rankingProperty);
    $this->strategy->rank($rankables);
  }

  /**
   * Sort the provided array without assigning rankings.
   * @param Array &$rankables Array of objects to sort.
   * @param Boolean $descending Ascending or descending.
   */
  public function sort(&$rankables) {
    $sortBy = $this->createCompareFunction();
    usort($rankables, $sortBy);
  }

  private function createCompareFunction() {
    $orderBy = $this->orderBy;
    $descending = $this->descending;
    return function($object1, $object2) use ($orderBy, $descending) {
      $a = $object1->$orderBy;
      $b = $object2->$orderBy;
      if ( $a == $b ) {
        return 0;
      } else if ( $descending ) {
        return ( $a > $b ) ? -1 : 1;
      } else {
        return ( $a > $b ) ? 1 : -1;
      }
    };

  }

}

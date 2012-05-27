<?php
class Ranker {
  
  private $strategyName = 'ordinal'; 
  private $strategy;
  private $orderBy = 'score';

  public function __construct() {
    $this->strategy = new OrdinalStrategy();
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
  public function setRankingStrategy($strategyName) {
    switch ($strategyName) {
      case 'competition':
        $this->strategy = new StandardCompetitionStrategy();
        break;
      case 'modified':
        $this->strategy = new ModifiedCompetitionStrategy();
        break;
      case 'dense':
        $this->strategy = new DenseStrategy();
        break;
      case 'ordinal':
        $this->strategy = new OrdinalStrategy();
        break;
      default:
        throw new UnknownRankingStrategyException("Ranking strategy '$strategyName' not found!");
    } 
    $this->strategyName = $strategyName;
  }

  public function getRankingStrategy() {
    return $this->strategyName;
  }

  /**
   * Set the name of the property on which the objects' ranking will be based on ( default is 'score' ).
   * @param String  The name of the property to base the ranking on.
   */
  public function setOrderBy($property) {
    $this->orderBy = $property;
  }
  
  /**
   * Get the name of the property on which the objects' ranking will be based on ( default is 'score' ).
   * @return String  The name of the property to base the ranking on.
   */
  public function getOrderBy() {
    return $this->orderBy;
  }

  /**
   * Ranks and sorts the provided array. The ranking number will be added as a ranking property.
   * @param Array Array of objects to rank
   */
  public function rank(&$rankables, $descending = TRUE) {
    $this->strategy->setOrderBy($this->orderBy);
    $this->sort($rankables, $descending);
    $this->strategy->rank($rankables);
  }

  /**
   * Sort the provided array without assignin rankings. 
   * @param Array Array of objects to sort
   */
  public function sort(&$rankables, $descending = TRUE) {
    $orderBy = $this->orderBy;
    $compare = function($object1, $object2) use ($orderBy, $descending) {
      $a = $object1->$orderBy;
      $b = $object2->$orderBy;
      if ( $a == $b ) {
        return 0;
      }
      if ($descending) {
        return ( $a > $b ) ? -1 : 1;
      } else {
        return ( $a > $b ) ? 1 : -1;
      }
    };
    usort($rankables, $compare);
  }

}

abstract class RankingStrategy {
  protected $orderBy = 'score';
  protected $last_rankable = null;

  public function setOrderBy($property) {
    $this->orderBy = $property;
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
    $rankable->ranking = 1;
  }

  abstract protected function whenEqual($rankable, $ranking_index);
  abstract protected function whenDifferent($rankable, $ranking_index);
}


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


/**
 * Dense ranking strategy ( 1223 )
 */ 
class DenseStrategy extends RankingStrategy {
  protected function whenEqual($rankable, $ranking_index) {
    $rankable->ranking = $this->last_rankable->ranking;
  }

  protected function whenDifferent($rankable, $ranking_index) {
    $rankable->ranking = $this->last_rankable->ranking + 1;
  }
} 


/**
 * Ordinal ranking strategy ( 1234 )
 */ 
class OrdinalStrategy extends RankingStrategy {
  protected function assignRanking($rankable, $ranking_index) {
    $rankable->ranking = $ranking_index + 1;
  }
  protected function whenEqual($rankable, $ranking_index) {}
  protected function whenDifferent($rankable, $ranking_index) {}
}


class UnknownRankingStrategyException extends Exception {}

<?php
include 'src/RankingStrategy.php';
include 'src/StandardCompetitionStrategy.php';
include 'src/ModifiedCompetitionStrategy.php';
include 'src/DenseStrategy.php';
include 'src/OrdinalStrategy.php';

class Ranker {
  
  private $strategyName = 'ordinal'; 
  private $strategy;
  private $rankingProperty = 'ranking';
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
   */
  public function orderBy($property) {
    $this->orderBy = $property;
    return $this;
  }

  /**
   * Ranks and sorts the provided array. The ranking number will be added to 
   * the 'ranking' property of the objects.
   * @param Array &$rankables Array of objects to rank
   * @param Boolean $descending Ascending or descending.
   */
  public function rank(&$rankables, $descending = TRUE) {
    $this->strategy->setOrderBy($this->orderBy);
    $this->strategy->setRankingProperty($this->rankingProperty);
    $this->sort($rankables, $descending);
    $this->strategy->rank($rankables);
  }

  /**
   * Sort the provided array without assigning rankings. 
   * @param Array &$rankables Array of objects to sort.
   * @param Boolean $descending Ascending or descending.
   */
  public function sort(&$rankables, $descending = TRUE) {
    $orderBy = $this->orderBy;
    $compare = function($object1, $object2) use ($orderBy, $descending) {
      $a = $object1->$orderBy;
      $b = $object2->$orderBy;
      if ( $a == $b ) {
        return 0;
      }
      if ( $descending ) {
        return ( $a > $b ) ? -1 : 1;
      } else {
        return ( $a > $b ) ? 1 : -1;
      }
    };
    usort($rankables, $compare);
  }

}

class UnknownRankingStrategyException extends Exception {}

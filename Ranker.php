<?php
class Ranker {
  
  private $strategy = 'ordinal'; 
  private $rankFunction = 'rankUsingOrdinalRanking';
  private $orderBy = 'score';
  // Used in modified ranking strategy
  private $equally_ranked = array();

  public function __construct() {
  }

  public function setRankingStrategy($strategy) {
    switch ($strategy) {
      case RankingStrategy::COMPETITION:
        $this->rankFunction = 'rankUsingCompetionRanking'; 
        break;
      case RankingStrategy::MODIFIED_COMPETITION:
        $this->rankFunction = 'rankUsingModifiedCompetionRanking'; 
        break;
      case RankingStrategy::DENSE:
        $this->rankFunction = 'rankUsingDenseRanking'; 
        break;
      case RankingStrategy::ORDINAL:
        $this->rankFunction = 'rankUsingOrdinalRanking'; 
        break;
      default:
        throw new Exception("Ranking strategy '$strategy' not found!");
    } 
    $this->strategy = $strategy;
  }

  public function getRankingStrategy() {
    return $this->strategy;
  }

  /**
   * Set the name of the property on which the objects' ranking will be based on ( default is 'score' ).
   * @param String  The name of the property to base the ranking on.
   */
  public function setPropertyToSortOn($orderBy) {
    $this->orderBy = $orderBy;
  }
  
  /**
   * Get the name of the property on which the objects' ranking will be based on ( default is 'score' ).
   * @param String  The name of the property to base the ranking on.
   */
  public function getPropertyToSortOn($orderBy) {
    return $this->orderBy;
  }

  /**
   * Ranks and sorts the provided array. The ranking number will be added as a ranking property.
   * @param Array Array of objects to rank
   */
  public function rank(&$rankables) {
    $last_rankable = null;
    $rank = $this->rankFunction;
    $this->sortByOrderByParameter(&$rankables);
    foreach ($rankables as $ranking_index => $rankable) {
      $this->$rank($rankable, $last_rankable, $ranking_index);
      $last_rankable = $rankable;
    }
  }

  public function sortByOrderByParameter(&$rankables) {
    $orderBy = $this->orderBy;
    $compare = function($object1, $object2) use ($orderBy) {
      $a = $object1->$orderBy;
      $b = $object2->$orderBy;
      if ( $a == $b ) {
        return 0;
      }
      return ( $a > $b ) ? -1 : 1;
    };
    usort($rankables, $compare);
  }

  /**
   * Standard competition strategy ( 1224 )
   */ 
  public function rankUsingCompetionRanking(&$rankable, $last_rankable, $ranking_index) {
    if ($last_rankable == null) {
      $rankable->ranking = 1;
    } else if ($rankable->score == $last_rankable->score) {
      $rankable->ranking = $last_rankable->ranking;
    } else {
      $rankable->ranking = $ranking_index + 1;
    }
  }

  /**
   * Modified competition strategy ( 1334 )
   */
  public function rankUsingModifiedCompetionRanking(&$rankable, $last_rankable, $ranking_index) {
    if ($last_rankable == null) {
      $rankable->ranking = 1;
    } else if ($rankable->score == $last_rankable->score) {
      $this->equally_ranked[] = $rankable;
      $this->updateEquallyRanked($ranking_index);
    } else {
      $rankable->ranking = $ranking_index + 1;
      $this->equally_ranked = array($rankable);
    }
  }

  private function updateEquallyRanked($ranking_index) {
    foreach ($this->equally_ranked as $r) {
      $r->ranking = $ranking_index + 1;
    }
  }
  
  /**
   * Dense ranking strategy ( 1223 )
   */ 
  public function rankUsingDenseRanking(&$rankable, $last_rankable, $ranking_index = null) {
    if ($last_rankable == null) {
      $rankable->ranking = 1;
    } else if ($rankable->score == $last_rankable->score) {
      $rankable->ranking = $last_rankable->ranking;
    } else {
      $rankable->ranking = $last_rankable->ranking + 1;
    }
  }
  
  /**
   * Ordinal ranking strategy ( 1234 )
   */ 
  public function rankUsingOrdinalRanking(&$rankable, $last_rankable, $ranking_index = null) {
    $rankable->ranking = $ranking_index + 1;
  }
  
}

class RankingStrategy {
  const COMPETITION = 'competition';
  const MODIFIED_COMPETITION = 'mod_competition';
  const DENSE = 'dense';
  const ORDINAL = 'ordinal';
}

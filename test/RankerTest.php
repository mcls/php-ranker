<?php
class RankerTest extends PHPUnit_Framework_TestCase {

  private $rankables = array();
  private $ranker = null;

  public function setUp() {
    $this->ranker = new Ranker();

    $this->rankables[] = $this->createRankable("bbb", 75);  
    $this->rankables[] = $this->createRankable("ddd", 50);  
    $this->rankables[] = $this->createRankable("ccc", 75);  
    $this->rankables[] = $this->createRankable("aaa", 100);  

    $this->rankables[] = $this->createRankable("eee", 5);  
    $this->rankables[] = $this->createRankable("fff", 5);  
    $this->rankables[] = $this->createRankable("ggg", 5);  
    
    $this->rankables[] = $this->createRankable("hhh", 1);  
    $this->rankables[] = $this->createRankable("iii", 0);  
  }

  private function createRankable($name, $score, $rankingProperty = 'ranking') {
    return (object) array(
      'name' => $name,
      'score' => $score,
      'inverseScore' => 100 - $score,
      $rankingProperty => 0,
    );
  }

  public function testSettingRankingStrategy() {
    $this->ranker->setRankingStrategy('competition');
    $this->assertEquals("competition", $this->ranker->getRankingStrategy());
  }
 
  /**
   * @expectedException UnknownRankingStrategyException
   */
  public function testSettingNonExistingRankingStrategyThrowsException() {
    $this->ranker->setRankingStrategy('dmsqlfkjds');
  }

  public function testSort() {
    $this->ranker->sort($this->rankables);
    $this->assertFirstAndLastNameValue('aaa', 'iii');
  }
  
  public function testSortAscending() {
    $this->ranker->sort($this->rankables, FALSE);
    $this->assertFirstAndLastNameValue('iii', 'aaa');
  }

  private function assertFirstAndLastNameValue($expected_first, $expected_last) {
    $actual_first = $this->rankables[0]->name;
    $this->assertEquals($expected_first, $actual_first);
    $last = count($this->rankables) - 1;
    $actual_last = $this->rankables[$last]->name;
    $this->assertEquals($expected_last, $actual_last);
  }
  
  public function testCompetitionRanking() {
    $this->applyRankingStrategy('competition');
    $this->assertRanking("122455589", $this->rankables);
  }
  
  public function testModifiedCompetitionRanking() {
    $this->applyRankingStrategy('modified');
    $this->assertRanking("133477789", $this->rankables);
  }

  public function testDenseRanking() {
    $this->applyRankingStrategy('dense');
    $this->assertRanking("122344456", $this->rankables);
  }
  
  public function testOrdinalRanking() {
    $this->applyRankingStrategy('ordinal');
    $this->assertRanking("123456789", $this->rankables);
    $this->assertFirstAndLastNameValue('aaa', 'iii');
  }
  
  public function testSettingOrderBy() {
    $this->ranker->setOrderBy('inverseScore');
    $this->applyRankingStrategy('ordinal');
    $this->assertRanking("123456789", $this->rankables);
    $this->assertFirstAndLastNameValue('iii', 'aaa');
  }
  
  public function testAlternativeRankingProperty() {
    $this->ranker->setRankingProperty('alternateRankingProperty');
    $this->applyRankingStrategy('ordinal');

    $this->assertEquals(1, $this->rankables[0]->alternateRankingProperty);
  }
  
  /**
   * Helper to test ranking strategies
   */
  private function applyRankingStrategy($strategy) {
    $this->ranker->setRankingStrategy($strategy);
    $this->ranker->rank($this->rankables);
  }
  
  /** 
   * Helps asserting ranking methods work as expected.
   */
  private function assertRanking($expected, $rankables) {
    $actual = "";
    foreach ($rankables as $rankable) {
      $actual .= $rankable->ranking;
    }
    $this->assertEquals($expected, $actual);
  }

}

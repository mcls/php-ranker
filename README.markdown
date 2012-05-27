# Ranker
A simple class to assign rankings to objects in an array based on one of their properties.

Use `setRankingStrategy()` to choose one of the predefined strategies:

* `RankingStrategy::COMPETITION`  
   *Standard Competition Ranking   
   Example - 1224*
* `RankingStrategy::MODIFIED_COMPETITION`  
   *Modified Competition Ranking  
   Example - 1334*
* `RankingStrategy::DENSE`  
   *Dense Ranking  
   Example - 1223*
* `RankingStrategy::ORDINAL`  
   *Ordinal Ranking  
   Example - 1234*
   
Then use `$ranker->rank($objectsToRank)` to apply the rankings to an array of objects to rank.

Example:  
```php
    <?php
    $ranker = new Ranker();
    $ranker->setRankingStrategy(RankingStrategy::DENSE);
    $ranker->rank($objectsToRank);  
```
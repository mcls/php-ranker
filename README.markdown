# Ranker
A simple class to assign rankings to objects in an array based on one of their properties.

Use `setRankingStrategy()` to choose one of the predefined strategies:

*  **Standard Competition Ranking** `RankingStrategy::COMPETITION`  
   *Example - 1224*
*  **Modified Competition Ranking** `RankingStrategy::MODIFIED_COMPETITION`  
   *Example - 1334*
*  **Dense Ranking** `RankingStrategy::DENSE`  
   *Example - 1223*
*  **Ordinal Ranking** `RankingStrategy::ORDINAL`  
   *Example - 1234*
   
Then use `$ranker->rank($objectsToRank)` to apply the rankings to an array of objects to rank.

Example:  
```php
    <?php
    $ranker = new Ranker();
    $ranker->setRankingStrategy(RankingStrategy::DENSE);
    $ranker->rank($objectsToRank);  
```
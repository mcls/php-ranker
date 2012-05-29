# Ranker
A simple class to assign rankings to objects in an array based on one of their properties.

Use `setRankingStrategy()` to choose one of the predefined strategies:

```
'competition' : Standard Competition Ranking  ( 1224 )
'modified'    : Modified Competition Ranking  ( 1334 )
'dense'       : Dense Ranking                 ( 1223 )
'ordinal'     : Ordinal Ranking               ( 1234 )
```

   
Then use `$ranker->rank($objectsToRank)` to apply the rankings to an array of objects to rank.

Example:  
```php
    <?php
    $ranker = new Ranker();
    $ranker->setRankingStrategy('dense');
    $ranker
      ->orderBy('points')         // Property to base ranking on, Default is 'score'
      ->storeRankingIn('ranked')  // Default is 'ranking'
      ->rank($objectsToRank);    
```

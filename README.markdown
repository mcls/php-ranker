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

#### Example  
```php
    <?php
    $objectsToRank = array(
      (object) array( 'name' => 'first',  'points' => 100 ),
      (object) array( 'name' => 'second', 'points' => 75 ),
      (object) array( 'name' => 'third',  'points' => 50 ),
    );
    
    $ranker = new Ranker();
    $ranker->setRankingStrategy('dense');
    $ranker
      ->orderBy('points')         // Property to base ranking on, Default is 'score'
      ->storeRankingIn('ranked')  // Default is 'ranking'
      ->rank($objectsToRank);  
      
    print_r($objectsToRank);
```

This will output something like:

```
   Array ( 
      [0] => stdClass Object ( [name] => first     [points] => 100   [ranked] => 1 ) 
      [1] => stdClass Object ( [name] => second    [points] => 75    [ranked] => 2 ) 
      [2] => stdClass Object ( [name] => third     [points] => 50    [ranked] => 3 ) 
   )
```

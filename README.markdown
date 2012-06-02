# Ranker
A simple class to assign rankings to objects in an array based on one of their properties.

#### Usage  
Use `useStrategy()` to choose one of the predefined strategies:

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
   (object) array( 'name' => 'first',      'points' => 100 ),
   (object) array( 'name' => 'second (1)', 'points' => 75 ),
   (object) array( 'name' => 'second (2)', 'points' => 75 ),
   (object) array( 'name' => 'third',      'points' => 50 ),
);
    
$ranker = new Ranker();
$ranker
   ->useStrategy('dense')      // Use the dense ranking strategy
   ->orderBy('points')         // Property to base ranking on, Default is 'score'
   ->storeRankingIn('ranked')  // Default is 'ranking'
   ->rank($objectsToRank);  
   
print_r($objectsToRank);
```

This will output something like:

```
Array ( 
   [0] => stdClass Object ( [name] => first      [points] => 100   [ranked] => 1 ) 
   [1] => stdClass Object ( [name] => second (1) [points] => 75    [ranked] => 2 ) 
   [2] => stdClass Object ( [name] => second (2) [points] => 75    [ranked] => 2 ) 
   [3] => stdClass Object ( [name] => third      [points] => 50    [ranked] => 3 ) 
)
```

#### Ranking already sorted items

If you've fetched some objects from the database which have already been sorted then you can 
set the second parameter of `rank()` to `FALSE`. This will improve your performance when ranking large arrays.

```php
<?php
$ranker->rank($alreadySortedObjects, FALSE);
```

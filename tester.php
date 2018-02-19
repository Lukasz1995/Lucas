<?php 

    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $query = new MongoDB\Driver\Query([]);
   
    $rows = $manager->executeQuery('Samochody.samochody', $query);
   
   
    print_r($rows->toArray());

?>
<?php

require_once('../sources/tableDB.php');

class testTableDB extends tableDB {
// methods and attributes overload
}

$testTableDB = new testTableDB();
$testTableDB->setName('Doud');
$testTableDB->setCity('Paris');
$testTableDB->setPostalCode(75000);
$last_insert_id = $testTableDB->create();

$testTableDB2 = new testTableDB();
$testTableDB2->setID($last_insert_id);
$testTableDB2->read();

echo 'Name : '.$testTableDB->getName();
echo 'City : '.$testTableDB->getCity();
echo 'CP : '.$testTableDB->getPostalCode();

$testTableDB3 = new testTableDB();
if ($testTableDB3->find(array('name' => 'Doud', 'city' => 'Paris'))) {
  echo 'Name '.$testTableDB3->getName().' has been found on ID : '.$testTableDB3->getID();	  
} else {
  echo 'Name '.$testTableDB3->getName().' has not been found';
}

$testTableDB->delete();
?>
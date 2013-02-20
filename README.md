tableDB
=======


Create a inheritable a PHP object who are generate getters, setters, create, read, update, delete and find method automaticaly.

Description du projet
---------------------

Créer une classe qui hérite de tableDB.
En la construisant, la classe va récupperer automatiquement la description de la table.
A partir des informations, l'objet crée tous les setter et getter associer au champ de la table.

L'objet à des méthodes génériques qui permettent d'intéragir avec la base de donnée.
INSERT = Créer une ligne dans la base de donnée. Si la ligne existe déja, l'objet lira les données et mettre à jour l'objet. Retourne le dernier id inseré de l'index primaire.
READ = Lit les données pour une ligne associer à partir de l'ID de l'index primaire. Replit l'objet avec toutes les données de la base de donnée.
UPDATE = Met à jour les données entre un objet et la base de donnée. Retourne true si le changement à effectivement eu lieu.
DELETE = Supprime en base de donnée la ligne associé à l'objet.
FIND = Permet de retrouver une donnée suivant un ou plusieurs champs. Vérifira si l'index existe.

How to use it ?
---------------

Nous avons une table comme-ci dessous.


Nous pouvons donc créer un objet à partir de tableDB. Le nom avant 'TableDB' est le nom de la table.
<?php

class testTableDB extends tableDB {
// methods and attributes overload
}

$testTableDB = new testTableDB();
$testTableDB->setName('Doud');
$testTableDB->setCity('Paris');
$testTableDB->setPostalCode(75000);
$last_insert_id = $testTableDB->insert();

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
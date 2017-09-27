<?php

function getDataToTable():array{

$strSQL = 'SELECT * from user u';

$dbuser = 'root';
$pass = '';
$arrResults = [];

try{

    $dbh = new PDO('mysql:host=localhost;dbname=todo', $dbuser, $pass);

    //$sth = $dbh->prepare('SELECT * from user u where u.name = :user');
    $sth = $dbh->query($strSQL);
    
    //$stmt->bindParam(1, $user);
    //$sth->bindParam(':user', $user, PDO::PARAM_STR);
    If($sth->execute()){


        /*das brauch ich eigentlich nicht, wenn ich ASSOC fetche
        for ($i = 0; $i < $sth->columnCount(); $i++) {
            $col = $rs->getColumnMeta($i);
            echo "<p>", $col['name'], "</p>";
            //$columns[] = $col['name'];
        }
        */

        //$row = $sth->fetch();
        while ($row = $sth->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
            //$data = $row[0] . ";" . $row[1] . ";" . $row[2];
            //echo "<p>$data</p><br>";
            $arrResults[] = $row;

        }
        
    }
    else{
        echo "<p>Es wurden keine Zeilen selektiert!</p>"; 

    }
        
    $dbh = null;

    return $arrResults;


  } 
     catch (PDOException $e) {
     print "Error!: " . $e->getMessage() . "<br/>";
     //die() - Bricht das Script ab und gibt davor noch die angegebene Meldung aus.
     die();
  }
}

?>
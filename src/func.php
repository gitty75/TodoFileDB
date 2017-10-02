<?php

function prettyPrint($data, $dump=true) {
    echo '<pre>';
    if ($dump) {
       var_dump($data); 
    }
    else {
        print_r($data);
    }
    echo '</pre>';
}


function renderTable($arrRows, bool $checkable=true){

echo '<table class="pure-table pure-table-bordered">';

/* automatische Auslesen der Tabellenköpfe 
(brauche ich nicht, da mir mit FETCH_ASSOC die Spaltennamen als Keys des zurückgegebenen Arrays zu Verfügung stehen)
        for($i = 0; $i < mysqli_num_fields($res); $i++) {
            $field_info = mysqli_fetch_field($res);
            echo "<th>{$field_info->name}</th>";
*/

//Tabellenüberschriften
echo '<thead>';
foreach($arrRows As $line){
    echo '<tr>';
    //wenn checkable is true, dann, soll jeder Zeile eine Checkbox vorangestellt werden...
    if($checkable===true){
        echo "<th>check</th>";
    }
    //jetzt wird aus den keys des übergebenen arrays (hier $fieldname) die Tabellenüberschrift erstellt...
        foreach($line As $fieldname => $column){
            echo '<th>', $fieldname, '</th>';
        }
    //Edit-spalte   
    echo '<th>edit</th>';
    echo '</tr>';
    break;
}
echo '</thead>';
         

//Tabellenzeilen
echo '<tbody>';
$j = 1;
foreach($arrRows As $line){
    echo '<tr>';
    //wenn checkable is true, dann, soll jeder Zeile eine Checkbox vorangestellt werden...
    if($checkable===true){
        
        if(array_key_exists('todo_id', $line)){
            //dann nimm eben diese 'todo_id'...
            echo "<td><input type=\"checkbox\" name=\"doneYesNo[]\" value=\"done", $line['todo_id'],"\" /></td>";
        }else{
            //die id ergibt sich für die Textdatei aus der Zeilennummer...
            echo "<td><input type=\"checkbox\" name=\"doneYesNo[]\" value=\"done$j\" /></td>";
        }

    
    
    }
    foreach($line As $column){
        echo '<td>', $column, '</td>';
    }
    //Edit-Spalte
    if(array_key_exists('todo_id', $line) === false ){
        $link = 'todo-update.php?tid=' . $j;
    }
    else{
        $link = 'todo-update.php?tid=' . $line['todo_id'];
    }
    echo "<td><a href=\"$link\">edit</a></td>";
    echo '</tr>';
$j++;
}

echo '</tbody>';
echo '</table>';

}

/**
 * Undocumented function
 *
 * @param array $arrToBeDeleted
 * @return bool
 */
function removefromCSV(array $arrToBeDeleted):bool{
   
       $ToDos = fopen('todoliste.csv', 'r'); //Lesen-Modus
       $arrPreliminary; //vorläufiges Array mit den zu erhaltenden Einträgen
   
       // Bei Dateien immer erst prüfen, ob eine gültige Ressource vorhanden ist.
       if ($ToDos !== false) {
               $j = 1;
               while (feof($ToDos) !== true) {
                   // die aktuelle Zeile auslesen, den Zeiger auf die nächste Zeile setzen
                   $line = fgets($ToDos);
                   if(in_array("done$j", $arrToBeDeleted)){

                   }
                   else
                   {
                       $arrPreliminary[] = $line;
                   }
                   $j++;

               }
       }
       else
       {
          return false; 
       }
       fclose($ToDos);

       $ToDos = fopen('todoliste.csv', 'w'); //neu schreiben, wenn nötig nicht existierende Datei für diesen Zweck erzeugen
       if ($ToDos != false){
           foreach($arrPreliminary As $value){
               fwrite($ToDos, $value);  //. "\r\n"    
           }
            
       }
 
       fclose($ToDos);  
   return true;    
}


function insertintoCSV(string $newtodo):bool{

$success = false;

    if(strlen($newtodo)>0){
        if (count(getTodos('todoliste.csv'))===0){
            $lastId = -1;
        }
        else
        {
            $lastId = getTodos('todoliste.csv')[count(getTodos('todoliste.csv'))-1][0];
        }

                // neuen Eintrag in die Datei 'todoliste.csv' schreiben.
                $file = fopen('todoliste.csv', 'a');
                if ($file !== false){
                    $newId = $lastId + 1;
                    fwrite($file,  $newId . ";" . date('d.m.Y H:i:s') . ';' . trim($newtodo) . "\r\n");
                    fclose($file);
                }
        
   }

$success = true;   
return $success;

}


?>
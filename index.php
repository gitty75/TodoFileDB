
<?php

session_start();
//var_dump($_COOKIE);
//var_dump($_SESSION);

$_SESSION['somedata'] = 'merkdirdas';


?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Die Tabelle, die aus der Datenbank kam</title>
    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheet.css?v=<?=time();?>">  
</head>
<body>

<div  id="left" class="pure-u-1 pure-u-md-1-3" >
<h1>Todo-Liste</h1>
</div>
<div id="mid" class="pure-u-1 pure-u-md-1-3" >
     
    <form class="pure-form pure-form-aligned"  action="" method="post">   
            <select name="datasource" class="input">
            <!-- Alt+Shift+A ist der HTML-Kommentar... disabled option outside of Option group can be uses to display standard-text-->
                <option disabled="disabled" selected="selected">Datenquelle wählen</option>
                <optgroup label="Datasource">
                <option value="sourcedb">Datenbank</option>
                <option value="sourcetx">Textdatei</option>
                </optgroup>
            </select>
            <br><br>
            <input class="inbox" type="text" name="newtodo" id="a"> 
            <br><br> 
            <input type="submit" value="aktualisieren"/>
            <br><br>
            

            <?php 
                //include ("./table.php"); 
                //renderTable($filePath, ";", true, $headers=['del', 'id', 'Datum/Uhrzeit', 'Aufgabe'], $widths=['8%', '8%', '28%', '56%']);
                require_once("src/func.php");
                require_once("src/data.php");
                require_once("../GUMP-master/gump.class.php");
                
                //G U M P - Validation, nur nachdem gepostet wurde!
                /*
                if(count($_POST)>0){

                $gump = new Gump('de');
                
                $gump->validation_rules(array(
                    'newtodo' => 'required|alpha_numeric|max_len,100|min_len,6'
                ));
                    
                    $validated_data = $gump->run($_POST);
                    
                                    if($validated_data === false) {
                                        echo $gump->get_readable_errors(true);
                                    } else {
                                        print_r($validated_data); // validation successful
                                    }
                    
                }
                */
            
                //Prüfen ob einzelne Zeilen gecheckt sind, d.h. erledigt sind, d.h. zu löschen sind
                if(count($_POST)>0){
                    
                    if(array_key_exists('doneYesNo', $_POST)){   
                            $dyn = $_POST['doneYesNo'];  

                            if(array_key_exists('datasource', $_POST)){
                                if($_POST['datasource'] === 'sourcetx'){
                                    removefromCSV($dyn);  
                                }
                                elseif($_POST['datasource'] === 'sourcedb'){
                                    removefromDB($dyn);
                                }   
                            }     
                    }

                    if(array_key_exists('newtodo', $_POST)){

                        if(array_key_exists('datasource', $_POST)){
                            if($_POST['datasource'] === 'sourcetx'){
                                insertintoCSV($_POST['newtodo']);  
                            }
                            elseif($_POST['datasource'] === 'sourcedb'){
                                //ist noch nicht implementiert?
                                //insertintoTable($_POST['newtodo']);
                            }   
                        }

                    }
 
                }    

                //Hier wird entschieden, ob eine Textdatei oder die Datenbank als Datenquelle verwendet werden soll...
                
                if(isset($_POST['datasource'])){
                    if($_POST['datasource']==='sourcedb'){
                        $rows = getData();
                        //$_SESSION['datasource'] = 'sourcedb';
                        renderTable($rows);                       
                    }
                    elseif($_POST['datasource']==='sourcetx'){
                         $rows = getTodos('todoliste.csv');
                        //$_SESSION['datasource'] = 'sourcetx';
                        renderTable($rows);                        
                    }
                }                                     
            ?>
            
    </form>     
     
</div>
<div  id="right" class="pure-u-1 pure-u-md-1-3" >
</div>
</body>
</html>
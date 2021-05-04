<?php

/* backup the db OR just a table */
//En la variable $talbes puedes agregar las tablas especificas separadas por comas:
//profesor,estudiante,clase
//O déjalo con el asterisco '*' para que se respalde toda la base de datos

/*function backup_tables($host,$user,$pass,$name,$tables = '*')
{
   $return='';
   $link = new mysqli($host,$user,$pass,$name);
  // mysql_select_db($name,$link);
   
   //get all of the tables
   if($tables == '*')
   {
      $tables = array();
      $result = $link->query('SHOW TABLES');
      while($row = mysqli_fetch_row($result))
      {
         $tables[] = $row[0];
      }
   }
   else
   {
      $tables = is_array($tables) ? $tables : explode(',',$tables);
   }
   
   //cycle through
   foreach($tables as $table)
   {
      $result = $link->query('SELECT * FROM '.$table);
      $num_fields = mysqli_num_fields($result);

      
      //$return.= 'DROP TABLE '.$table.';';
      $row2 = mysqli_fetch_row($link->query('SHOW CREATE TABLE '.$table));
      $return.= "\n\n".$row2[1].";\n\n";
      
    for ($i = 0; $i < $num_fields; $i++)
      {
         while($row = mysqli_fetch_row($result))
         {
            $return.= 'INSERT INTO '.$table.' VALUES(';
            for($j=0; $j<$num_fields; $j++) 
            {
               $row[$j] = addslashes($row[$j]);
               $row[$j] = preg_replace("/\n/","\\n",$row[$j]);
               if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
               if ($j<($num_fields-1)) { $return.= ','; }
            }
            $return.= ");\n";
         }
      }
      $return.="\n\n\n";
   }
   $fecha=date("Y-m-d");
   //save file
   $handle = fopen('backups/db-backup-'.$fecha.'.sql','w+');
    fwrite($handle,$return);
    fclose($handle);
}*/

$db_host='localhost';
$db_name='dbtecniworld';
$db_user='root';
$db_pass='';

$fecha= date("Y-m-d");


// Construimos el nombre de archivo SQL Ejemplo: mibase_20170101-081120.sql
   $salida_sql = $db_name.'_'.$fecha.'.sql'; 
   
   //Comando para genera respaldo de MySQL, enviamos las variales de conexion y el destino
   $dump = "mysqldump -h $db_host -u $db_user -p $db_pass --opt --events --routines --triggers $db_name > $salida_sql";
   system($dump, $output); //Ejecutamos el comando para respaldo
   
   $zip = new ZipArchive(); //Objeto de Libreria ZipArchive
   
   //Construimos el nombre del archivo ZIP Ejemplo: mibase_20160101-081120.zip
   $salida_zip = $db_name.'_'.$fecha.'.zip';
   
   if($zip->open($salida_zip,ZIPARCHIVE::CREATE)===true) { //Creamos y abrimos el archivo ZIP
      $zip->addFile($salida_sql); //Agregamos el archivo SQL a ZIP
      $zip->close(); //Cerramos el ZIP
      unlink($salida_sql); //Eliminamos el archivo temporal SQL
      header ("Location: $salida_zip"); // Redireccionamos para descargar el Arcivo ZIP
      } else {
      echo 'Error'; //Enviamos el mensaje de error
   }
?>
?>


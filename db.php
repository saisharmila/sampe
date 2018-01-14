<?php
   include_once 'connect.php';
   
   $sql = 'CREATE Database IF NOT EXISTS sample';
   $retval = mysql_query( $sql, $conn );
   
   if(! $retval ) {
      die('Could not create database: ' . mysql_error());
   }
   
   echo "Database sample created successfully\n";
   $sql = 'CREATE TABLE  IF NOT EXISTS users( '.
      'id INT NOT NULL AUTO_INCREMENT, '.
      'username VARCHAR(20) NOT NULL, '.
      'email  VARCHAR(20) NOT NULL, '.
      'password  VARCHAR(120) NOT NULL, '.
      
      'primary key ( id ))';
   mysql_select_db('sample');
   $retval = mysql_query( $sql, $conn );
   
   if(! $retval ) {
      die('Could not create table: ' . mysql_error());
   }
   
   echo "Table users created successfully\n";
   
   mysql_close($conn);
?>
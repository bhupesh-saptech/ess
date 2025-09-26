<?php
   spl_autoload_register(function(string $class) {
      if (class_exists($class, false)) {
         return; // Class already exists, no need to load it
     }
     if (strpos($class, '\\') === false) {
         return;  // Skip global PHP classes like PDO, Exception, etc.
      }
      $file = '../src/' . str_replace('\\','/',$class) . '.php';
      if (file_exists($file)) {
         require $file;
      }
   });



   
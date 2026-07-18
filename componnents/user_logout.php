<?php

   include 'connect.php';

   setcookie('user_id', '', time() - 1, '/');

   header('location:../platforme/home.php');

?>
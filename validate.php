<?php
    session_start();
    if(!isset($_SESSION['user']) || !isset($_SESSION['token'])) header("location:login.php");
<?php 
    function verify_query($check_set){

        global $connection;

        if(!$check_set){
            die("Databse query failed:" . mysqli_error($connection));
        }
    }
?>
<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>
<?php 

    //user submit btton eka press karlalalda?
    if(isset($_POST['submit'])) {

        $errors = array();

        //user name pw enter karlalalda ?
        if(!isset($_POST['email']) || strlen(trim($_POST['email']) < 1)) {
            $errors[] = 'Username is Missing or Invalid';
        }

        if(!isset($_POST['password']) || strlen(trim($_POST['password']) < 1)) {
            $errors[] = 'password is Missing or Invalid';
        }

        //check if there are errors
        if(empty($errors)) {

            //save username n pw to variables 
            $email = mysqli_real_escape_string($connection, $_POST['email']);
            $password = mysqli_real_escape_string($connection, $_POST['password']);
            $hashed_password = $password;
            
            //prepare database query
            $query = "SELECT * FROM user
                    WHERE email = '{$email}'
                    AND password = '{$hashed_password}'
                    LIMIT 1";

            $result_set = mysqli_query($connection, $query);

            if($result_set){
                if(mysqli_num_rows($result_set) == 1){
                    //valid user found
                    $user = mysqli_fetch_assoc($result_set);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['first_name'] = $user['first_name'];
                    
                    //updating the last login
                    $query = "UPDATE user SET last_login = NOW()";
                    $query .= "WHERE id = {$_SESSION['user_id']} LIMIT 1";

                    $result_set = mysqli_query($connection, $query);

                    if(!$result_set)
                    {
                        echo "Database query failed";
                    }


                    
                    
                    //if so send tp users php
                    header('Location: users.php');
                    //echo '<p>Indddddddddddvalid Username / Password</p>';  
                } 
                else 
                {
                    //username and pw is invalid
                    $errors[] = 'Invalid user';
                }
            }
            else 
            {
                $errors[] = 'Database quary failed';
            }
          

           

            //else ,display errors
        }

    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User Manangement System</title>
</head>
<body>
    
    <div>
        <form action="index.php" method ="post">
            <feildset>
                <h1>LOG IN</h1>
                <!--<p>Invalid Username / Password</p> -->
                <?php 
                 if(isset($errors) && !empty($errors)){
                   echo '<p>Invalid Username / Password</p>';  
                 }
                ?>

                <?php 
                    if(isset($_GET['logout']))
                    {
                        echo '<p>Successfully Logged out</p>'; 
                    }
                ?>


                <p>
                    <label for="">User Name:</label>
                    <input type="text" name="email" placeholder = "email address">
                
                </p>

                <p>
                    <label for="">Password:</label>
                    <input type="password" name="password" placeholder = "password">
                </p>

                <p>
                    <input type="submit" name="submit">
                </p>
            </feildset>

        </form>
    </div>

</body>
</html>
<?php mysqli_close($connection); ?>
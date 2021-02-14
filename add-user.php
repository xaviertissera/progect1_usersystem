<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>
<?php 
    $errors = array();
    $first_name ='';
    $last_name ='';
    $email ='';
    $password ='';

    if(isset($_POST['submit']))
    {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        //checking the feilds
        /*
        if(empty(trim($_POST['first_name']))){
            $errors[] = 'Frist Name is empty';
        }
        if(empty(trim($_POST['last_name']))){
            $errors[] = 'Last Name is empty';
        }
        if(empty(trim($_POST['email']))){
            $errors[] = 'email is empty';
        }
        if(empty(trim($_POST['password']))){
            $errors[] = 'Password is empty';
        }
        */
        //alternative methord to check errs

        $req_feilds = array('first_name', 'last_name', 'email', 'password');

        foreach ($req_feilds as $feild){
            if(empty(trim($_POST[$feild]))){
                $errors[] = $feild . ' is required to add';
            }
        }

        //-------------------------------------------------------/
        //validation - max length

        $max_length = array('first_name' =>50, 'last_name' => 100, 'email' =>100, 'password' => 40);

        foreach ($max_length as $feild => $max_len){
            if(strlen(trim($_POST[$feild])) > $max_len){
                $errors[] = $feild . ' must be less than  ' .$max_len . ' characters';
            }
        }

        //email validation
        if (!filter_var(($_POST['email']), FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        //check the database for existing email 
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $query = "SELECT * FROM user WHERE email = '{$email}' LIMIT 1";

        $result = mysqli_query($connection, $query);

        verify_query($result);
        if(mysqli_num_rows($result) == 1){
            $errors[] = 'Email Address exist';
        }

        //updating the database
        if(empty($errors)){
            //sanitizing the feilds b4 sending to the database
            $first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
            $last_name= mysqli_real_escape_string($connection, $_POST['last_name']);
            $password= mysqli_real_escape_string($connection, $_POST['password']);

            $query = "INSERT INTO user ( ";
            $query .= "first_name, last_name, email, password, is_deleted";
            $query .= ") VALUES (";
            $query .= "'{$first_name}','$last_name','$email','$password', 0";
            $query .= ")";

            $resultt = mysqli_query($connection, $query);
            if($resultt){
                //query successfull
                header('Location: users.php?user_added=true');

            }else
            {
                $errors[] = 'Failed adding new record';
            }
        
        }

        
    }



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add-NewUser</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <header>
    <div class="appname">User managment system</div>
    <div class="loggedin">Welcome <?php echo $_SESSION['first_name']; ?>! <a href="logout.php">Log Out</a></div>
    </header>

    <main>
        <h1>Add New User<span><a href="users.php"> << Back to List</a></span></h1>
        <?php 
            if(!empty($errors)){
                echo '<div>';
                echo '<b>Errors in your form</b> <br>';
                foreach($errors as $error){
                    echo $error .'<br>';
                }
                echo '</div>';
            }
        ?>
        <form action="add-user.php" class="newUser" method="POST">
            <p>
                <label for="">First Name</label>
                <input type="text" name="first_name" <?php echo 'value="' .$first_name . '"' ?> >
            </p>
            <p>
                <label for="">Last Name</label>
                <input type="text" name="last_name" <?php echo 'value="' .$last_name . '"' ?> >
            </p>
            <p>
                <label for="">Email Address</label>
                <input type="text" name="email" >
            </p>
            <p>
                <label for="">New Password</label>
                <input type="password" name="password" >
            </p>
            <p>
                <label for="">&nbsp;</label>
                <button type="submit" name="submit">Save</button>
            </p>
        </form>
    </main>

    
</body>
</html>
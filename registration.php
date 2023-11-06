<?php  include "includes/db.php"; ?>
 <?php  include "includes/header.php"; ?>


    <!-- Navigation -->
    
    <?php  include "includes/navigation.php"; ?>

<?php 
    if(isset($_POST['submit'])){

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        $username = mysqli_real_escape_string($connection, $username);
        $email    = mysqli_real_escape_string($connection, $email);
        $password = mysqli_real_escape_string($connection, $password);


        // // Generate a unique salt and hash the password
        // $options = [
        //     'cost' => 12, // You can adjust the cost factor as needed
        // ];
        // $password = password_hash($password, PASSWORD_BCRYPT, $options);

            //Selecting randSalt for encrypting 
            $query = "SELECT randSalt FROM users";
            $select_randsalt_query = mysqli_query($connection, $query);
            if(!$select_randsalt_query){
                die("QUERY FAILED".mysqli_errno($connection));
            }

            //Fetching result from randSalt query
            $row = mysqli_fetch_assoc($select_randsalt_query);
            $salt = $row['randSalt'];

            //Crypt Function
            $hashed = crypt($password, '$2y$10$iusesomecrazystrings22');

            // //Inserting user into the database query
            $query = "INSERT INTO users (username, user_email, user_password, user_role) ";
            $query .= "VALUES ('{$username}','{$email}', '{$hashed}','subscriber')";
            $insert_user_query = mysqli_query($connection, $query);
            if(!$insert_user_query){
                die("QUERY FAILED".mysqli_errno($connection));
            }

            $message = "Your registration has been submitted!";

        }else{
            $message = "Fields cannot be empty!";
        }
        

    }else{
        $message = "";
    }
?>

 
    <!-- Page Content -->
    <div class="container">
    
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3">
                <div class="form-wrap">
                <h1>Register</h1>
                    <form role="form" action="registration.php" method="post" id="login-form" autocomplete="off">
                        
                        <h5 class="text-center"><?php echo $message; ?></h5>
                        <div class="form-group">
                            <label for="username" class="sr-only">username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Enter Desired Username">
                        </div>
                         <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="somebody@example.com">
                        </div>
                         <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="key" class="form-control" placeholder="Password">
                        </div>
                
                        <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Register">
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>


        <hr>



<?php include "includes/footer.php";?>

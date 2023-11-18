<?php

function redirect($location){
    header("Location:".$location);
    exit;
}

//Counting Online Users
function users_online(){
    if(isset($_GET['onlineusers'])){
        global $connection;
        if(!$connection){
            include("../includes/db.php");
            $session = session_id();
            $time = time();
            $time_out_in_seconds = 30;
            $time_out = $time - $time_out_in_seconds;
            
            $query = "SELECT * FROM users_online WHERE session = '$session' ";
            $send_query = mysqli_query($connection, $query);
            $count = mysqli_num_rows($send_query);
            
            if($count == NULL){
                mysqli_query($connection, "INSERT INTO users_online (session, time) 
            VALUES ('$session','$time')");
        }else{
            mysqli_query($connection, "UPDATE users_online SET time = '$time' 
            WHERE session = '$session' ");
        }
        $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > '$time_out' ");
        echo $count_user = mysqli_num_rows($users_online_query);
        
        
    }
}
}
//Calling the function
users_online();




//Checking is there any POST or GET method
function ifItIsMethod($method=null){
    if($_SERVER['REQUEST_METHOD'] == strtoupper($method)){
        return true;
    }
    return false;
}

function isLoggedIn(){
    if(isset($_SESSION['user_role'])){
        return true;

    }
        return false;
    
    
}

function checkIfUserLoggedInAndRedirect($redirectLocation=null){
    if(isLoggedIn()){
        redirect($redirectLocation);
    }
}

function login_user($username, $password){
    global $connection;
    
    $username = mysqli_real_escape_string($connection, $username);
    $password = mysqli_real_escape_string($connection, $password);

    $query = "SELECT * FROM users WHERE username = '{$username}' ";
        $select_user_query = mysqli_query($connection, $query);
        confirmQuery($select_user_query);

        while($row = mysqli_fetch_assoc($select_user_query)){
                $db_user_id = $row['user_id'];
                $db_username = $row['username'];
                $db_password = $row['user_password'];
                $db_user_role = $row['user_role'];

                if(password_verify($password, $db_password)){
                    $_SESSION['username'] = $db_username;
                    $_SESSION['user_role'] = $db_user_role;
        
                    redirect("/cms/admin");
        
        
                }else{
                    return false;
                }
                
        }
        return false;

        // Encrypting Password
        // $password = crypt($password, $db_password);        
}

function register_user($username, $email, $password){
    
    global $connection;
        $username = mysqli_real_escape_string($connection, $username);
        $email    = mysqli_real_escape_string($connection, $email);
        $password = mysqli_real_escape_string($connection, $password);

        //hashing the password using algo.s
        $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 10));

        // //Inserting user into the database query
        $query = "INSERT INTO users (username, user_email, user_password, user_role) ";
        $query .= "VALUES ('{$username}','{$email}', '{$password}','subscriber')";
        $insert_user_query = mysqli_query($connection, $query);
        
        confirmQuery($insert_user_query);
    

}

//Checking duplicate email in database
function email_exists($email){
    global $connection;

    $query = "SELECT user_email FROM users WHERE user_email = '$email' ";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);
    if(mysqli_num_rows($result) > 0 ){
        return true;
    }else{
        return false;
    }
}

//Checking duplicate username in the database
function username_exists($username){
    global $connection;

    $query = "SELECT username FROM users WHERE username = '$username' ";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);
    if(mysqli_num_rows($result) > 0 ){
        return true;
    }else{
        return false;
    }
}


//Checking if user is admin
function is_admin($username = ''){
    global $connection;
    $query = "SELECT user_role FROM users WHERE username = '$username' ";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);
    $row = mysqli_fetch_array($result);
    if($row['user_role'] == 'admin'){
        return true;
    }else{
        return false;
    }

}

function checkStatus($table, $column, $status){
    global $connection;
    $query = "SELECT * FROM $table WHERE $column = '$status' ";
    $select_all_publish = mysqli_query($connection, $query);

    return mysqli_num_rows($select_all_publish);

}


//Returning the count of the records for respective table.

function recordCount($table){
    global $connection;
    $query = "SELECT * FROM ".$table;
    $select_all_post = mysqli_query($connection, $query);

    return mysqli_num_rows($select_all_post);
}



function escape($string){
    global $connection;
    mysqli_real_escape_string($connection, trim($string));
}





    



//Confirm Mysql query
function confirmQuery($result){
    global $connection;
    if(!$result){
        die("QUERY FAILED".mysqli_error($connection));
    }
}

//FUNCTION TO INSERT CATEGORY
function insert_categories(){
    global $connection;
    if (isset($_POST['submit'])) {
        $cat_title = $_POST['cat_title'];
        if ($cat_title == "" || empty($cat_title)) {
            echo "This field should not be empty";
        } else {
            $query = "INSERT INTO categories(cat_title) ";
            $query .= "VALUE('{$cat_title}')";
    
            $creat_category_query = mysqli_query($connection, $query);
            if (!$creat_category_query) {
                die("QUERY FAILED" . mysqli_error($connection));
            }
        }
    }
}


//FUNCTION TO FIND ALL CATEGORIES
function findAllCategories()
{
    global $connection;

    $query = "SELECT * FROM categories";
    $select_categories = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($select_categories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        echo "<tr>";
        echo "<td>{$cat_id}</td>";
        echo "<td>{$cat_title}</td>";
        echo "<td><a href='categories.php?delete={$cat_id}'>Delete</a></td>";
        echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
        echo "</tr>";
    }
}

//Deleting categories from categories table
function deleteCategories(){
    global $connection;
    if(isset($_GET['delete'])){
        $the_cat_id = $_GET['delete'];
        $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id}";
        $delete_query = mysqli_query($connection, $query);
        header("Location: categories.php");
    }
}



?>
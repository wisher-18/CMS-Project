<?php
if (isset($_POST['create_user'])) {
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    // $user_role = $_POST['user_role'];
    $username = $_POST['username'];

    // $post_image = $_FILES['post_image']['name'];
    // $post_image_temp = $_FILES['post_image']['tmp_name'];

    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

    //Hashing the password
    $user_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost'=> 10));
    // $post_date = date('d-m-y');

    // move_uploaded_file($post_image_temp, "../images/$post_image");

    $query = "INSERT INTO users(user_firstname, user_lastname, username, user_email, user_password) ";

    $query .= "VALUES ('$user_firstname', '$user_lastname',
    '$username', '$user_email', '$user_password')";

    $create_user_query = mysqli_query($connection, $query);

    confirmQuery($create_user_query);
    echo "User Created Successfully "." "."<a href='users.php'>View Users</a>";
}
?>
<form action="" method="post" enctype="multipart/form-data">

    

    <div class="form-group">
        <label for="user_firstname">Firstname</label>
        <input type="text" class="form-control" name="user_firstname">
    </div>

    <div class="form-group">
        <label for="user_lastname">Lastname</label>
        <input type="text" class="form-control" name="user_lastname">
    </div>
<!-- 
    <div class="form-group">
        <select name="user_role" id="">
            <option value="subsciber">Select Option</option>
            <option value="admin">Admin</option>
            <option value="subscriber">Subscriber</option>
        </select>
    </div> -->

    


    <!-- <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file" name="post_image">
    </div> -->

    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" name="username">
    </div>

    <div class="form-group">
        <label for="user_email">Email</label>
        <input type="email" class="form-control" name="user_email">
    </div>

    <div class="form-group">
        <label for="user_password">Password</label>
        <input type="password" autocomplete="off" class="form-control" name="user_password">
    </div>

    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="create_user" value="Submit">

    </div>





</form>
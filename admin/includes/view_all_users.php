<?php
    if(isset($_POST['checkBoxArrayUsers'])){

        foreach($_POST['checkBoxArrayUsers'] as $userIdValue){
            $bulk_options_users = $_POST['bulk_options_users'];

            switch($bulk_options_users){
                case 'delete':
                    $query = "DELETE FROM users WHERE user_id = {$userIdValue}";
                    $bulk_delete_query = mysqli_query($connection, $query);
                    if(!$bulk_delete_query){
                        die("QUERY FAILED".mysqli_error($connection));
                    }
                    break;

                default:
            }
        }
    }
?>




<form action="" method="post">

    <table class="table table-bordered table-hover">
        <!-- Bulk Options For Edit -->
        <div id="bulkOptionsContainerUsers" class="col-xs-4">
            <select name="bulk_options_users" class="form-control">
                <option value="">Select Options</option>
                <option value="delete">Delete</option>
            </select>
        </div>

        <div class="col-xs-4">
            <input type="submit" name="submit" value="Apply" class="btn btn-success">
            <a href="users.php?source=add_user" class="btn btn-primary">Add User</a>
        </div>

        <thead>
        <tr>
            <th><input type="checkbox" id="selectAllBoxesUsers"></th>
            <th>User_Id</th>
            <th>Username</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Admin</th>
            <th>Subs</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM users";
        $select_users = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_assoc($select_users)) {
            $user_id = $row['user_id'];
            $username = $row['username'];
            $user_firstname = $row['user_firstname'];
            $user_lastname = $row['user_lastname'];
            $user_email = $row['user_email'];
            $user_role = $row['user_role'];
            $user_image = $row['user_image'];
            $user_password = $row['user_password'];
            
            echo "<tr>";
            ?>
            <td><input type="checkbox" name="checkBoxArrayUsers[]" 
            value="<?php echo $user_id; ?>" 
            class="checkBoxes"></td>

            <?php

            echo "<td>{$user_id}</td>";
            echo "<td>{$username}</td>";
            echo "<td>{$user_firstname}</td>";
            echo "<td>{$user_lastname}</td>";
            echo "<td>{$user_email}</td>";
            // $query = "SELECT * FROM posts WHERE post_id = $comment_post_id";
            // $select_post_id_query = mysqli_query($connection, $query);
            // while($row = mysqli_fetch_assoc($select_post_id_query)){
                //     $post_id = $row['post_id'];
                //     $post_title = $row['post_title'];
                
                //     echo "<td><a href='../post.php?p_id=$post_id'>$post_title</a></td>";
                
                // }
                echo "<td>{$user_role}</td>";
                echo "<td><a href='users.php?change_to_admin={$user_id}'>Admin</a></td>";
                echo "<td><a href='users.php?change_to_sub={$user_id}'>Subs</a></td>";
            echo "<td><a href='users.php?source=edit_user&edit_user={$user_id}'>Edit</a></td>";
            echo "<td><a onClick=\"javascript: return confirm('Are you sure you want to delete this user?');\" href='users.php?delete={$user_id}'>Delete</a></td>";
            echo "</tr>";
        }


        ?>

</tbody>
</table>
</form>

<?php
//APPROVING COMMENTS QUERY
if(isset($_GET['change_to_admin'])){
    $user_id_ad = escape($_GET['change_to_admin']);
    $query = "UPDATE users SET user_role = 'admin' WHERE user_id = {$user_id_ad} ";
    $make_admin_query = mysqli_query($connection, $query);
    confirmQuery($make_admin_query);
    header("Location: users.php");
}


//DISAPPROVING COMMENTS QUERY
if(isset($_GET['change_to_sub'])){
    $user_id_sub = escape($_GET['change_to_sub']);
    $query = "UPDATE users SET user_role = 'subscriber' WHERE user_id = {$user_id_sub} ";
    $make_sub_query = mysqli_query($connection, $query);
    confirmQuery($make_sub_query);
    header("Location: users.php");
}



//DELETING COMMENTS QUERY
if(isset($_GET['delete'])){
    $user_id_del = escape($_GET['delete']);
    $query = "DELETE FROM users WHERE user_id = {$user_id_del} ";
    $delete_user_query = mysqli_query($connection, $query);
    confirmQuery($delete_user_query);
    header("Location: users.php");
}

?>
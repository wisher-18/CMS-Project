<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>User_Id</th>
            <th>Username</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Role</th>
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
            echo "<td><a href='comment.php?approve={}'>Approve</a></td>";
            echo "<td><a href='comment.php?disapprove={}'>Disapprove</a></td>";
            echo "<td><a href='comment.php?delete={}'>Delete</a></td>";
            echo "</tr>";
        }


        ?>

    </tbody>
</table>

<?php
//APPROVING COMMENTS QUERY
if(isset($_GET['approve'])){
    $comment_id_dis = $_GET['approve'];
    $query = "UPDATE comments SET comment_status = 'approved' WHERE comment_id = {$comment_id_dis} ";
    $approve_query = mysqli_query($connection, $query);
    confirmQuery($approve_query);
    header("Location: comment.php");
}


//DISAPPROVING COMMENTS QUERY
if(isset($_GET['disapprove'])){
    $comment_id_dis = $_GET['disapprove'];
    $query = "UPDATE comments SET comment_status = 'disapproved' WHERE comment_id = {$comment_id_dis} ";
    $disapprove_query = mysqli_query($connection, $query);
    confirmQuery($disapprove_query);
    header("Location: comment.php");
}



//DELETING COMMENTS QUERY
if(isset($_GET['delete'])){
    $comment_id_del = $_GET['delete'];
    $query = "DELETE FROM comments WHERE comment_id = {$comment_id_del} ";
    $delete_query = mysqli_query($connection, $query);
    confirmQuery($delete_query);
    header("Location: comment.php");
}

?>
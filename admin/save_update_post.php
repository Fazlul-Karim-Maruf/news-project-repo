<?php
include "config.php";

if (empty($_FILES['new_image']['name'])) {
    $file_name = $_POST['old_image'];
} else {
    $errors = array();

    $file_name = $_FILES['new_image']['name'];
    $file_size = $_FILES['new_image ']['size'];
    $file_tmp = $_FILES['new_image']['tmp_name'];
    $file_type = $_FILES['new_image ']['type'];
    $file_ext = end(explode('.', $file_name));

    $extensions = array("jpeg", "jpg", "png");

    if (in_array($file_ext, $extensions) === false) {
        $errors[] = "This extension file not allowed, Please choose a JPG or PNG file.";
    }

    if ($file_size > 2097152) {
        $errors[] = "File size must be 2mb or lower.";
    }
    $new_name = time() . "-" . basename($file_name);
    $target = "upload/" . $new_name;

    if (empty($errors) == true) {
        move_uploaded_file($file_tmp, "upload/" . $file_name);
    } else {
        print_r($errors);
        die();
    }
}
$sql = "UPDATE post SET title = '{$_POST['post_title']}' , description ='{$_POST['postdesc']}', category = '{$_POST['category']}' , post_img = '{$file_name}' 
WHERE post_id={$_POST['post_id']}; ";
if($_POST['old_category'] != $_POST['cateegory']){
 $sql .= "UPDATE category SET post = post - 1 WHERE category_id ={$_POST['old_category']};"; 
 $sql .= "UPDATE category SET post = post + 1 WHERE category_id ={$_POST['category']};";  
}

$result = mysqli_multi_query($conn,$sql);
if($result){
    header("location: {$hostname}/admin/post.php");
}else{
    echo "query failed";
}
?>

<?php

// Include database connection
include '../componnents/connect.php';

// Check if tutor is logged in, otherwise redirect to login page
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:log.php');
}

// Handle form submission
if(isset($_POST['submit'])){

   // Generate unique ID for the content
   $id = unique_id();

   // Sanitize and store form inputs
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_SPECIAL_CHARS);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
   $playlist_id = $_POST['playlist_id'];
   $playlist_id = filter_var($playlist_id, FILTER_SANITIZE_SPECIAL_CHARS);

   // Process thumbnail upload
   $thumb = $_FILES['thumb']['name'];
   $thumb = filter_var($thumb, FILTER_SANITIZE_SPECIAL_CHARS);
   $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION); // Extract file extension
   $rename_thumb = unique_id().'.'.$thumb_ext; // Rename file to ensure uniqueness
   $thumb_size = $_FILES['thumb']['size']; // Get file size
   $thumb_tmp_name = $_FILES['thumb']['tmp_name']; // Temporary file name
   $thumb_folder = '../uploaded/'.$rename_thumb; // Destination folder

   // Process video upload
   $video = $_FILES['video']['name'];
   $video = filter_var($video, FILTER_SANITIZE_SPECIAL_CHARS);
   $video_ext = pathinfo($video, PATHINFO_EXTENSION); // Extract file extension
   $rename_video = unique_id().'.'.$video_ext; // Rename file to ensure uniqueness
   $video_tmp_name = $_FILES['video']['tmp_name']; // Temporary file name
   $video_folder = '../uploaded/'.$rename_video; // Destination folder

   // Check if content with the same title and description already exists
   $verify_content = $conn->prepare("SELECT * FROM content WHERE tutor_id = ? AND title = ? AND description = ?");
   $verify_content->execute([$tutor_id, $title, $description]);

   if ($verify_content->rowCount() > 0) {
       $message[] = 'Content already created!';
   } else {
       // Insert new content into the database
       $add_content = $conn->prepare("INSERT INTO content (id, tutor_id, playlist_id, title, description, video, thumb, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
       $add_content->execute([$id, $tutor_id, $playlist_id, $title, $description, $rename_video, $rename_thumb, $status]);

       // Move uploaded files to their respective folders
       if (move_uploaded_file($thumb_tmp_name, $thumb_folder) && move_uploaded_file($video_tmp_name, $video_folder)) {
           $message[] = 'New content created!';
       } else {
           $message[] = 'Failed to move uploaded files!';
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
   <title>Dashboard</title>

   <!-- Include font awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Include custom CSS -->
   <link rel="stylesheet" href="../css/style_adm.css">

</head>
<body>

<?php 
// Include the admin header
include '../componnents/admin_header.php'; 
?>
   
<section class="video-form">

   <h1 class="heading">Ajouter un contenu</h1>

   <!-- Form to add video content -->
   <form action="" method="post" enctype="multipart/form-data">
      <p>Statut de la vidéo <span>*</span></p>
      <select name="status" class="box" required>
         <option value="" selected disabled>-- Sélectionner le statut --</option>
         <option value="active">Active</option>
         <option value="deactive">Inactive<</option>
      </select>
      <p>Titre de la vidéo <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="Enter video title" class="box">
      <p>Description de la vidéo  <span>*</span></p>
      <textarea name="description" class="box" required placeholder="Write description" maxlength="1000" cols="30" rows="10"></textarea>
      <p>vidéo playlist <span>*</span></p>
      <select name="playlist_id" class="box" required>
         <option value="" disabled selected>-- Sélectionner la  playlist --</option>
         <?php
         // Fetch playlists for the logged-in tutor
         $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
         $select_playlists->execute([$tutor_id]);
         if($select_playlists->rowCount() > 0){
            while($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)){
         ?>
         <option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?></option>
         <?php
            }
         ?>
         <?php
         }else{
            echo '<option value="" disabled>No playlist created yet!</option>';
         }
         ?>
      </select>
      <p>Miniature de la vidéo <span>*</span></p>
      <input type="file" name="thumb" accept="image/*" required class="box">
      <p>Sélectionner la vidéo <span>*</span></p>
      <input type="file" name="video" accept="video/*" required class="box">
      <input type="submit" value="Ajouter de la vidéo " name="submit" class="btn">
   </form>

</section>

<?php 
// Include the footer
include '../componnents/footer.php'; 
?>

<script src="../js/adm_script.js"></script>

</body>
</html>

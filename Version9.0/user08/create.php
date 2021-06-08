<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$title = $artist = $album = "";
$title_err = $artist_err = $album_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_title = trim($_POST["title"]);
    if(empty($input_title)){
        $title_err = "Please enter a Song Title.";
    } elseif(!filter_var($input_title, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $title_err = "Please enter a valid name.";
    } else{
        $title = $input_title;
    }
    
    // Validate address
    $input_artist = trim($_POST["artist"]);
    if(empty($input_artist)){
        $artist_err = "Please enter an artist.";     
    } else{
        $artist = $input_artist;
    }
    
    // Validate salary
    $input_album = trim($_POST["album"]);
    if(empty($input_album)){
        $album_err = "Please enter an album.";     
    } else{
        $album = $input_album;
    }
    
    // Check input errors before inserting in database
    if(empty($title_err) && empty($artist_err) && empty($album_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO song (title, artist, album) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_title, $param_artist, $param_album);
            
            // Set parameters
            $param_title = $title;
            $param_artist = $artist;
            $param_album = $album;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo $title; ?>">
                            <span class="help-block"><?php echo $title_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($artist_err)) ? 'has-error' : ''; ?>">
                            <label>Artist</label>
                            <textarea name="artist" class="form-control"><?php echo $artist; ?></textarea>
                            <span class="help-block"><?php echo $artist_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($album_err)) ? 'has-error' : ''; ?>">
                            <label>Album</label>
                            <input type="text" name="album" class="form-control" value="<?php echo $album; ?>">
                            <span class="help-block"><?php echo $album_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
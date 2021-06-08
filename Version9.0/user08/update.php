<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$title = $artist = $album = "";
$title_err = $artist_err = $album_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_title = trim($_POST["title"]);
    if(empty($input_title)){
        $title_err = "Please enter a song.";
    } elseif(!filter_var($input_title, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $title_err = "Only letters can be used.";
    } else{
        $title = $input_title;
    }
    
    // Validate address address
    $input_artist = trim($_POST["artist"]);
    if(empty($input_artist)){
        $artist_err = "Please enter an Artist.";     
    } else{
        $artist = $input_artist;
    }
    
    // Validate salary
    $input_album = trim($_POST["album"]);
    if(empty($input_album)){
        $album_err = "Please enter an Album.";     
    } else{
        $album = $input_album;
    }

    
    // Check input errors before inserting in database
    if(empty($title_err) && empty($artist_err) && empty($album_err)){
        // Prepare an update statement
        $sql = "UPDATE song SET title=?, artist=?, album=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_title, $param_artist, $param_album, $param_id);
            
            // Set parameters
            $param_title = $title;
            $param_artist = $artist;
            $param_album = $album;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM game WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $title = $row["title"];
                    $artist = $row["artist"];
                    $album = $row["album"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
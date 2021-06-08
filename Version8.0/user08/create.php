<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $console = $hours = "";
$name_err = $console_err = $hours_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address
    $input_console = trim($_POST["console"]);
    if(empty($input_console)){
        $console_err = "Please enter a console.";     
    } else{
        $console = $input_console;
    }
    
    // Validate salary
    $input_hours = trim($_POST["hours"]);
    if(empty($input_hours)){
        $hours_err = "Please enter an hour amount.";     
    } elseif(!ctype_digit($input_hours)){
        $hours_err = "Please enter a positive integer value.";
    } else{
        $hours = $input_hours;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($console_err) && empty($hours_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO game (name, console, hours) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_console, $param_hours);
            
            // Set parameters
            $param_name = $name;
            $param_console = $console;
            $param_hours = $hours;
            
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
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($console_err)) ? 'has-error' : ''; ?>">
                            <label>Console</label>
                            <textarea name="console" class="form-control"><?php echo $console; ?></textarea>
                            <span class="help-block"><?php echo $console_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($hours_err)) ? 'has-error' : ''; ?>">
                            <label>Hours</label>
                            <input type="text" name="hours" class="form-control" value="<?php echo $hours; ?>">
                            <span class="help-block"><?php echo $hours_err;?></span>
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
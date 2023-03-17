<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$eventName = $startDate = $startYear= $startMonth= $endDate = $endYear= $endMonth = $venueName = "";
$start = $end = $surcharge = NULL;
$eventName_err = $startDate_err= $startYear_err= $startMonth_err= $endDate_err= $endYear_err= $endMonth_err = $surcharge_err = $venueName_err = "";
    
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(empty(trim($_POST["eventName"]))){
        $eventName_err = "Please enter the event name";
    } else {
        $sql = "SELECT Name from event where name = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_eventname);
            $param_eventname = trim($_POST["eventName"]);
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $eventName_err = "This event already exists!";
                } elseif(strlen(trim($_POST["eventName"])) > 30){
                    $eventName_err = "Event name too long! (must less than 30 characters)";
                }else{
                    $eventName = trim($_POST["eventName"]);
                }
            } else{
                echo "<script>alert('Oops! Something went wrong. Please try again later.')</script>";     
            }
            mysqli_stmt_close($stmt);
        }
    }   
    
    if(empty(trim($_POST["venueName"]))){
        $venueName_err = "Please enter the venue name";
    } else {
        $sql = "SELECT Name from venue where name = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_venuename);
            $param_venuename = trim($_POST["venueName"]);
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) != 1){
                    $venueName_err = "This venue does not exist!";
                }else{
                    $venueName = trim($_POST["venueName"]);
                }
            } else{
                echo "<script>alert('Oops! Something went wrong. Please try again later.')</script>";     
            }
            mysqli_stmt_close($stmt);
        }
    }
    
    $today = date("Ymd");
    $setStartDate = 0;
    if(!empty(trim($_POST["startYear"])) || !empty(trim($_POST["startMonth"])) ||!empty(trim($_POST["startDate"]))){
    if(is_numeric(trim($_POST["startYear"])) && is_numeric(trim($_POST["startMonth"])) && is_numeric(trim($_POST["startDate"]))){
        $setStartDate = intval(trim($_POST["startYear"]).trim($_POST["startMonth"]).trim($_POST["startDate"]));
        if(!checkdate(intval(trim($_POST["startMonth"])),intval(trim($_POST["startDate"])),intval(trim($_POST["startYear"])))){           
            $startYear_err = "Invalid Date";
            $startMonth_err = "Invalid Date";
            $startDate_err = "Invalid Date";
        }else if((intval($today) > $setStartDate)){
            $startYear_err = "Must after today";
            $startMonth_err = "after today";
            $startDate_err = "after today";
        }else{
            $startDate = trim($_POST["startDate"]);
            $startMonth = trim($_POST["startMonth"]);
            $startYear = trim($_POST["startYear"]);
            $start = trim($_POST["startYear"]). "-" . trim($_POST["startMonth"]) . "-" . trim($_POST["startDate"]);
        }
    }else{
        $startYear_err = "Require Numbers";
        $startMonth_err = "Require Numbers";
        $startDate_err = "Require Numbers";
        $setStartDate = -1;
    }
    }

    if(!empty(trim($_POST["endYear"])) || !empty(trim($_POST["endMonth"])) ||!empty(trim($_POST["endDate"]))){
    if(is_numeric(trim($_POST["endYear"])) && is_numeric(trim($_POST["endMonth"])) && is_numeric(trim($_POST["endMonth"]))){
    if(!checkdate(intval(trim($_POST["endMonth"])),intval(trim($_POST["endDate"])),intval(trim($_POST["endYear"])))){
        $endYear_err = "Invalid Date";
        $endMonth_err = "Invalid Date";
        $endDate_err = "Invalid Date";
    }else if($setStartDate == 0 && (intval($today) > intval(trim($_POST["endYear"]).trim($_POST["endMonth"]).trim($_POST["endDate"])))){
        $endYear_err = "Must after today";
        $endMonth_err = "after today";
        $endDate_err = "after today";
    }else if(intval($setStartDate) >= intval(trim($_POST["endYear"]).trim($_POST["endMonth"]).trim($_POST["endDate"]))){
        $endYear_err = "After start date";
        $endMonth_err = "After start date";
        $endDate_err = "After start date";

    }else{
        $endDate = trim($_POST["endDate"]);
        $endMonth = trim($_POST["endMonth"]);
        $endYear = trim($_POST["endYear"]);
        $end = trim($_POST["endYear"]). "-" . trim($_POST["endMonth"]) . "-" . trim($_POST["endDate"]);
    }
    }else{
        $endYear_err = "Require Numbers";
        $endMonth_err = "Require Numbers";
        $endDate_err = "Require Numbers";
    }
    }
    
    
    if(floatval(trim($_POST["surcharge"])) == 0 || !empty(trim($_POST["surcharge"]))){
    if(!is_numeric(trim($_POST["surcharge"]))){
        $surcharge_err = "Please enter a decimal.";
    }else if(floatval(trim($_POST["surcharge"]))<0){
        $surcharge_err = "Please enter a decimal bigger than 0.";
    }else{
        $surcharge = trim($_POST["surcharge"]);
    }
    }

    if(empty($eventName_err) && empty($startDate_err) && empty($endDate_err) && empty($surcharge_err)&& empty($startYear_err) && empty($startMonth_err) && empty($endMonth_err)&& empty($endYear_err)&& empty($venueName_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO event (Name, StartDate, EndDate, eventcharge, venueName) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_eventname, $param_start, $param_end, $param_surcharge,$param_venuename);
            
            // Set parameters
            $param_eventname = $eventName;
            $param_start = $start;
            $param_end = $end;
            $param_surcharge = $surcharge;
            $param_venuename = $venueName;
            //Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                echo "<script>alert('New Event Successfully Created')</script>";     
            } else{
                echo "<script>alert('Oops! Something went wrong. Please try again later.')</script>";     
            }

            mysqli_stmt_close($stmt);
        }
    }

}
    // Close connection
    mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Events</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #image {
            width: 360px;
            object-position: -20px 0px;
        }
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; margin: 0 auto; }
        .title{width:400px;padding:20px; margin: 0 auto; }
        .year{width:100px;padding:0px}
        .date{width:90px;padding:0px}
        .month{width:90px;padding:0px}
        .Connection{
            display: inline-block;
            position: relative;
            margin:  5px 0 0;
        }
    </style>
</head>
<body>
<div class="title">
<img id="image" src="ParkingMaster.png" style="width:360px;">
<h2>Create New Event</h2>
</div>
	<div class="wrapper">
           	    	   
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	<div class="form-group">
                <label>Event Name</label>
                <input type="text" name="eventName" class="form-control <?php echo (!empty($eventName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $eventName; ?>">
                <span class="invalid-feedback"><?php echo $eventName_err; ?></span>
    </div>
    
    <div class="form-group">
                <label>Venue Name</label>
                <input type="text" name="venueName" class="form-control <?php echo (!empty($venueName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $venueName; ?>">
                <span class="invalid-feedback"><?php echo $venueName_err; ?></span>
    </div>
    
    	<div class="form-group">
                <label>Start Date (Form as: YYYY-MM-DD)</label>
                
                <div class="Connection">
                <div class="year">
                <input placeholder="YYYY" type="text" name="startYear" class="form-control <?php echo (!empty($startYear_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $startYear; ?>">  
                <span class="invalid-feedback"><?php echo $startYear_err; ?></span>              
                </div>                
                </div>
                <div class="Connection">
                <p>-</p>
                </div>
                
                <div class="Connection">
                <div class="month">
                <input placeholder="MM" type="text" name="startMonth" class="form-control <?php echo (!empty($startMonth_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $startMonth; ?>">
                <span class="invalid-feedback"><?php echo $startMonth_err; ?></span>
                </div>                
                </div>
                
                <div class="Connection">
                <p>-</p>
                </div>
                
                <div class="Connection">
                <div class="date">
                <input placeholder="DD" type="text" name="startDate" class="form-control <?php echo (!empty($startDate_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $startDate; ?>">      
                <span class="invalid-feedback"><?php echo $startDate_err; ?></span>          
                </div>
                </div>
                
                                                
    	</div>    	
    
    <div class="form-group">
                <label>End Date (Form as: YYYY-MM-DD)</label>
                
                <div class="Connection">
                <div class="year">
                <input placeholder="YYYY" type="text" name="endYear" class="form-control <?php echo (!empty($endYear_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $endYear; ?>"> 
                <span class="invalid-feedback"><?php echo $endYear_err; ?></span>               
                </div>                
                </div>
                <div class="Connection">
                <p>-</p>
                </div>
                
                <div class="Connection">
                <div class="month">
                <input placeholder="MM" type="text" name="endMonth" class="form-control <?php echo (!empty($endMonth_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $endMonth; ?>">
                <span class="invalid-feedback"><?php echo $endMonth_err; ?></span>
                </div>                
                </div>
                
                <div class="Connection">
                <p>-</p>
                </div>
                
                <div class="Connection">
                <div class="date">
                <input placeholder="DD" type="text" name="endDate" class="form-control <?php echo (!empty($endDate_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $endDate; ?>">
                <span class="invalid-feedback"><?php echo $endDate_err; ?></span>
                </div>
                </div>
                
                
    </div>    
    <div class="form-group">
                <label>Event Charge</label>
                <input type="text" name="surcharge" class="form-control <?php echo (!empty($surcharge_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $surcharge; ?>">
                <span class="invalid-feedback"><?php echo $surcharge_err; ?></span>
    </div>
            
	<div class="form-group">
    	<input type="submit" class="btn btn-primary" value="Create New Events">
    </div>
        	    	
    </div>
    </form>
    	<div class = "wrapper">
            <a href='venadmin_welcome.php'>
        <button class = "btn btn-danger">
            Go Back
        </button>
    	</a> 
    
    </div>
</body>
</html>
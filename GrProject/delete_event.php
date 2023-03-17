<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$eventName = $startdate = $currentName = $startdateForm= $enddate=$enddateForm="";
$eventName_err = "";
    
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(empty(trim($_POST["eventName"]))){
        $eventName_err = "Please enter the event name";
    } else {
        $sql = "SELECT Name, startdate, enddate from event where name = ?";
        $today = date("Ymd");
        if(($stmt = mysqli_prepare($link, $sql))){
            mysqli_stmt_bind_param($stmt, "s", $param_eventname);
            $param_eventname = trim($_POST["eventName"]);
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);      
                mysqli_stmt_bind_result($stmt, $currentName, $startdate,$enddate);
                mysqli_stmt_fetch($stmt);
                if($startdate != null){
                    $startdateForm = substr($startdate,0,4).substr($startdate,5,2).substr($startdate,8,2);
                }
                if($enddate != null){
                    $enddateForm = substr($enddate,0,4).substr($enddate,5,2).substr($enddate,8,2);
                }
                if(mysqli_stmt_num_rows($stmt) != 1){
                    $eventName_err = "Event: ".trim($_POST["eventName"])." does not exist!";
                }else{
                    $eventName = trim($_POST["eventName"]);
                }
            } else{
                echo '<script>alert("Something went wrong!")</script>';

            }
            mysqli_stmt_close($stmt);
        }
    }               

    if(empty($eventName_err)){
        
        // Prepare an insert statement
        mysqli_stmt_execute(mysqli_prepare($link, "set foreign_key_checks = 0"));
        //$sql = "set foreign_key_checks = 0";               
        $sqlUpdate = "update RESERVATION set CancelStatus = 1 where eventname = ?";
        $sqlDelete = "DELETE from event where (name = ?)";
        if( ($stmt2 = mysqli_prepare($link, $sqlUpdate)) &&($stmt1 = mysqli_prepare($link, $sqlDelete)) ){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt1, "s", $param_eventname);
            mysqli_stmt_bind_param($stmt2, "s", $param_eventname);
            // Set parameters
            $param_eventname = $eventName;
            
            //Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt2) && mysqli_stmt_execute($stmt1)){
                echo '<script>alert("Event has been removed, reservation has been updated.")</script>';          
            }else{
                echo '<script>alert("Oops! Something went wrong. Please try again later.")</script>';     
            }

            mysqli_stmt_close($stmt1);
            mysqli_stmt_close($stmt2);
        }
    }

}
    // Close connection
    //mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Remove Event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #image {
            width: 360px;
            object-position: -20px 0px;
        }
        body{ font: 14px sans-serif; }
        .wrapper{ width: 500px; padding: 20px; margin: 0 auto; }
        .title{width:400px;padding:20px; margin: 0 auto; }        
    </style>
</head>
<body>
<div class="title">
<img id="image" src="ParkingMaster.png" style="width:360px;">
<h2>Remove Event</h2>
</div>

<div class = "wrapper"> 

<?php
// Include config file
require_once "config.php";   

$eventName = $startDate = $endDate = $surcharge =  $venuename ="";
//$rowNumber = 0;
$sql = "SELECT * from event";

$stmt = mysqli_prepare($link, $sql);

$result = mysqli_query($link,"SELECT * FROM event order by name");


echo "<table border='2'>
    <tr>
    <th>Event Name</th>
    <th>Start Date</th>
    <th>End Name</th>
    <th>Event Charge</th>
    <th>Venue Name</th>
    </tr>";
while($row = mysqli_fetch_array($result))
{
    echo "<tr>";
    echo "<td>" .$row["Name"] . "</td>";
    echo "<td>" .$row["StartDate"] . "</td>";
    echo "<td>" .$row["EndDate"] . "</td>";
    echo "<td>" .$row["eventcharge"] . "</td>";
    echo "<td>" .$row["VenueName"] . "</td>";
    echo "</tr>";
}

echo "</table>";
   

    // Close connection
    mysqli_close($link);
?>
</div> 
	<div class="wrapper">           	    	        
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	<div class="form-group">
                <label>Event Name</label>
                <input type="text" name="eventName" class="form-control <?php echo (!empty($eventName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $eventName; ?>">
                <span class="invalid-feedback"><?php echo $eventName_err; ?></span>
    </div>
        	            
	<div class="form-group">
    	<input type="submit" class="btn btn-danger" value="Remove Event">
    </div>
    </form>
       	    	
    </div>
    <div class = "wrapper">
            <a href='venadmin_welcome.php'>
        <button class = "btn btn-danger">
            Go Back
        </button>
    	</a> 
    </div>
</body>
</html>
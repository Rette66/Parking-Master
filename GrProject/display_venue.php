<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #image {
            width: 360px;
            object-position: -20px 0px;
        }
        body{ font: 18px sans-serif; }
        .wrapper{ width: 550px; padding: 20px; margin: 0 auto; }
        .title{width: 550px; padding: 20px; margin: 0 auto; }
    </style>
</head>
<body>
<div class="title">
<img id="image" src="ParkingMaster.png" style="width:360px;">
<h1>Events In Database</h1>
</div>

<div class = "wrapper"> 
<?php
// Include config file
require_once "config.php";   

$eventName = $startDate = $endDate = $surcharge = "";
//$rowNumber = 0;

$result = mysqli_query($link,"SELECT * FROM venue order by name");


echo "<table border='2'>
    <tr>
    <th>Venue Name</th>
    </tr>";
while($row = mysqli_fetch_array($result))
{
    echo "<tr>";
    echo "<td>" .$row["Name"] . "</td>";

    echo "</tr>";
}

echo "</table>";
   

    // Close connection
    mysqli_close($link);
?>
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
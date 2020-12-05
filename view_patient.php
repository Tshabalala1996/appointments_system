<?php

  require_once("connect.php");

  function validate_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
  }

  $firstname = $patient_details = $lastname = $appointment_date = $appointment_time = "";
   $firstname_error = $lastname_error = $time_error = $date_error = "";
  $error = "";



  if(isset($_GET["patient_details"]) && !empty(trim($_GET["patient_details"]))){

     $patient_details = $_GET["patient_details"];
  }


  if($_SERVER["REQUEST_METHOD"] == "POST"){

  $patient_details = validate_input($_POST["patient_details"]);  
  $status = "waiting";


  if(empty($_POST['appointment_date'])) {

    $date_error = "<span class='error-message'>Appointment date is required.</span><br>";

  } else {

    $appointment_date = validate_input($_POST["appointment_date"]);
  
  }

  if(empty($_POST['appointment_time'])) {

    $time_error = "<span class='error-message'>Appointment time is required.</span><br>";

  } else {

    $appointment_time = validate_input($_POST["appointment_time"]);
  
  }
   

  if(empty($date_error) && empty($time_error)){

        $sql = "UPDATE patients SET patient_status = ?, patient_appointment = ?, patient_time = ? WHERE patient_id = ?";

        if($stmt = $conn->prepare($sql)){

            $stmt->bind_param("sssi", $param_status, $param_date, $param_time, $param_id);
            $param_id = $patient_details;
            $param_time = $appointment_time;

            $new_date = date_format(date_create($appointment_date), "Y-m-d");
            $param_date = $new_date;
            $param_status = $status;

            if($stmt->execute()){
               
              $error = "<span class='success-message'>Patient Details Updated.</span><br>";
              $reload = "health_center.php";
          
              header("Refresh: 5; $reload");

            }else{
              $error = "<span class='error-message'>Failed to delete. Please try again later.</span><br>";
            }

            $stmt->close();
        }       
    
     
      

      }
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Update Patient</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php



    $sql = "SELECT * FROM patients WHERE patient_id = ?";

    if($stmt = $conn->prepare($sql)){

       $stmt->bind_param("i", $param_id);
       $param_id = $patient_details;
       if($stmt->execute()){

          $result = $stmt->get_result();
          if($result->num_rows == 1){

             $row = $result->fetch_array(MYSQLI_ASSOC);
             $firstname = $row["patient_firstname"];
             $lastname = $row["patient_lastname"];

          }else{
             
             echo "Failed. Try again later.";
          }
       }

       $stmt->close();
       $conn->close();

    }

?>

<div class="add-form">
<h2>Update Patient Details</h2>
 <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
 <div><?php echo $error;?></div>
  <label>First Name<span style="color:red;">&nbsp*</span></label>
  <input type="text" name="firstname" value="<?php echo $firstname;?>" readonly><br>
  <label> Last Name<span style="color:red;">&nbsp*</span></label>
  <input type="text" name="lastname" value="<?php echo $lastname;?>" readonly><br>
  <label>Appointment Date<span style="color:red;">&nbsp*</span></label>
  <input type="date" name="appointment_date"><br>
    <div><?php echo $date_error;?></div>
  <label>Appointment Time<span style="color:red;">&nbsp*</span></label>
  <input type="time" min="09:00" max="17:00" name="appointment_time"><br>
    <div><?php echo $time_error;?></div>
  <input type="hidden" name="patient_details" value="<?php echo $patient_details;?>">
  <input type="submit" value="Save">
  <a class="button-back" href="health_center.php">Back</a>
</form> 
</div>
</body>
</html>
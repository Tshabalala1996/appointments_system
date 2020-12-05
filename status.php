<?php

require_once("connect.php");

$idnumber_error = $error = $idnumber = "";

function validate_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
   
  if (empty($_POST["idnumber"])) {

    $idnumber_error = "<span class='error-message'>ID or Passport Number is required</span><br>";

  } else {

    $idnumber = validate_input($_POST["idnumber"]);
 
    if (!is_numeric($idnumber)) {
      $idnumber_error = "<span class='error-message'>Invalid ID or Passport Number format</span><br>";
    }
  }
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Appointment Status</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2  style="text-align:center;"><span>Appointment Status</span></h2>
<p style="text-align:center;">If COVID-19 is spreading in your community, stay safe by taking some simple precautions, such as </p><p style="text-align:center;">physical distancing, wearing a mask, avoiding crowds, cleaning your hands, and coughing into a bent elbow or tissue.</p>
  <div class="form-content">
 <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div><?php echo $error;?></div>
    <label>ID number / Passport Number<span style="color:red;">&nbsp*</span></label>
    <input type="text" placeholder="ID number / Passport Number" name="idnumber" value="<?php echo $idnumber;?>">
    <div><?php echo $idnumber_error;?></div>
    <input  name="submit" type="submit" value="Check">
</form>
</div>
<div class="form-content">
<?php
  if(empty($phone_error)){

    $sql = "SELECT * FROM patients WHERE patient_idnumber = ?";
    if($stmt = $conn->prepare($sql)){

       $stmt->bind_param("i", $param_id);
       $param_id = $idnumber;
       if($stmt->execute()){

          $result = $stmt->get_result();
          if($result->num_rows == 1){

             $row = $result->fetch_array(MYSQLI_ASSOC);
             $firstname = $row["patient_firstname"];
             $lastname = $row["patient_lastname"];
             $status = $row["patient_status"];
             $appointment_time = $row["patient_time"];
             $appointment_date = $row["patient_appointment"];

             echo "<h2>Appointment Details</h2>";
             echo "<p><span class='bold-text'>Names:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>".$firstname."&nbsp;&nbsp;".$lastname."</p>";
             echo "<p><span class='bold-text'>Appointment Date:&nbsp;&nbsp;</span><span>".$appointment_date."</span></p>";
             echo "<p><span class='bold-text'>Time:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>".$appointment_time."</p>";
             echo "<p><span class='bold-text'>Status:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>".$status."</p>";

          }else{
             
             echo "No result found!.";
          }
       }

       $stmt->close();
       $conn->close();

    }

  }?>

</div>
</body>
</html>
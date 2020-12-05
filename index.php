<?php

require_once("connect.php");

$firstname_error = $lastname_error = $gender_error = $phone_error = $idnumber_error = $error = "";
$firstname = $lastname = $gender = $phone = $idnumber = "";

function validate_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

  $datetime = date("ymdHis");
  $status = "submitted";
   
  if(empty($_POST['firstname'])) {

    $firstname_error = "<span class='error-message'>First Name is required.</span><br>";

  } else {
    
    $firstname = validate_input($_POST["firstname"]);
         // check if firstname only contains letters and whitespace
    if(!preg_match("/^[a-zA-Z ]*$/", $firstname)) {

      $firstname_error = "<span class='error-message'>Only letters and white space allowed.</span><br>";
    }
  }

  if(empty($_POST['lastname'])) {

    $lastname_error = "<span class='error-message'>Last Name is required.</span><br>";

  } else {
    
    $lastname = validate_input($_POST["lastname"]);
         // check if firstname only contains letters and whitespace
    if(!preg_match("/^[a-zA-Z ]*$/", $lastname)) {

      $lastname_error = "<span class='error-message'>Only letters and white space allowed.</span><br>";
    }
  }

  if(empty($_POST['gender'])) {

    $gender_error = "<span class='error-message'>Gender is required.</span><br>";

  } else {
    
    $gender = validate_input($_POST["gender"]);
         // check if firstname only contains letters and whitespace
  }

  if (empty($_POST["idnumber"])) {

    $idnumber_error = "<span class='error-message'>ID or Passport number is required</span><br>";

  } else {

    $idnumber = validate_input($_POST["idnumber"]);
    if (!is_numeric($idnumber)) {
      $idnumber_error = "<span class='error-message'>Invalid email format</span><br>";
    }
  }

  if (empty($_POST["phone"])) {

    $phone_error = "<span class='error-message'>Phone Number is required</span><br>";

  } else {

    $phone = validate_input($_POST["phone"]);
    // check if e-mail address is well-formed
    if (!is_numeric($phone)) {
      $phone_error = "<span class='error-message'>Invalid email format</span><br>";
    }
  }

  if(empty($firstname_error) && empty($lastname_error) && empty($gender_error) && empty($phone_error)
   && empty($idnumber_error)){

    $sql = "INSERT INTO patients (patient_firstname, patient_lastname, patient_gender, patient_phone, patient_idnumber, patient_code, patient_status) VALUES (?, ?, ?, ?, ?, ?,?)";

     if($stmt = $conn->prepare($sql)){ 

          $stmt->bind_param("sssssss", $firstname, $lastname, $gender, $phone, $idnumber, $datetime, $status);
          if($stmt->execute()){

          $error = "<span class='success-message'>Appointment Booked Successfully.</span><br>";

          $reload = $_SERVER['PHP_SELF'];
          
          header("Refresh: 5; $reload");
          
          }else{

          $error = "<span class='error-message'>Failed to make Appointment.</span><br>";

          }

          $stmt->close();
        }

  }
}

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2  style="text-align:center;"><span>Make an Appointment</span></h2>
<p style="text-align:center;">If COVID-19 is spreading in your community, stay safe by taking some simple precautions, such as </p><p style="text-align:center;">physical distancing, wearing a mask, avoiding crowds, cleaning your hands, and coughing into a bent elbow or tissue.</p>
  <div class="form-content">
 <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div><?php echo $error;?></div>
    <label>Firstname<span style="color:red;">&nbsp*</span></label>
    <input type="text" placeholder="First Name" name="firstname" value="<?php echo $firstname;?>"><br>
    <div><?php echo $firstname_error;?></div>
    <label>Lastname<span style="color:red;">&nbsp*</span></label>
    <input type="text" placeholder="Last Name" name="lastname" value="<?php echo $lastname;?>">
    <div><?php echo $lastname_error;?></div>
    <input type="radio" name="gender" value="male" checked> Male<br>
    <input type="radio" name="gender" value="female"> Female<br>
    <div><?php echo $gender_error;?></div>
    <label>ID number / Passport Number<span style="color:red;">&nbsp*</span></label>
    <input type="text" placeholder="ID number / Passport Number" name="idnumber" value="<?php echo $idnumber;?>">
    <div><?php echo $idnumber_error;?></div>
    <label>Phone number<span style="color:red;">&nbsp*</span></label>
    <input type="text" placeholder="Phone Number" name="phone" value="<?php echo $phone;?>">
    <div><?php echo $phone_error;?></div>
    
    <input  name="submit" type="submit" value="Book Appoitnment"><a class="button-back" href="status.php">Check Status</a>
</form>
</div>
</body>
</html>
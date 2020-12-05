
<!DOCTYPE html>
<html>
<head>
	<title>All Patients</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="main">
<h2>Covid19 Patients Details</h2>
<?php 

require_once("connect.php");

$sql = "SELECT patient_id, patient_firstname, patient_lastname, patient_gender, patient_phone, patient_idnumber, patient_status, patient_appointment, patient_time FROM patients";
if($stmt = $conn->prepare($sql)){

  if($stmt->execute()){

      $result = $stmt->get_result();

      if($result->num_rows > 0){

      echo "<table>";
       echo "<thead>";
          echo "<tr>";
             echo "<th>#</th>";
             echo "<th>Firstname</th>";
             echo "<th>Lastname</th>";
             echo "<th>Gender</th>";
             echo "<th>Phone</th>";
             echo "<th>ID number</th>";
             echo "<th>Appointment Date</th>";
             echo "<th>Time</th>";
             echo "<th>Status</th>";
          echo "<tr>";
       echo "</thead>";
       echo "<tbody>";
       $count = 0;
       while ( $row = $result->fetch_assoc()) {
          $count++;
          echo "<tr>";
            echo "<td>".$count."</td>";
            echo "<td>".$row['patient_firstname']."</td>";
            echo "<td>".$row['patient_lastname']."</td>";
            echo "<td>".$row['patient_gender']."</td>";
            echo "<td>".$row['patient_phone']."</td>";
            echo "<td>".$row['patient_idnumber']."</td>";
            echo "<td>".$row['patient_appointment']."</td>";
            echo "<td>".$row['patient_time']."</td>";
            echo "<td>";
               echo "<a class='button-view' href='view_patient.php?patient_details=".$row['patient_id']."'>".$row['patient_status']."</a>";
            echo "</td>";
          echo "</tr>";

       }
       echo "</tbody>";
       echo "<table>";
     
  }else{

    echo "<p>No records were found</p>";
  }
  
}else{
  echo "Failed to execute ";
}
$stmt->close();
$conn->close();

}

?>
</div>
</body>
</html>


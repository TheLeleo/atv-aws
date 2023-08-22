<?php include "db.inc.php"; ?>
<html>
<body>
<h1>Sample page</h1>
<?php

$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

$database = mysqli_select_db($connection, DB_DATABASE);

VerifyEmployeesTable($connection, DB_DATABASE);

$employee_name = trim($_POST['NAME']);
$employee_address = trim($_POST['ADDRESS']);
$employee_lelis = trim($_POST['LELIS']);
$employee_is_active = isset($_POST['IS_ACTIVE']) ? 1 : 0;

if (!empty($employee_name) || !empty($employee_address)) {
  AddEmployee($connection, $employee_name, $employee_address, $employee_lelis, $employee_is_active);
}
?>

<!-- Input form -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>NAME</td>
      <td>ADDRESS</td>
      <td>Lelis</td>
      <td>is_active</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="NAME" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="ADDRESS" maxlength="90" size="60" />
      </td>
      <td>
        <input type="text" name="LELIS" maxlength="90" size="60" />
      </td>
      <td>
        <input type="checkbox" name="IS_ACTIVE" />
      </td>
      <td>
        <input type="submit" value="Add Data" />
      </td>
    </tr>
  </table>
</form>

<!-- Display table data. -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>NAME</td>
    <td>ADDRESS</td>
    <td>Lelis</td>
    <td>is_active</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM EMPLOYEES");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>", $query_data[0], "</td>",
       "<td>", $query_data[1], "</td>",
       "<td>", $query_data[2], "</td>",
       "<td>", $query_data[3], "</td>",
       "<td>", $query_data[4], "</td>";
  echo "</tr>";
}
?>

</table>

<!-- Clean up. -->
<?php

mysqli_free_result($result);
mysqli_close($connection);

?>

</body>
</html>


<?php

function AddEmployee($connection, $name, $address, $lelis, $is_active) {
  $n = mysqli_real_escape_string($connection, $name);
  $a = mysqli_real_escape_string($connection, $address);
  $l = intval($lelis);

  $query = "INSERT INTO EMPLOYEES (NAME, ADDRESS, LELIS, IS_ACTIVE) VALUES ('$n', '$a', $l, $is_active);";

  if (!mysqli_query($connection, $query)) echo("<p>Error adding employee data.</p>");
}

function VerifyEmployeesTable($connection, $dbName) {
  if (!TableExists("EMPLOYEES", $connection, $dbName)) {
     $query = "CREATE TABLE EMPLOYEES (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         NAME VARCHAR(45),
         ADDRESS VARCHAR(90),
         LELIS INT,
         IS_ACTIVE INT
       )";

     if (!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
  }
}

function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if (mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>

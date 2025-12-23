<?php
session_start();

/* ------------------------------------------
   DATABASE CONNECTION (Fixed)
------------------------------------------- */
$con = mysqli_connect("localhost", "root", "", "mentalhealthsystem");

if (!$con) {
    die("Database not connected: " . mysqli_connect_error());
}

/* ------------------------------------------
   PROCESS ADMIN REGISTRATION
------------------------------------------- */
$message = "";

if (isset($_POST['submit'])) {

    $nm  = trim($_POST['uname']);
    $em  = trim($_POST['email']);
    $ps  = trim($_POST['pass']);

    // Basic validation
    if ($nm === "" || $em === "" || $ps === "") {
        $message = "<p style='color:red; text-align:center;'>All fields are required.</p>";
    } else {

        // Check if email already exists
        $check = $con->prepare("SELECT Email FROM admin WHERE Email=? LIMIT 1");
        $check->bind_param("s", $em);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "<p style='color:red; text-align:center;'>Email already registered!</p>";
        } else {

            // Secure password hashing
            $hashedPass = password_hash($ps, PASSWORD_DEFAULT);

            $stmt = $con->prepare("INSERT INTO admin (Name, Email, Password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nm, $em, $hashedPass);

            if ($stmt->execute()) {
                $message = "<p style='color:green; text-align:center;'>Admin registered successfully!</p>";
            } else {
                $message = "<p style='color:red; text-align:center;'>Registration failed. Try again.</p>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Signup</title>
</head>
<body>
<!-- NAVBAR ADDED (unchanged) -->
<style>
.navbar{
  width:100%; background:#333; padding:10px;
}
.navbar a{
  color:white; margin-right:15px; text-decoration:none; font-weight:bold;
}
.navbar a:hover{ text-decoration:underline; }
</style>

<div class="navbar">
  <a href="viewcity.php">Find Doctors</a>
  <a href="quiz1.html">Take a Quiz</a>
  <a href="diary1.php">Diary</a>
  <a href="clamtech.html">Calming Technique</a>
  <a href="selftracker.html">Mood Tracker</a>
  <a href="selfgrowth.html">Self Growth</a>
</div>

<?php 
// Show success/error message
echo $message; 
?>

<form action="adminsignup.php" method="post">
    <table border="4" cellpadding="5" cellspacing="4" align="center" height="600px" width="800px">
        <tr>
            <th colspan="2" align="center">Admin Registration</th>
        </tr>
        <tr>
            <td>Name</td>
            <td><input type="text" name="uname" placeholder="Enter your name"></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type="email" name="email" placeholder="Enter email"></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="password" name="pass" placeholder="Enter password"></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="submit" value="Register">
            </td>
        </tr>
    </table>
</form>

</body>
</html>

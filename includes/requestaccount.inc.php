<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $middlename = $_POST['middlename'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $civilstatus = $_POST['civilstatus'];
    $dateofbirth = $_POST['dateofbirth'];
    $address = $_POST['address'];
    $mobilenumber = $_POST['mobilenumber'];
    $email = $_POST['email'];
    $telephonenumber = $_POST['telephonenumber'];
    $personneltype = $_POST['personneltype'];
    $departmentid = $_POST['departmentid'];
    $branchofservice = $_POST['branchofservice'];
    $rank = $_POST['rank'];
    $idnumber = $_POST['idnumber'];
    $position = $_POST['position'];
    $frontidimagepath = $_POST['frontidimagepath'];
    $backidimagepath = $_POST['backidimagepath'];

    $errors = [];

    // Assuming email_exists function checks if the email already exists in the accounts table
    if (email_exists($email)) {
        $errors[] = "$email is already registered.";
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            validation_errors($error);
        }
    } else {
        // Adjusted SQL query to match the accounts table structure
        $sql = "INSERT INTO accounts (firstname, lastname, middlename, age, gender, civilstatus, dateofbirth, address, mobilenumber, email, telephonenumber, personneltype, departmentid, branchofservice, rank, idnumber, position, frontidimagepath, backidimagepath)
        VALUES ('$firstname', '$lastname', '$middlename', '$age', '$gender', '$civilstatus', '$dateofbirth', '$address', '$mobilenumber', '$email', '$telephonenumber', '$personneltype', '$departmentid', '$branchofservice', '$rank', '$idnumber', '$position', '$frontidimagepath', '$backidimagepath')";

        if ($conn->query($sql) === TRUE) {
            redirect("index.php");
            exit;
        } else {
            set_message("<p>Error: " . $sql . "<br>" . $conn->error . "</p>");
        }

        $conn->close();
    }
}
?>

<?php
        if (isset($_POST["submit"])) {
           $fullName = $_POST["fullname"];
           $email = $_POST["email"];
           $password = $_POST["password"];
           $passwordRepeat = $_POST["repeat_password"];
           
           $passwordHash = password_hash($password, PASSWORD_DEFAULT);

           $errors = array();
           
           if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
            array_push($errors,"All fields are required");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
           }
           if (strlen($password)<8) {
            array_push($errors,"Password must be at least 8 charactes long");
           }
           if ($password!==$passwordRepeat) {
            array_push($errors,"Password does not match");
           }
           require_once "database.php";
           $sql = "SELECT * FROM users WHERE email = '$email'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount>0) {
            array_push($errors,"Email already exists!");
           }
           if (count($errors)>0) {
            foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
           }else{
            
            $sql = "INSERT INTO users (full_name, email, password) VALUES ( ?, ?, ? )";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"sss",$fullName, $email, $passwordHash);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>You are registered successfully.</div>";
            }else{
                die("Something went wrong");
            }
           }
          

        }
?>

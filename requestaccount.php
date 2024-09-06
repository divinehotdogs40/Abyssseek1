<?php
include_once "header.php";

$errors = []; // Initialize an array to store errors
$form_data = []; // Initialize an array to store form data

// Check if the form is submitted
if(isset($_POST['submit'])) {
    // Process form data
    $form_data = $_POST; // Store form data to repopulate the fields
    $f_name = $_POST['firstName'];
    $m_name = $_POST['middleName'];
    $l_name = $_POST['lastName'];
    $age = $_POST['age'];
    $Sex = $_POST['gender'];
    $civilStatus = $_POST['civilStatus'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $address = $_POST['address'];
    $mobileNum = $_POST['mobileNumber'];
    $email = $_POST['email'];
    $telephoneNum = $_POST['telephoneNumber'];
    $personnelType = $_POST['personnelType'];
    $department = $_POST['department'];
    $departmentID = $_POST['departmentID'];
    $branchOfService = $_POST['branchOfService'];
    $rank = $_POST['rank'];
    $position = $_POST['position'];

    // Validate age
    if ($age < 18) {
        $errors['age'] = "You must be 18 years old or above!!";
    }

    // Validate date of birth
    $today = new DateTime();
    $dob = new DateTime($dateOfBirth);
    $ageFromDate = $dob->diff($today)->y;
    if ($ageFromDate != $age) {
        $errors['dateOfBirth'] = "Date of Birth does not match the entered age!!";
    }

    // If there are no validation errors, proceed with database insertion
    if (empty($errors)) {
        // Connect to database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "abyssseek";

        $conn = new mysqli($servername, $username, $password, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle file uploads
        if(isset($_FILES['frontIdPicture']) && !empty($_FILES['frontIdPicture']['name'])) {
            $frontIdPicture = $_FILES['frontIdPicture']['name'];
            $frontIdPictureTmp = $_FILES['frontIdPicture']['tmp_name'];
            move_uploaded_file($frontIdPictureTmp, "admin/request_form/uploads/" . $frontIdPicture);
        } else {
            $frontIdPicture = "";
        }

        if(isset($_FILES['backIdPicture']) && !empty($_FILES['backIdPicture']['name'])) {
            $backIdPicture = $_FILES['backIdPicture']['name'];
            $backIdPictureTmp = $_FILES['backIdPicture']['tmp_name'];
            move_uploaded_file($backIdPictureTmp, "admin/request_form/uploads/" . $backIdPicture);
        } else {
            $backIdPicture = "";
        }

        // Prepare SQL query
        $sql = "INSERT INTO requests (first_name, middle_name, last_name, email, Age, Sex, CivilStatus, DateOfBirth, Address, MobileNum, TelephoneNum, PersonnelType, Department, Departmentid, BranchOfService, Rank, Position, FrontPic, BackPic, Date_Time) 
        VALUES ('$f_name', '$m_name', '$l_name', '$email', '$age', '$Sex', '$civilStatus', '$dateOfBirth', '$address', '$mobileNum', '$telephoneNum', '$personnelType', '$department', '$departmentID', '$branchOfService', '$rank', '$position', '$frontIdPicture', '$backIdPicture', CURRENT_TIMESTAMP())";

        // Execute SQL query
        if (mysqli_query($conn, $sql)) {
            echo "Request submitted successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        // Close database connection
        mysqli_close($conn);
    }
}

?>

<?php include_once "header.php"; ?>

<style>
    .custom-file-upload {
        height: 200px;
        width: 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed #cacaca;
        background-color: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0px 48px 35px -48px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .custom-file-upload .text {
        font-weight: 400;
        color: rgba(75, 85, 99, 1);
    }

    .custom-file-upload input {
        display: none;
    }

    .form-control {
        background-color: white;
        color: black;
    }

    .btn {
        background-color: #45acab !important;
    }

    .error {
    color: red;
    font-size: 1rem; /* Increased font size */
    position: absolute;
    margin-top: -1.2em; /* Adjust based on your design */
    margin-left: 10px; /* Adjust based on your design */
}

</style>

<div class="container mt-5" style="padding-bottom:70px">
    <h2>Request Account Form</h2>
    <form action="requestaccount.php" method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="firstName">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo isset($form_data['firstName']) ? $form_data['firstName'] : ''; ?>" required>
            </div>
            <div class="form-group col-md-4">
                <label for="lastName">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo isset($form_data['lastName']) ? $form_data['lastName'] : ''; ?>" required>
            </div>
            <div class="form-group col-md-4">
                <label for="middleName">Middle Name</label>
                <input type="text" class="form-control" id="middleName" name="middleName" value="<?php echo isset($form_data['middleName']) ? $form_data['middleName'] : ''; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="age">Age</label>
                <input type="number" class="form-control" id="age" name="age" value="<?php echo isset($form_data['age']) ? $form_data['age'] : ''; ?>" required>
                <?php if(isset($errors['age'])) { ?>
                    <div class="error"><?php echo $errors['age']; ?></div>
                <?php } ?>
            </div>
            <div class="form-group col-md-4">
                <label for="gender">Gender</label>
                <select id="gender" class="form-control" name="gender" required>
                    <option value="Male" <?php echo (isset($form_data['gender']) && $form_data['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo (isset($form_data['gender']) && $form_data['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo (isset($form_data['gender']) && $form_data['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="civilStatus">Civil Status</label>
                <select id="civilStatus" class="form-control" name="civilStatus" required>
                    <option value="Single" <?php echo (isset($form_data['civilStatus']) && $form_data['civilStatus'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                    <option value="Married" <?php echo (isset($form_data['civilStatus']) && $form_data['civilStatus'] == 'Married') ? 'selected' : ''; ?>>Married</option>
                    <option value="Divorced" <?php echo (isset($form_data['civilStatus']) && $form_data['civilStatus'] == 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                    <option value="Widowed" <?php echo (isset($form_data['civilStatus']) && $form_data['civilStatus'] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="dateOfBirth">Date of Birth</label>
                <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth" value="<?php echo isset($form_data['dateOfBirth']) ? $form_data['dateOfBirth'] : ''; ?>" required>
                <?php if(isset($errors['dateOfBirth'])) { ?>
                    <div class="error"><?php echo $errors['dateOfBirth']; ?></div>
                <?php } ?>
            </div>
            <div class="form-group col-md-8">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo isset($form_data['address']) ? $form_data['address'] : ''; ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="mobileNumber">Mobile Number</label>
                <input type="text" class="form-control" id="mobileNumber" name="mobileNumber" value="<?php echo isset($form_data['mobileNumber']) ? $form_data['mobileNumber'] : ''; ?>" required>
            </div>
            <div class="form-group col-md-4">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($form_data['email']) ? $form_data['email'] : ''; ?>" required>
            </div>
            <div class="form-group col-md-4">
                <label for="telephoneNumber">Telephone Number</label>
                <input type="text" class="form-control" id="telephoneNumber" name="telephoneNumber" value="<?php echo isset($form_data['telephoneNumber']) ? $form_data['telephoneNumber'] : ''; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="personnelType">Personnel Type</label>
                <select id="personnelType" class="form-control" name="personnelType" required>
                    <option value="" <?php echo (!isset($form_data['personnelType']) || $form_data['personnelType'] == '') ? 'selected' : ''; ?> disabled>Select Personnel Type</option>
                    <option value="Military" <?php echo (isset($form_data['personnelType']) && $form_data['personnelType'] == 'Military') ? 'selected' : ''; ?>>Military</option>
                    <option value="Civilian" <?php echo (isset($form_data['personnelType']) && $form_data['personnelType'] == 'Civilian') ? 'selected' : ''; ?>>Civilian</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="department">Department</label>
                <input type="text" class="form-control" id="department" name="department" value="<?php echo isset($form_data['department']) ? $form_data['department'] : ''; ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="departmentID">Department ID</label>
                <input type="text" class="form-control" id="departmentID" name="departmentID" value="<?php echo isset($form_data['departmentID']) ? $form_data['departmentID'] : ''; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="branchOfService">Branch of Service</label>
                <input type="text" class="form-control" id="branchOfService" name="branchOfService" value="<?php echo isset($form_data['branchOfService']) ? $form_data['branchOfService'] : ''; ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="rank">Rank</label>
                <input type="text" class="form-control" id="rank" name="rank" value="<?php echo isset($form_data['rank']) ? $form_data['rank'] : ''; ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="position">Position</label>
                <input type="text" class="form-control" id="position" name="position" value="<?php echo isset($form_data['position']) ? $form_data['position'] : ''; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frontIdPicture">Front of ID Picture</label>
            <input type="file" class="form-control-file" id="frontIdPicture" name="frontIdPicture" accept="image/*" required>
        </div>
        <div class="form-group">
            <label for="backIdPicture">Back of ID Picture</label>
            <input type="file" class="form-control-file" id="backIdPicture" name="backIdPicture" accept="image/*" required>
        </div>
        <br>
        <input type="submit" class="btn" value="Submit" name="submit">
    </form>
</div>

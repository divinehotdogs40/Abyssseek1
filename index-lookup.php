<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

$email = $_SESSION['EmailEntry'];

$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'abyssseek';

$conn1 = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn1->connect_error) {
    die("Connection failed: " . $conn1->connect_error);
}

$sql1 = "SELECT * FROM history_lookup_linkedin WHERE Email = '$email' ORDER BY ID DESC";
$result1 = $conn1->query($sql1);

$sql2 = "SELECT * FROM history_lookup_skype WHERE Email = '$email' ORDER BY ID DESC";
$result2 = $conn1->query($sql2);

$sql3 = "SELECT * FROM history_lookup_sociallinkssearch WHERE Email = '$email' ORDER BY ID DESC";
$result3 = $conn1->query($sql3);

$sql4 = "SELECT * FROM history_lookup_websitesocialscraper WHERE Email = '$email' ORDER BY ID DESC";
$result4 = $conn1->query($sql4);


if ($result1->num_rows > 0) {
    $row1 = $result1->fetch_assoc(); {
        $Name = $row1['DisplayName'];
        $Email = $row1['EmailInput'];
        $PhoneNumber = $row1['PhoneNumbers'];
        $Location = $row1['Location'];
        $Schools = $row1['Schools'];
        $Positions = $row1['Positions'];
        $Skills = $row1['Skills'];
    }
} else {
    echo "No results found in Database";
}

if ($result2->num_rows > 0) {
    $row2 = $result2->fetch_assoc(); {
        $SkypeName = $row2['AccName'];
        $SkypeID = $row2['ProfileID'];
    }
} else {
    echo "No results found in Database";
}

if ($result3->num_rows > 0) {
    $row3 = $result3->fetch_assoc(); {
        $PCFacebook = $row3['Facebook'];
        $PCInstagram = $row3['Instagram'];
        $PCTwitter = $row3['Twitter'];
        $PCTikTok = $row3['Tiktok'];
        $PCSnapchat = $row3['Snapchat'];
        $PCYouTube = $row3['YouTube'];
        $PCYouTubeMusic = $row3['YouTubeMusic'];
        $PCLinkedIn = $row3['LinkedIn'];
        $PCGitHub = $row3['GitHub'];
        $PCPinterest = $row3['Pinterest'];
    }
} else {
    echo "No results found in Database";
}

if ($result4->num_rows > 0) {
    $row4 = $result4->fetch_assoc(); {
        $PAFacebook = $row4['Facebook'];
        $PAInstagram = $row4['Instagram'];
        $PATwitter = $row4['Twitter'];
        $PATikTok = $row4['Tiktok'];
        $PASnapchat = $row4['Snapchat'];
        $PAYouTube = $row4['YouTube'];
        $PALinkedIn = $row4['LinkedIn'];
        $PAGitHub = $row4['GitHub'];
        $PCEmails = $row4['Emails'];
        $PCPhoneNumbers = $row4['Phones'];
    }
} else {
    echo "No results found in Database";
}

$conn1->close();
?>

<?php
$SchoolsData = json_decode($Schools, true);
$PositionsData = json_decode($Positions, true);
$SkillsData = json_decode($Skills, true);

$months = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
];

function formatDate($date) {
    global $months;
    $dateStr = '';
    
    if (!empty($date['year'])) {
        $dateStr .= $date['year'];
        if (!empty($date['month'])) {
            $dateStr = $months[$date['month']] . ' ' . $dateStr;
            if (!empty($date['day'])) {
                $dateStr = $date['day'] . ' ' . $dateStr;
            }
        }
    }

    return $dateStr;
}

?>


<?php

$userID = $_SESSION['user_id'];
$emailEntry = $_SESSION['EmailEntry'];

ini_set('max_execution_time', 9999999999);

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abyssseek";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_SESSION["startButton"])) {
    session_write_close();
    unset($_SESSION["startButton"]);

    
    if (!empty($_POST['inputField'])) {

        $forceStopping = 'files/lookup/data/' . $userID . '/control.txt';
        @file_put_contents($forceStopping, 'This system is designed exclusively for use by the Armed Forces of the Philippines and is subject to strict confidentiality and security measures. It is prohibited to copy, modify, or make any alterations to the system without explicit authorization. Unauthorized access or use of this system may result in disciplinary action or legal consequences.');


        $inputField = escapeshellarg($_POST['inputField']);
        
        $command = "python C:/xampp/htdocs/abyssseek/files/lookup/linkedin.py $userID $inputField $emailEntry";
        exec($command, $output, $return_value);

        $command = "python C:/xampp/htdocs/abyssseek/files/lookup/skype.py $userID $inputField $emailEntry";
        exec($command, $output, $return_value);

        $sql = "SELECT DisplayName FROM history_lookup_linkedin WHERE Email = ? ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $emailEntry);
        $stmt->execute();
        $result = $stmt->get_result();

        

        if ($result->num_rows > 0) {
            
            $row = $result->fetch_assoc();
            $displayName = $row['DisplayName'];


            if (!empty($displayName)) {
                $command = "python C:/xampp/htdocs/abyssseek/files/lookup/website_social_scraper.py $userID \"$displayName\" $emailEntry";
                exec($command, $output, $return_value);

                $command = "python C:/xampp/htdocs/abyssseek/files/lookup/social_links_search.py $userID \"$displayName\" $emailEntry";
                exec($command, $output, $return_value);

                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();

            } else {
                
                $sql = "SELECT AccName FROM history_lookup_skype WHERE Email = ? ORDER BY id DESC LIMIT 1";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $emailEntry);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    
                    $row = $result->fetch_assoc();
                    $accName = $row['AccName'];

                    if (!empty($accName)) {
                        $command = "python C:/xampp/htdocs/abyssseek/files/lookup/website_social_scraper.py $userID \"$accName\" $emailEntry";
                        exec($command, $output, $return_value);

                        $command = "python C:/xampp/htdocs/abyssseek/files/lookup/social_links_search.py $userID \"$accName\" $emailEntry";
                        exec($command, $output, $return_value);

                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit();

                    }

                    else{

                        $command = "python C:/xampp/htdocs/abyssseek/files/lookup/website_social_scraper.py $userID $inputField $emailEntry";
                        exec($command, $output, $return_value);

                        $command = "python C:/xampp/htdocs/abyssseek/files/lookup/social_links_search.py $userID $inputField $emailEntry";
                        exec($command, $output, $return_value);

                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit();

                    }

            }


            
        }
        } 

        
        $stmt->close();

        

        
        echo '<script>window.location.reload();</script>';
    }
}


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abyssseek Lookup</title>
    <link rel="stylesheet" href="lookup.css">

        

</head>
<body>
    <div class="container" id="container">

            <img src="images/emptyProfile.png" alt="Description of Image" style="margin-left: 500px; width: 150px; height: 150px;">

        <h1>
            <img src="images/user.png" alt="Icon" style="width: 24px; height: 24px;">
            Personal Information
        </h1>
        <div style="max-width: 50%;">
        <h3> 
            <img src ="images/pencil.png" alt="Icon" style="width: 24px; height: 24px;">
            Name:&nbsp;<span class="results"><?php echo $Name; ?></span>
        </h3>
        <h3> 
            <img src ="images/arroba.png" alt="Icon" style="width: 24px; height: 24px;">
            Email:&nbsp;<span class="results"><?php echo $Email; ?></span>
        </h3>
        <h3> 
            <img src ="images/phone.png" alt="Icon" style="width: 24px; height: 24px;">
            Phone Number:&nbsp;<span class="results"><?php echo $PhoneNumber; ?></span>
        </h3>
    </div>
        <div style="align-items: center; max-width: 50%; margin-left: 50%; margin-top: -108px;">
        <h3> 
            <img src ="images/skype.png" alt="Icon" style="width: 24px; height: 24px;">
            Skype Name:&nbsp;<span class="results"><?php echo $SkypeName; ?></span>
        </h3>
        <h3> 
            <img src ="images/id.png" alt="Icon" style="width: 24px; height: 24px;">
            Skype ID:&nbsp;<span class="results"><?php echo $SkypeID; ?></span>
        </h3>
        <h3> 
            <img src ="images/location.png" alt="Icon" style="width: 24px; height: 24px;">
            Location:&nbsp;<span class="results"><?php echo $Location; ?></span>
        </h3>
        </div>
        <br>
        <hr style="border-width: 5px; border-color: #D9CAB3; ">
        <br>
        <h1>
            <img src="images/hiring.png" alt="Icon" style="width: 24px; height: 24px;">
            Background
        </h1>
        <h3> 
            <img src ="images/graduation-hat.png" alt="Icon" style="width: 24px; height: 24px;">
            Schools
          
            <div style="margin-left: 320px; margin-bottom: -1px;"> <img src ="images/employee.png" alt="Icon" style="width: 24px; height: 24px;"></div>
            <div style="margin-left: 0px;">Positions</div>
            
            <div style="margin-left: 297px;"><img src ="images/skills.png" alt="Icon" style="width: 24px; height: 24px;"></div>
            <div style="margin-left: -2px;">Skills
            </div>
        </h3>
        <div class="inner-container">
            <?php

    if (is_array($SchoolsData)){
            foreach ($SchoolsData as $school) {
                $schoolName = $school['schoolName'];
                $degreeName = $school['degreeName'];
                $startDate = $school['startEndDate']['start'];
                $endDate = $school['startEndDate']['end'];
                $fieldOfStudy = $school['fieldOfStudy'];
            
                $startStr = formatDate($startDate);
                $endStr = formatDate($endDate);
            
                $dateStr = '';
                if (!empty($startStr) || !empty($endStr)) {
                    $dateStr = trim($startStr) . ' - ' . trim($endStr);
                }
            
                echo '<span class="resultsContainerKeys">School Name:</span> <span class="resultsContainerValues">' . $schoolName . "</span><br>";
                echo '<span class="resultsContainerKeys">Degree Name:</span> <span class="resultsContainerValues">' . $degreeName . "</span><br>";
                echo '<span class="resultsContainerKeys">Date:</span> <span class="resultsContainerValues">' . $dateStr . "</span><br>";
                echo '<span class="resultsContainerKeys">Field of Study:</span> <span class="resultsContainerValues">' . $fieldOfStudy . "</span><br><br><br>";
            }
        }
        
            ?>
        </div>
        <div class="inner-container1">
            <?php
            if (is_array($PositionsData)){
            foreach ($PositionsData as $job) {
                $title = $job['title'];
                $startDate = $job['startEndDate']['start'];
                $endDate = $job['startEndDate']['end'];
                $companyName = $job['companyName'];
            
                $startStr = formatDate($startDate);
                $endStr = formatDate($endDate);
            
                $dateStr = '';
                if (!empty($startStr) || !empty($endStr)) {
                    $dateStr = trim($startStr) . ' - ' . trim($endStr);
                }
            
                echo '<span class="resultsContainerKeys">Job Title:</span> <span class="resultsContainerValues">' . $title . "</span><br>";
                echo '<span class="resultsContainerKeys">Company Name:</span> <span class="resultsContainerValues">' . $companyName . "</span><br>";
                echo '<span class="resultsContainerKeys">Date:</span> <span class="resultsContainerValues">' . $dateStr . "</span><br><br><br>";
            }
        }

            ?>
        </div>
        <div class="inner-container2">
            <?php
            if (is_array($SkillsData)){
            foreach ($SkillsData as $skill) {
                echo '<span class="resultsContainerValues">' . $skill['name'] . "</span><br>";
            }
        }

            ?>
        </div>
        <br><br><br>
        <hr style="border-width: 5px; border-color: #D9CAB3; ">
        <br>
        <h1> 
            <img src ="images/socials.png" alt="Icon" style="width: 24px; height: 24px;">
            Possible Accounts
        </h1>
        <div style="max-width: 50%;">
        <h3> 
            <img src ="images/facebook.png" alt="Icon" style="width: 24px; height: 24px;">
            Facebook:&nbsp;<span class="resultsLinks"><a href="<?php echo $PAFacebook; ?>" target="_blank"><?php echo $PAFacebook; ?></a></span>
        </h3>
        <h3> 
            <img src ="images/instagram.png" alt="Icon" style="width: 24px; height: 24px;">
            Instagram:&nbsp;<span class="resultsLinks"><a href="<?php echo $PAInstagram; ?>" target="_blank"><?php echo $PAInstagram; ?></a></span>
        </h3>
        <h3> 
            <img src ="images/twitter.png" alt="Icon" style="width: 24px; height: 24px;">
            Twitter:&nbsp;<span class="resultsLinks"><a href="<?php echo $PATwitter; ?>" target="_blank"><?php echo $PATwitter; ?></a></span>
        </h3>
        <h3> 
            <img src ="images/tiktok.png" alt="Icon" style="width: 24px; height: 24px;">
            TikTok:&nbsp;<span class="resultsLinks"><a href="<?php echo $PATikTok; ?>" target="_blank"><?php echo $PATikTok; ?></a></span>
        </h3>
        </div>
        <div style="align-items: center; max-width: 50%; margin-left: 50%; margin-top: -144px;">
        <h3> 
            <img src ="images/snapchat.png" alt="Icon" style="width: 24px; height: 24px;">
            Snapchat:&nbsp;<span class="resultsLinks"><a href="<?php echo $PASnapchat; ?>" target="_blank"><?php echo $PASnapchat; ?></a></span>
        </h3>
        <h3> 
            <img src ="images/youtube.png" alt="Icon" style="width: 24px; height: 24px;">
            YouTube:&nbsp;<span class="resultsLinks"><a href="<?php echo $PAYouTube; ?>" target="_blank"><?php echo $PAYouTube; ?></a></span>
        </h3>
        <h3>
            <img src ="images/linkedin-logo.png" alt="Icon" style="width: 24px; height: 24px;">
            LinkedIn:&nbsp;<span class="resultsLinks"><a href="<?php echo $PALinkedIn; ?>" target="_blank"><?php echo $PALinkedIn; ?></a></span>
        </h3>
        <h3> 
            <img src ="images/github.png" alt="Icon" style="width: 24px; height: 24px;">
            GitHub:&nbsp;<span class="resultsLinks"><a href="<?php echo $PAGitHub; ?>" target="_blank"><?php echo $PAGitHub; ?></a></span>
        </h3>
        </div>
        <br>
        <hr style="border-width: 5px; border-color: #D9CAB3; ">
        <br>
        <div>
        <h1> 
            <img src ="images/connect.png" alt="Icon" style="width: 24px; height: 24px;">
            Possible Connections
        </h1>




    <div>
        <h3> 
            <img src ="images/arroba.png" alt="Icon" style="width: 24px; height: 24px;">
            Emails
          
            <div style="margin-left: 220px; margin-bottom: -1px;"> <img src ="images/phone.png" alt="Icon" style="width: 24px; height: 24px;"></div>
            <div style="margin-left: 0px; width: 48px;">Phone Numbers</div>
            
            <div style="margin-left: 215px;"><img src ="images/facebook.png" alt="Icon" style="width: 24px; height: 24px;"></div>
            <div style="margin-left: -2px;">Facebook
            </div>

            <div style="margin-left: 205px;"><img src ="images/instagram.png" alt="Icon" style="width: 24px; height: 24px;"></div>
            <div style="margin-left: -2px;">Instagram
            </div>
        </h3>
        <div class="conn-container1">
            <?php 
            $PCEmails = explode(', ', $PCEmails);
            foreach ($PCEmails as $l){
                $l = str_replace("'","",$l);
                echo '<span class="results">' . $l . '</span><br>';
            }
            ?>
        </div>
        <div class="conn-container2">
            <?php 
            $PCPhoneNumbers = explode(', ', $PCPhoneNumbers);
            foreach ($PCPhoneNumbers as $l){
                $l = str_replace("'","",$l);
                echo '<span class="results">' . $l . '</span><br>';
            }
            ?>
        </div>
        <div class="conn-container3">
            <?php 
            $PCFacebook = explode(', ', $PCFacebook);
            foreach ($PCFacebook as $l){
                $la = str_replace("https://www.","",$l);
                $la = str_replace("https://","",$la);
                echo '<a href="' . $l . '" target="_blank">' . $la . '</a><br>';
            }
            ?>
        </div>
        <div class="conn-container4">
        <?php 
            $PCInstagram = explode(', ', $PCInstagram);
            foreach ($PCInstagram as $l){
                $la = str_replace("https://www.","",$l);
                $la = str_replace("https://","",$la);
                echo '<a href="' . $l . '" target="_blank">' . $la . '</a><br>';
            }
            ?>
        </div>
    </div>
    <br> 
    <div>
    <h3> 
            <img src ="images/twitter.png" alt="Icon" style="width: 24px; height: 24px;">
            Twitter
          
            <div style="margin-left: 220px; margin-bottom: -1px;"> <img src ="images/tiktok.png" alt="Icon" style="width: 24px; height: 24px;"></div>
            <div style="margin-left: 0px; width: 48px;">TikTok</div>
            
            <div style="margin-left: 215px;"><img src ="images/snapchat.png" alt="Icon" style="width: 24px; height: 24px;"></div>
            <div style="margin-left: -2px;">Snapchat
            </div>

            <div style="margin-left: 205px;"><img src ="images/youtube.png" alt="Icon" style="width: 24px; height: 24px;"></div>
            <div style="margin-left: -2px;">YouTube
            </div>
        </h3>
        <div class="conn-container5">
            <?php 
            $PCTwitter = explode(', ', $PCTwitter);
            foreach ($PCTwitter as $l){
                $la = str_replace("https://www.","",$l);
                $la = str_replace("https://","",$la);
                echo '<a href="' . $l . '" target="_blank">' . $la . '</a><br>';
            }
            ?>
        </div>
        <div class="conn-container6">
            <?php 
            $PCTikTok = explode(', ', $PCTikTok);
            foreach ($PCTikTok as $l){
                $la = str_replace("https://www.","",$l);
                $la = str_replace("https://","",$la);
                echo '<a href="' . $l . '" target="_blank">' . $la . '</a><br>';
            }
            ?>
        </div>
        <div class="conn-container7">
            <?php 
            $PCSnapchat = explode(', ', $PCSnapchat);
            foreach ($PCSnapchat as $l){
                $la = str_replace("https://www.","",$l);
                $la = str_replace("https://","",$la);
                echo '<a href="' . $l . '" target="_blank">' . $la . '</a><br>';
            }
            ?>
        </div>
        <div class="conn-container8">
            <?php 
            $PCYouTube = explode(', ', $PCYouTube);
            foreach ($PCYouTube as $l){
                $la = str_replace("https://www.","",$l);
                $la = str_replace("https://","",$la);
                echo '<a href="' . $l . '" target="_blank">' . $la . '</a><br>';
            }
            ?>
        </div>
    </div>
<br>
    <div>
    <h3> 
            <img src ="images/music.png" alt="Icon" style="width: 24px; height: 24px;">
            YouTube Music
          
            <div style="margin-left: 170px; margin-bottom: -1px;"> <img src ="images/linkedin-logo.png" alt="Icon" style="width: 24px; height: 24px;"></div>
            <div style="margin-left: 0px; width: 48px;">LinkedIn</div>
            
            <div style="margin-left: 215px;"><img src ="images/github.png" alt="Icon" style="width: 24px; height: 24px;"></div>
            <div style="margin-left: -2px;">GitHub
            </div>

            <div style="margin-left: 225px;"><img src ="images/pinterest.png" alt="Icon" style="width: 24px; height: 24px;"></div>
            <div style="margin-left: -2px;">Pinterest
            </div>
        </h3>
        <div class="conn-container9">
            <?php 
            $PCYouTubeMusic = explode(', ', $PCYouTubeMusic);
            foreach ($PCYouTubeMusic as $l){
                $la = str_replace("https://www.","",$l);
                $la = str_replace("https://","",$la);
                echo '<a href="' . $l . '" target="_blank">' . $la . '</a><br>';
            }
            ?>
        </div>
        <div class="conn-container10">
            <?php 
            $PCLinkedIn = explode(', ', $PCLinkedIn);
            foreach ($PCLinkedIn as $l){
                $la = str_replace("https://www.","",$l);
                $la = str_replace("https://","",$la);
                echo '<a href="' . $l . '" target="_blank">' . $la . '</a><br>';
            }
            ?>
        </div>
        <div class="conn-container11">
            <?php 
            $PCGitHub = explode(', ', $PCGitHub);
            foreach ($PCGitHub as $l){
                $la = str_replace("https://www.","",$l);
                $la = str_replace("https://","",$la);
                echo '<a href="' . $l . '" target="_blank">' . $la . '</a><br>';
            }
            ?>
        </div>
        <div class="conn-container12">
            <?php 
            $PCPinterest = explode(', ', $PCPinterest);
            foreach ($PCPinterest as $l){
                $la = str_replace("https://www.","",$l);
                $la = str_replace("https://","",$la);
                echo '<a href="' . $l . '" target="_blank">' . $la . '</a><br>';
            }
            ?>
        </div>
    </div>
    </div>

<form id="searchForm" method="post" style="position: fixed; top: 75px; left: 325px;">

    <label for="inputField" style="color: white; font-family: main2, sans-serif; position: fixed; top: 180px; left: 100px;">Email Address:&nbsp;</label>
    <input type="text" id="inputField" name="inputField" style="font-family: main2, sans-serif; width: 225px; height: 30px; position: fixed; top: 210px; left: 43px;" placeholder="abyssseek@example.com" required><br><br>

    <button type="submit" id="startButton" style="background-color: #007bff; display: none; font-family: 'buttons', sans-serif; width: 100px; height: 30px; position: fixed; top: 280px; left: 100px; font-family: main3, sans-serif;" onclick="checkForTextFile(), hideSeekButton()" this.style.display='none';>SEEK</button>

</form>
   
    <img id="loadingIcon" src="files/assets/lookup_loading.gif" alt="loading..." style="position: fixed; top: 225px; left: 90px; width: 120px; height: 140px; display: none;">
    <img src="files/assets/LookUpSearch.png" alt="loading..." style="position: fixed; top: 65px; left: 645px; width: 724.5px; height: 70.5px;">

<script>
function hideSeekButton(){
    document.getElementById('startButton').style.display = 'none';
    document.getElementById('loadingIcon').style.display = 'block';
}

document.getElementById('loadingIcon').style.display = 'block';

function checkForTextFile() {
    setTimeout(function() {
        var userIDcheck = "<?php echo $userID; ?>";
        var url = 'files/lookup/data/' + userIDcheck + '/control.txt?timestamp=' + new Date().getTime();

        fetch(url, {
            cache: 'no-store'
        })
        .then(response => {
            if (response.ok) {
                logoAnimation();
            } else {
                document.getElementById('loadingIcon').style.display = 'none';
                document.getElementById('startButton').style.display = 'block';
                
            }
        })
        .catch(error => {
            console.error(error);
        });
    }, 2000);
}

checkForTextFile();

</script>
</body>
</html>

       

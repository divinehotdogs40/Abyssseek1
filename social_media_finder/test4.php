<?php 
$servername = "localhost";
$username = "root";
$password = "";
$database = "abyssseek";

// Connect to MySQL database
$mysqli = new mysqli($servername, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
// Check if session is active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    $userId = $_SESSION['user_id'] ?? null;
}

function get_user_email($conn, $user_id) {
    $user_id = $conn->real_escape_string($user_id);
    $sql = "SELECT email FROM created_account WHERE id = '$user_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['email'];
    }
    return null;
}
$userEmail = get_user_email($mysqli, $userId); // Get user email
?>




<!DOCTYPE html>
<html>
<head>
    <title>Profile Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }
        .container {
            display: flex;
            flex-direction: column;
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
        }
        .platform {
            margin-bottom: 70px;
            background: rgba(255, 255, 255, 0.8);
            color: black;
            text-align: center;
            border-radius: 10px;
            padding: 20px;
        }

        .platform h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .profiles-horizontal {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: -20px;
        }

        .profile {
            width: 250px;
            padding: 10px;
            margin: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background: white;
            color: black;
            text-align: center;
            text-decoration: none;
            transition: transform 0.2s;
            flex: 0 0 auto;
        }
        .profile:hover {
            transform: scale(1.05);
        }

        .profile img {
            margin-bottom: 10px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        .profile h4 {
            margin-top: 10px;
            font-size: 1.2em;
        }

        .profile p {
            margin-bottom: 5px;
            font-size: 0.9em; /* Uniform font size for profile details */
        }

        .profile a {
            text-decoration: none;
            color: #007bff;
        }
        form {
            margin-bottom: 20px;
            text-align: center;
        }
        input[type="text"] {
            padding: 8px;
            font-size: 16px;
        }
        input[type="submit"] {
            padding: 8px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        .filter {
            margin-top: 20px;
            text-align: center;
        }
        .filter label {
            margin-right: 10px;
        }
        .spinner {
            display: none;
            margin: 0 auto;
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>


<h2 style="text-align: center;">Profile Search</h2>

<div class="search-container">
<form method="post" action="">
    <input type="text" name="name" placeholder="Enter a name">
    <input type="submit" value="Search">
</form>

<div class="filter">
    <h2 style="text-align: center;">Filter</h2>
    <label><input type="checkbox" id="filter-github" checked> GitHub</label>
    <label><input type="checkbox" id="filter-reddit" checked> Reddit</label>
    <label><input type="checkbox" id="filter-stackexchange" checked> Stack Exchange</label>
    <label><input type="checkbox" id="filter-twitter" checked> Twitter</label>
    <label><input type="checkbox" id="filter-tiktok" checked> TikTok</label>
    <label><input type="checkbox" id="filter-instagram" checked> Instagram</label>
    <label><input type="checkbox" id="filter-linkedin" checked> LinkedIn</label>
</div>

<div class="container">
    <div class="left">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!empty($_POST["name"])) {
                $name = $_POST["name"];
                $searchDate = date('Y-m-d H:i:s'); // Current date and time

                $platform = ["GitHub", "Reddit", "StackExchange", "Twitter", "TikTok", "Instagram", "LinkedIn"];
                foreach ($platform as $platform) {
                    $stmt = $mysqli->prepare("INSERT INTO social_media_history (user_id, platform, search_term, search_date, email) VALUES (?, ?, ?, ?, ?)");
                    if ($stmt) {
                        $stmt->bind_param("issss", $userId, $platform, $name, $searchDate, $userEmail);
                        $stmt->execute();
                    }
                }

                // Twitter Profiles
                $twitterProfiles = searchTwitterProfiles($name);
                echo "<div class='platform twitter'>";
                echo "<h3>Twitter</h3>";
                echo "<div class='profiles-container'>";
                if ($twitterProfiles['status'] === 'ok' && !empty($twitterProfiles['timeline'])) {
                    echo "<div class='profiles-horizontal'>";
                    foreach ($twitterProfiles['timeline'] as $profile) {
                        echo "<a href='https://twitter.com/" . htmlspecialchars($profile['screen_name']) . "' target='_blank' class='profile'>";
                        echo "<img src='" . htmlspecialchars($profile['avatar']) . "' alt='Profile Picture' width='100'>";
                        echo "<h4>" . htmlspecialchars($profile['name']) . "</h4>";
                        echo "<p>Username: @" . htmlspecialchars($profile['screen_name']) . "</p>";
                        echo "<p>Followers: " . htmlspecialchars($profile['followers_count']) . "</p>";
                        echo "<p>Friends: " . htmlspecialchars($profile['friends_count']) . "</p>";
                        echo "<p>Statuses: " . htmlspecialchars($profile['statuses_count']) . "</p>";
                        echo "<p>Media Count: " . htmlspecialchars($profile['media_count']) . "</p>";
                        echo "<p>Description: " . (isset($profile['description']) ? htmlspecialchars($profile['description']) : 'No bio available') . "</p>";
                        echo "</a>";
                    }
                    echo "</div>";
                } else {
                    echo "No Twitter profiles found.";
                }
                echo "</div>";
                echo "</div>";

                // TikTok Profiles
                $tiktokProfiles = searchTikTokProfiles($name);
                echo "<div class='platform tiktok'>";
                echo "<h3>TikTok</h3>";
                echo "<div class='profiles-container'>";
                if (!empty($tiktokProfiles['data']['user_list'])) {
                    echo "<div class='profiles-horizontal'>";
                    foreach ($tiktokProfiles['data']['user_list'] as $profile) {
                        $user = $profile['user'];
                        $stats = $profile['stats'];
                        echo "<a href='https://www.tiktok.com/@" . htmlspecialchars($user['uniqueId']) . (isset($profile['url']) ? $profile['url'] : '#') . "' target='_blank' class='profile'>";
                        echo "<img src='" . $user['avatarMedium'] . "' alt='" . htmlspecialchars($user['nickname']) . "' width='100'>";
                        echo "<h4>" . htmlspecialchars($user['nickname']) . " (@" . htmlspecialchars($user['uniqueId']) . ")</h4>";
                        echo "<p>Followers: " . number_format($stats['followerCount']) . "</p>";
                        echo "<p>Hearts: " . number_format($stats['heartCount']) . "</p>";
                        echo "<p>Videos: " . $stats['videoCount'] . "</p>";
                        echo "<p>Signature: " . htmlspecialchars($user['signature']) . "</p>";
                        echo "</a>";
                    }
                    echo "</div>";
                } else {
                    echo "No TikTok profiles found.";
                }
                echo "</div>";
                echo "</div>";

                // Instagram Profiles
                echo "<div class='platform instagram'>";
                echo "<h3>Instagram</h3>";
                echo "<div class='profiles-container'>";
                if (!empty($_POST["name"])) {
                    $search_query = urlencode($_POST['name']);
                    $instagramProfiles = fetchInstagramProfiles($search_query);
                    if (!empty($instagramProfiles)) {
                        echo "<div class='profiles-horizontal'>";
                        foreach ($instagramProfiles as $profile) {
                            $profilePicUrl = htmlspecialchars($profile['profile_pic_url']);
                            $username = htmlspecialchars($profile['username']);
                            $fullName = htmlspecialchars($profile['full_name']);
                            $isPrivate = $profile['is_private'] ? 'Yes' : 'No';
                            $isVerified = $profile['is_verified'] ? 'Yes' : 'No';

                            echo "<a href='https://www.instagram.com/$username' target='_blank' class='profile'>";
                            echo "<img src='$profilePicUrl' alt='Profile Pic' class='profile-pic' width='100' onerror='console.log(\"Error loading image: $profilePicUrl\")'>";
                            echo "<h4>$username</h4>";
                            echo "<p>Full Name: $fullName</p>";
                            echo "<p>Private: $isPrivate</p>";
                            echo "<p>Verified: $isVerified</p>";
                            echo "</a>";
                        }
                        echo "</div>";
                    } else {
                        echo "<p>No Instagram profiles found.</p>";
                    }
                    echo "</div>";
                } else {
                    echo "<p>Please enter a name to search for Instagram profiles.</p>";
                }
                echo "</div>";

                // Reddit Profiles
                $redditProfiles = searchRedditProfiles($name);
                echo "<div class='platform reddit'>";
                echo "<h3>Reddit</h3>";
                echo "<div class='profiles-container'>";
                if (!empty($redditProfiles)) {
                    echo "<div class='profiles-horizontal'>";
                    foreach ($redditProfiles as $profile) {
                        echo "<a href='https://www.reddit.com/user/" . htmlspecialchars($profile['name']) . "' target='_blank' class='profile'>";
                        if (isset($profile['icon_img'])) {
                            echo "<img src='" . $profile['icon_img'] . "' alt='Profile Picture' width='100'>";
                        }
                        echo "<h4>" . htmlspecialchars($profile['name']) . "</h4>";
                        echo "<p>Karma: " . $profile['total_karma'] . "</p>";
                        echo "<p>Created: " . date('Y-m-d H:i:s', $profile['created_utc']) . "</p>";
                        echo "<p>Title: " . (isset($profile['subreddit']['title']) ? htmlspecialchars($profile['subreddit']['title']) : 'No title available') . "</p>";
                        echo "</a>";
                    }
                    echo "</div>";
                } else {
                    echo "No Reddit profiles found.";
                }
                echo "</div>";
                echo "</div>";

                // GitHub Profiles
                $githubProfiles = searchGitHubProfiles($name);
                echo "<div class='platform github'>";
                echo "<h3>GitHub</h3>";
                echo "<div class='profiles-container'>";
                if (!empty($githubProfiles)) {
                    echo "<div class='profiles-horizontal'>";
                    foreach ($githubProfiles as $profile) {
                        echo "<a href='" . $profile['html_url'] . "' target='_blank' class='profile'>";
                        echo "<img src='" . $profile['avatar_url'] . "' alt='Profile Picture' width='100'>";
                        echo "<h4>" . $profile['login'] . "</h4>";
                        echo "<p>Name: " . (isset($profile['name']) ? $profile['name'] : 'No name available') . "</p>";
                        echo "<p>Bio: " . (isset($profile['bio']) ? $profile['bio'] : 'No bio available') . "</p>";
                        echo "<p>Location: " . (isset($profile['location']) ? $profile['location'] : 'No location available') . "</p>";
                        echo "<p>Public Repos: " . (isset($profile['public_repos']) ? $profile['public_repos'] : 'No data available') . "</p>";
                        echo "<p>Followers: " . (isset($profile['followers']) ? $profile['followers'] : 'No data available') . "</p>";
                        echo "<p>Following: " . (isset($profile['following']) ? $profile['following'] : 'No data available') . "</p>";
                        echo "</a>";
                    }
                    echo "</div>";
                } else {
                    echo "<p>No GitHub profiles found.</p>";
                }
                echo "</div>";
                echo "</div>";

                // Stack Exchange Profiles
                $stackExchangeProfiles = searchStackExchangeProfiles($name);
                echo "<div class='platform stackexchange'>";
                echo "<h3>Stack Exchange</h3>";
                echo "<div class='profiles-container'>";
                if (!empty($stackExchangeProfiles)) {
                    echo "<div class='profiles-horizontal'>";
                    foreach ($stackExchangeProfiles as $profile) {
                        echo "<a href='" . $profile['link'] . "' target='_blank' class='profile'>";
                        echo "<img src='" . $profile['profile_image'] . "' alt='Profile Picture' width='100'>";
                        echo "<h4>" . htmlspecialchars($profile['display_name']) . "</h4>";
                        echo "<p>Reputation: " . $profile['reputation'] . "</p>";
                        echo "<p>Location: " . (isset($profile['location']) ? htmlspecialchars($profile['location']) : 'No location available') . "</p>";
                        echo "<p>Answers: " . (isset($profile['answer_count']) ? $profile['answer_count'] : 'No data available') . "</p>";
                        echo "<p>Questions: " . (isset($profile['question_count']) ? $profile['question_count'] : 'No data available') . "</p>";
                        echo "</a>";
                    }
                    echo "</div>";
                } else {
                    echo "No Stack Exchange profiles found.";
                }
                echo "</div>";
                echo "</div>";

                // LinkedIn Profiles
                $linkedInProfiles = searchLinkedInProfiles($name);
                echo "<div class='platform LinkedIn'>";
                echo "<h3>LinkedIn</h3>";
                echo "<div class='profiles-container'>";
                if (!empty($linkedInProfiles) && is_array($linkedInProfiles)) {
                    echo "<div class='profiles-horizontal'>";
                    foreach ($linkedInProfiles as $profile) {
                        if (isset($profile['profileURL'], $profile['name'])) {
                            echo "<a href='" . $profile['profileURL'] . "' target='_blank' class='profile'>";
                            if (isset($profile['profileImage'])) {
                                echo "<img src='" . $profile['profileImage'] . "' alt='Profile Picture' width='100'>";
                            }
                            echo "<h4>" . htmlspecialchars($profile['name']) . "</h4>";
                            if (isset($profile['headline'])) {
                                echo "<p>Headline: " . htmlspecialchars($profile['headline']) . "</p>";
                            }
                            if (isset($profile['location'])) {
                                echo "<p>Location: " . htmlspecialchars($profile['location']) . "</p>";
                            }
                            echo "</a>";
                        }
                    }
                    echo "</div>";
                } else {
                    echo "No LinkedIn profiles found.";
                }
                echo "</div>";
                echo "</div>";
            }
        }

        function searchGitHubProfiles($name) {
            $url = "https://api.github.com/search/users?q=" . urlencode($name);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            if (isset($data['items'])) {
                $profiles = array_map(function($item) {
                    $userDetails = getGitHubUserDetails($item['login']);
                    return [
                        'login' => $item['login'],
                        'html_url' => $item['html_url'],
                        'avatar_url' => $item['avatar_url'],
                        'name' => $userDetails['name'] ?? 'No name available',
                        'bio' => $userDetails['bio'] ?? 'No bio available',
                        'location' => $userDetails['location'] ?? 'No location available',
                        'public_repos' => $userDetails['public_repos'] ?? 'No data available',
                        'followers' => $userDetails['followers'] ?? 'No data available',
                        'following' => $userDetails['following'] ?? 'No data available'
                    ];
                }, $data['items']);

                return $profiles;
            } else {
                return [];
            }
        }

        function getGitHubUserDetails($username) {
            $url = "https://api.github.com/users/" . urlencode($username);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

            return json_decode($response, true);
        }

        function searchRedditProfiles($name) {
            $username = str_replace(" ", "", $name); // Remove spaces for Reddit
            $url = "https://www.reddit.com/user/" . urlencode($username) . "/about.json";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            if (isset($data['data'])) {
                return [$data['data']];
            } else {
                return [];
            }
        }

        function searchStackExchangeProfiles($name) {
            $url = "https://api.stackexchange.com/2.3/users?order=desc&sort=reputation&inname=" . urlencode($name) . "&site=stackoverflow";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            if (isset($data['items'])) {
                return array_map(function($item) {
                    return [
                        'display_name' => $item['display_name'],
                        'link' => $item['link'],
                        'profile_image' => $item['profile_image'],
                        'reputation' => $item['reputation'],
                        'location' => $item['location'] ?? 'No location available',
                        'answer_count' => $item['answer_count'] ?? 'No data available',
                        'question_count' => $item['question_count'] ?? 'No data available'
                    ];
                }, $data['items']);
            } else {
                return [];
            }
        }
        function searchTwitterProfiles($name) {
            $curl = curl_init();
            
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://twitter-api45.p.rapidapi.com/search.php?query=" . urlencode($name) . "&search_type=People",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "x-rapidapi-host: twitter-api45.p.rapidapi.com",
                    "x-rapidapi-key: 799a0ae3fbmshb539595d13d9040p1b8783jsnb4dc12a5b606"
                ],
            ]);
            
            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return [];
            } else {
                return json_decode($response, true);
            }
        }

        function fetchInstagramProfiles($search_query) {
            $curl = curl_init();
        
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://instagram-scraper-api2.p.rapidapi.com/v1/search_users?search_query=$search_query",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "x-rapidapi-host: instagram-scraper-api2.p.rapidapi.com",
                    "x-rapidapi-key: 799a0ae3fbmshb539595d13d9040p1b8783jsnb4dc12a5b606"
                ],
            ]);
        
            $response = curl_exec($curl);
            $err = curl_error($curl);
        
            curl_close($curl);
        
            if ($err) {
                echo "<p style='color: red;'>cURL Error #:" . $err . "</p>";
                return []; // Return an empty array on error
            } else {
                $data = json_decode($response, true);
        
                if (isset($data['data']['items']) && !empty($data['data']['items'])) {
                    return $data['data']['items']; // Return Instagram profiles if data is available
                } else {
                    echo "<p>No Instagram profiles found.</p>";
                    return []; // Return an empty array if no profiles are found
                }
            }
        }

        function searchTikTokProfiles($name) {
            $url = "https://tiktok-scraper7.p.rapidapi.com/user/search?keywords=" . urlencode($name) . "&count=30&cursor=0";

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "x-rapidapi-host: tiktok-scraper7.p.rapidapi.com",
                    "x-rapidapi-key: 799a0ae3fbmshb539595d13d9040p1b8783jsnb4dc12a5b606"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return [];
            } else {
                return json_decode($response, true);
            }
        }
        function searchLinkedInProfiles($name) {
            $encodedName = urlencode($name); // URL-encode the name parameter
            $curl = curl_init();
        
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://linkedin-api8.p.rapidapi.com/search-people?keywords=$encodedName&start=0",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "x-rapidapi-host: linkedin-api8.p.rapidapi.com",
                    "x-rapidapi-key: 799a0ae3fbmshb539595d13d9040p1b8783jsnb4dc12a5b606"
                ],
            ]);
        
            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
                return [];
            } else {
                $decodedResponse = json_decode($response, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    echo "JSON Decode Error: " . json_last_error_msg();
                    return [];
                }
                return $decodedResponse['data']['items'] ?? [];
            }
        }
        
        ?>
        </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const githubCheckbox = document.getElementById('filter-github');
        const redditCheckbox = document.getElementById('filter-reddit');
        const stackexchangeCheckbox = document.getElementById('filter-stackexchange');
        const twitterCheckbox = document.getElementById('filter-twitter');
        const tiktokCheckbox = document.getElementById('filter-tiktok');
        const instagramCheckbox = document.getElementById('filter-instagram');
        const linkedinCheckbox = document.getElementById('filter-linkedin');

        githubCheckbox.addEventListener('change', function() {
            togglePlatformDisplay('github', githubCheckbox.checked);
        });
        redditCheckbox.addEventListener('change', function() {
            togglePlatformDisplay('reddit', redditCheckbox.checked);
        });
        stackexchangeCheckbox.addEventListener('change', function() {
            togglePlatformDisplay('stackexchange', stackexchangeCheckbox.checked);
        });
        twitterCheckbox.addEventListener('change', function() {
        togglePlatformDisplay('twitter', twitterCheckbox.checked);
        });
        tiktokCheckbox.addEventListener('change', function() {
            togglePlatformDisplay('tiktok', tiktokCheckbox.checked);
        });
        instagramCheckbox.addEventListener('change', function() {
            togglePlatformDisplay('instagram', instagramCheckbox.checked);
        });
        linkedinCheckbox.addEventListener('change', function() {
            togglePlatformDisplay('linkedin', linkedinCheckbox.checked);
        });

        function togglePlatformDisplay(platformClass, display) {
            const elements = document.getElementsByClassName(platformClass);
            for (let i = 0; i < elements.length; i++) {
                elements[i].style.display = display ? 'block' : 'none';
            }
        }
    });
</script>

</body>
</html>
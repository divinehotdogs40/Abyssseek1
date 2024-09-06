<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            display: flex;
            color: #fff; 
            height: 100vh; 
        }

        .top-bar {
            background-color: white;
            height: 80px; /* Adjust height as needed */
        }

        header {
            background-color: #151b3d;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        /* Adjust sidebar width to be responsive */
        .sidebar {
            width: 20%; 
            min-width: 200px; 
            max-width: 300px; 
            background-color: #287bff;
            color: #fff;

            padding-left: 20px;

            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar h1 {
            margin-bottom: 30px;
            text-align: center;
            color: white;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin-top: 8px;
            margin-bottom: 8px;
            padding-top: 2px;
            padding-bottom: 2px;
            padding-left: 10px;

            border-bottom: 1px solid white; /* Sidebar border color */
        }

        .sidebar ul li:last-child {
            border-bottom: none;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px;
        }

        .sidebar ul li a:hover .text {
            color: black; /* Change text color on hover */
        }

        .sidebar ul li a:hover {
            background-color: white; /* Sidebar hover background color */
        }

        /* Add style for active link */
        .sidebar ul li.active a {
            background-color: white;
        }

        .sidebar ul li.active .text{
            color: #287bff;
        }


        .logout-btn {
            padding: 10px;
            background-color: white; 
            color: #287bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: auto; 

        }


        .content {
            flex: 1;
 
            background-color: white;
            position: relative;
            overflow: auto; 
        }
        .iframe-container {
            position: relative;
            overflow: hidden;
            padding-top: calc(123% / (16 / 9)); 
        }

        .iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        .sidebar ul li a ion-icon {
    font-size: 1.5rem; /* Adjust the font size as needed */
    margin-right: 10px; /* Optional: Add some space between icon and text */
}

.logout-btn  ion-icon {
    font-size: 1.7rem; /* Adjust the font size as needed */
    margin-right: 10px; /* Optional: Add some space between icon and text */
}

.logout-btn .text {
            font-size: 1.2rem; /* Adjust the font size as needed */
        }
    </style>
</head>
<body>
<div class="top-bar"></div>
    
<div class="sidebar">
    <h1>Admin Panel</h1>
    <ul>
    <li>
            <a href="#" onclick="loadPage('/abyssseek/admin/admin_dashboard/admin_dashboard.php')">
                
                <span class="text"><ion-icon name="home-outline"></ion-icon>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="loadPage('/abyssseek/admin/request_form/request_form.php')">
                <span class="text"><ion-icon name="newspaper-outline"></ion-icon>Account Request</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="loadPage('/abyssseek/admin/create_user/index.php')">
                <span class="text"><ion-icon name="people-outline"></ion-icon>Abyssseek User</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="loadPage('/abyssseek/admin/user_status/user_status.php')">
                <span class="text"><ion-icon name="reload-circle-outline"></ion-icon>User Status</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="loadPage('/abyssseek/admin/user_monitoring/user_table.php')">
                <span class="text"><ion-icon name="eye-outline"></ion-icon>User Monitoring</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="loadPage('/abyssseek/admin/admin_account/index.php')">
                <span class="text"><ion-icon name="person-circle-outline"></ion-icon>Admin Account</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="loadPage('/abyssseek/admin/messages/user_message.php')">
              
                <span class="text"><ion-icon name="chatbubbles-outline"></ion-icon>Help Inquiry</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="loadPage('')">
                <span class="text"><ion-icon name="create-outline"></ion-icon>Modify Homepage</span>
            </a>
        </li>
        
    </ul>
    <button class="logout-btn" onclick="logout()">
        <span class="text"><ion-icon name="log-out-outline"></ion-icon>Log out</span>
    </button> 
</div>
    <div class="content">
        <!-- Content area -->
        <div class="iframe-container" id="iframeContainer">
            <!-- Initial iframe content -->
            <iframe src="/abyssseek/admin/admin_dashboard/admin_dashboard.php"></iframe> <!-- Load Abyssseek User page by default -->
        </div>
    </div>

    <script>
        function loadPage(url) {
            var iframeContainer = document.getElementById('iframeContainer');
            iframeContainer.innerHTML = '<iframe src="' + url + '"></iframe>';

            // Remove active class from all links
            var links = document.querySelectorAll('.sidebar ul li');
            links.forEach(function (el) {
                el.classList.remove('active');
            });

            // Add active class to the clicked link
            event.target.closest('li').classList.add('active');
        }

        // Function to handle logout
        function logout() {
            // Redirect to index.php
            window.location.href = '/abyssseek/index.php';
        }
    </script>
</body>
</html>
 
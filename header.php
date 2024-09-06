    <?php
    include_once "includes/init.php";
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Abyssseek</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">
        <script src="assets/js/jquery-1.10.2.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="assets/js/custom.js"></script>
        <style>
            body {
                color: #ffff; 
                font-family: 'Courier New', Courier, monospace; 
                margin-bottom: 200px;   
                background: radial-gradient(circle, #3b8898 5%, #284a6f 25%, #151b3d 50%, #0a0a24 90%, #000000 100%);
                background-size: contain;
                font-size: 18px;
                height: 100%;   
    
            }   

            .navbar {
                background-color: rgba(0, 0, 0, 1);
                color: #45acab; /* Green text color */
            }

            .navbar-nav li a, .navbar-brand {
                color: #45acab !important; /* Green text color */
            }

            .navbar-nav li a:hover {
                color: gray !important; /* Green text color on hover */
                text-decoration: none !important; /* Remove underline on hover */
            }

            .navbar-brand:hover {
                color: #45acab !important; /* Green text color on hover */
                text-decoration: none !important; /* Remove underline on hover */
            } 
            

            .input-style {
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            color: #555;
            outline: none;
            width: 500px;
            }

            .input-style:focus {
            border-color: #45acab;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }
            
            .toggle-switch {
            position: relative;
            width: 100px;
            height: 50px;
            --light: #d8dbe0;
            --dark: #28292c;
            --link: rgb(27, 129, 112);
            --link-hover: rgb(24, 94, 82);
            }

            .switch-label {
            position: absolute;
            width: 100%;
            height: 50px;
            background-color: var(--dark);
            border-radius: 25px;
            cursor: pointer;
            border: 3px solid var(--dark);
            }

            .logo-container {
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .footer {
                background-color: rgba(0, 0, 0, 1);
                padding: 10px;
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
                color: #45acab;
                z-index: 9999; /* Ensure the footer stays on top */
                font-size: 14px;
            }

            .typing-box {
                border: 2px solid #ccc;
                padding: 20px;
            }

            .typing-box p {
                border-right: 2px solid #000;
                white-space: nowrap;
                overflow: hidden;
                width: 0;
            }

            @keyframes typing {
                from { width: 0 }
                to { width: 100% }
            }


        </style>
    </head>
    <body>
    <div class="container-fluid">
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">ABYSSSEEK</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="guide.php">Guide</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                    <?php if(!logged_in()) : ?>
                        <li><a href="login.php"><i class="bi bi-box-arrow-in-right"></i> Sign In</a></li>
                        <li><a href="requestaccount.php"><i class="bi bi-person-lines-fill"></i> Request Account</a></li>
                    <?php else : ?>
                     
                    <?php endif; ?>
                </ul>
                </div>
            </div>
        </nav>


<?php include_once "header.php"; ?>

<style>
    .custom-box {
        background-color: rgba(255, 255, 255, 0.1); /* Transparent white background */
        border-radius: 10px; /* Rounded corners */
        padding: 20px; /* Padding inside the box */
        margin-bottom: 20px; /* Margin between boxes */
    }
</style>

<div class="container-fluid" style="margin-top: 50px;">
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <div class="custom-box">
                <h1 class="display-4 text-center"><b>Getting Started</b></h1>
                <br><br>
                <p class="lead" id="typing-text1"> 
                    <strong>Logging In</strong><br>
                    <strong>Step 1:</strong> Enter your AFP email and the password you set up during the registration process.<br>
                    <strong>Step 2:</strong> Click the login button to access your dashboard.<br><br>

                    <strong>User Dashboard</strong><br>
                    <b>1. Dashboard</b> - Displays the history of total searches performed by the crawler and scraper.<br>
                    <b>2. Look Up</b> - Tool for finding email addresses and phone numbers.<br>
                    <b>3. Social Media Finder</b> - Searches and retrieves profile information from various social media
                    platforms.<br>
                    <b>4. Web Crawler Tool</b> - Searches for keywords within the content of specified URLs.<br>
                    <b>5. Web Scraping Tool</b> - Extracts data from websites, converting it into a structured format for
                    analysis.<br>
                    <b>6. History</b> - Shows a log of past searches conducted with the tools.<br><br>

                    <strong>User Profile</strong><br>
                    <strong>1. Upload:</strong> Change your profile picture.<br>
                    <strong>2. Edit Profile:</strong> Update your profile details.<br>
                    <strong>3. View Activity:</strong> View the history of your searches.<br>
                    <strong>4. Help:</strong> Contact support for inquiries.<br>
                    <strong>5. Logout:</strong> Sign out of your account.<br><br>
               
                <strong>How to use the tools?</strong><br><br>
                <strong>Web Crawler</strong><br>
                <strong>1. Enter a URL: </strong> Input any URL that you wish to parse.<br>
                <strong>2. Enter Keyword: </strong> Type in the keyword you want to search for within the content of the specified URL.<br>
                <strong>3. Search Content:</strong> Initiate the crawl to search for the keyword in the website content and save the data.<br>
                <strong>4. Extract Data:</strong> Retrieve the relevant data from the website based on the keyword search.<br>
                <strong>5. Export Data: </strong> Save the extracted data in both CSV and PDF formats for easy analysis and reporting.<br><br>

                <strong>Web Scrape</strong><br>
                <strong>1. Install Scrape Tool Extension:</strong>Users can install the extension for the scraping tool on their browser.<br>
                <strong>2. Utilize Search Engine:</strong>Use the built-in search engine within the tool to locate and scrape comprehensive data
                across multiple web pages. This feature helps in gathering structured data from websites, including text, links,
                and images.<br>
                <strong>3. Extract Data:</strong> Retrieve data from the website, ensuring that it includes all relevant information identified
                during the scraping process.<br>
                <strong>4. Export Data:</strong> Allow for the data to be exported in multiple formats, including CSV, PDF, and JSON,
                accommodating various data handling and processing needs.<br><br>

                <strong>Social Media Finder</strong><br>
                <strong>1.	Enter Full Name:</strong>Input the full name of the individual whose social media profiles you wish to find.<br>
                <strong>2.	Navigate Search:</strong>Initiate the search across multiple social media platforms to locate profiles matching the entered name.<br>
                <strong>3.	Review Results:</strong> Examine and verify the search results to find the correct individual. This step may include sorting through potential matches and using additional identifiers (like location or mutual connections) to ensure accuracy.<br>
                <strong>4.	Extract Profiles:</strong>Collect profile details such as user IDs, profile links, bios, and other publicly available information.<br>
                <strong>5.	Export Data:</strong>Export the gathered social media profile information into formats like CSV or JSON for further analysis or reporting.<br><br>

                </p>
            </div>
        </div>
        <div class="col-sm-3"></div>
    </div>
</div>
<div class="container-fluid" style="margin-top: 50px;">
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <div class="custom-box text-center"> <!-- Added 'text-center' class -->
                <h1 class="display-4"><b>Help!!</b></h1> <!-- Removed 'text-center' class -->
                <br>
                <p class="lead" id="typing-text1"> 
                    If you have Further concerns contact us!<br>
                    <strong><b>divinehotdogs40@gmail.com</b></strong><br>
                </p> <!-- Added closing tag for <p> -->
            </div>
        </div>
    </div>
</div>
<?php include_once "footer.php"; ?>

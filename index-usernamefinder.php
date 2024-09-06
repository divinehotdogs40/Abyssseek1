<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Abyssseek Webcrawler</title>
  <div id="backButtonContainer">
    <button type="backButton" id="usernamefinderButton" onclick="back();">Back</button>
  </div>
  <style>
    body {
  resize: none;
  font-family: Arial, sans-serif;
  background-image: url('files/assets/bg.jpg');
  background-size: 100% 100%; /* Stretch to cover the entire background */
  background-position: center; /* Adjust as needed */
  background-repeat: no-repeat;
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh; /* Changed to 100vh for full viewport height */
}

    #container {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      padding: 20px;
      margin-top: 40vh; /* Set margin at the top */
    }

    form {
      position: relative;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width: 500px;
      margin-bottom: 20px;
      max-width: 100%; /* Ensure form doesn't exceed viewport width */
    }

    .input-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    label {
      margin-right: 20px; /* Adjust the margin as needed */
      font-weight: bold;
    }

    #buttonContainer {
      position: fixed;
      top: 10px;
      left: 10px;
      z-index: 9999; /* Ensure it's above other content */
    }

    #backButtonContainer {
      position: fixed;
      top: 10px;
      left: 10px;
      z-index: 9999; /* Ensure it's above other content */
    }

    button {
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    /* Hide the loader by default */
    #loader {
      display: none;
      position: absolute;
      top: 75px;
      left: 175px;
      transform: translate(-50%, -50%);
      z-index: 9999; /* Set a high z-index */
    }

  </style>
</head>
<body>
  <div id="container">
    <form id="searchForm">
      <div class="input-container">
        <label for="usernamefinderField">Username:</label>
        <input type="text" id="usernamefinderField" name="usernamefinderField">
      </div>
      <img id="loader" src="files/usernamefinder/assets/loader.gif" alt="Loader" width="75" height="75">
      <button type="button" id="startButton" onclick="startSearch();">Start Search</button>
    </form>

    <!-- Loader element -->
    

    <div id="socialMediaContainer"></div>
  </div>

  <script>
    function startSearch() {
      var formData = new FormData(document.getElementById("searchForm"));
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "files/usernamefinder/startusernamefinder.php", true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          console.log(xhr.responseText);
          //location.reload(); // Reload the page to reflect the changes
        }
      };
      xhr.send(formData);
    }

    function back() {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "index-usernamefinder.php", true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          location.href = 'index-webcrawler.php';
        }
      };
      xhr.send();
    }

    // Function to update social media links
    function updateSocialMedia() {
      var socialMediaContainer = document.getElementById("socialMediaContainer");
      fetch('files/usernamefinder/src/sites.txt')
        .then(response => response.text())
        .then(text => {
          socialMediaContainer.innerHTML = ""; // Clear previous content
          text.split('\n').forEach(line => {
            var [platform, url] = line.split(' ');
            if (url.trim() !== 'NONE') {
              var container = document.createElement("div");
              container.className = "input-container";
              container.innerHTML = `<label>${platform}:</label><div class="button-container"><a href="${url}" target="_blank"><button>Visit Profile</button></a></div>`;
              socialMediaContainer.appendChild(container);
            } else {
              var container = document.createElement("div");
              container.className = "input-container";
              container.innerHTML = `<label>${platform}:</label>`;
              socialMediaContainer.appendChild(container);
            }
          });
        });
    }

    // Update social media links every 5 seconds
    setInterval(updateSocialMedia, 500);

    // Initial update when the page loads
    updateSocialMedia();

    // Function to check for the existence of the loader trigger file
    function checkLoaderTrigger() {
      var xhr = new XMLHttpRequest();
      xhr.open("HEAD", "files/usernamefinder/assets/loaderTrigger.txt", true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            // Show the loader if the file exists
            document.getElementById("loader").style.display = "block";
          } else {
            // Hide the loader if the file does not exist
            document.getElementById("loader").style.display = "none";
          }
        }
      };
      xhr.send();
    }

    // Check for the loader trigger file every 1 second
    setInterval(checkLoaderTrigger, 1000);
  </script>
</body>
</html>

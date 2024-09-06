<?php
  session_start();

  ini_set('max_execution_time', 9999999999);

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_SESSION["startButton"]) ) {
    session_write_close();
    unset($_SESSION["startButton"]);

    @$url = escapeshellarg($_POST['urlField']);
    @$keyword = escapeshellarg($_POST['keywordField']);
    @$limit = escapeshellarg($_POST['limitField']);
    @$matchType = escapeshellarg($_POST['matchType']);
    @$emailEntry = $_SESSION['EmailEntry'];
    
    $command = "python C:/xampp/htdocs/abyssseek/files/webcrawler/webcrawler.py $url $keyword $limit $matchType $emailEntry";
    
    exec($command, $output, $return_value);

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
    
  }
?>



<!DOCTYPE html>
  <html lang="en">
<head>
  <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>Abyssseek Webcrawler</title>
  
  <link rel="stylesheet" type="text/css" href="webcrawler.css">


  <div id="constantLogo">

  <img src="files/assets/logo-pic2.png" class="logo-pic2" alt="loading" width="500" height="500">
  <img src="files/assets/logo-pic1.png" class="logo-pic1" alt="loading" width="500" height="500" id="scanning">
  <img src="files/assets/logo-pic3.png" class="logo-pic3" alt="loading" width="500" height="500" id="scanning1">

  </div>

  </head>
  <body>


  <button type="button" id="stopButton" class="controlbuttons" style="display: none; font-family: 'buttons', sans-serif; width: 100px; position: fixed; top: 303px; left: 705px; font-family: main3, sans-serif; z-index: 100;" onclick="stopCrawler(); hideSeekButton();">STOP</button>


<div id="container">
  <form id="searchForm" method="post">

    <label for="urlField" style="font-family: main2; font-weight: bold; color: black;">URL:</label>
    <input type="text" id="urlField" name="urlField" style="font-family: main3, sans-serif;" placeholder="www.example.com" required><br><br>
    
    <label for="keywordField" style="font-family: main2; font-weight: bold; color: black;"">Keyword:</label>
    <input type="text" id="keywordField" name="keywordField" style="font-family: main3, sans-serif;" placeholder="word(s)" required><br><br>

    <label type="limitText" for="limitField" style="left: 830px; font-family: main2; font-weight: bold; color: black;"">Limit:</label>
    <input type="limit" id="limitField" onkeydown="numbersOnly(event)" name="limitField" style="left: 830px; font-family: main3, sans-serif;" placeholder="# of links" required><br><br>
    
    <label for="matchType" style="position: fixed; top: 284px; left: 980px; font-family: main2; font-weight: bold; color: black;"">Search Mode:</label>
    <select id="matchType" name="matchType" class="matchType" style="font-family: main3, sans-serif; width: 190px; height: 30px;">
    <option value="Partial" title="If the keyword is &quot;sss&quot; and there is &quot;Abyssseek&quot; on the page, it will be marked as &quot;found&quot;.">Partial Keyword Match</option>
    <option value="Exact" title="If the keyword is &quot;sss&quot; and there is &quot;Abyssseek&quot; on the page, it will be marked as &quot;not found&quot;.">Exact Keyword Match</option>
    </select>

    <button type="submit" id="startButton" class="controlbuttons" style="display: none; font-family: 'buttons', sans-serif; width: 100px; position: fixed; top: 303px; left: 705px; font-family: main3, sans-serif;">SEEK</button>

  </form>
</div>

  

  <form action="download-linksWithKeywords.php" method="post">
        <button type="submit" name="download_csv" style="font-family: main3, sans-serif; font-size: 10px; position: fixed; top: 60px; left: 805px; background: green;">Download links with keywords</button>
    </form>

  <form action="download-detectedLinks.php" method="post">
        <button type="submit" name="download_csv" style="font-family: main3, sans-serif; font-size: 10px; position: fixed; top: 60px; left: 1020px; background: green;">Download all detected links</button>
    </form>


    
<label for="notfoundcount-text" id="notfoundcount-text">Links without keyword: </label>
<label for="foundcount-text" id="foundcount-text">Links with keyword: </label>
<label style="position: fixed; top: 405px; left: 50px; font-family: main3, sans-serif; font-size: 22px; color: black;">Search History</Label>
<label style="position: fixed; top: 405px; left: 695px; font-family: main3, sans-serif; font-size: 22px; color: black;">Links with keyword</Label>
<label style="position: fixed; top: 405px; left: 1267px; font-family: main3, sans-serif; font-size: 22px; color: black;">Crawler Response</Label>

<div id="searchHistory"></div>
<div id="foundLinks"></div> 
<div id="foundcount-container"></div>
<div id="notfoundcount-container"></div>
<div id="crawler-response"></div>

  <div class="dbs">
        <a href="index-databasesearch.php">

        <img id="loadingDBS" src="files/assets/AbyssseekDatabaseSearch1.png" alt="loading..." style="position: fixed; top: 150px; left: 1375px; width: 339px; height: 141px;" onmouseover="convertToBlack(true)" onmouseout="convertToBlack(false)">

        </a>
      </div>

  <script>

$webcrawlerStatus = "Null";

convertToBlack(false)

function convertToBlack(toBlack) {
            if (toBlack) {
                document.getElementById('loadingDBS').style.filter = 'brightness(0%)';  // Change to 0% to revert to original
            } else {
                document.getElementById('loadingDBS').style.filter = 'brightness(100%)';
            }
        }



function hideSeekButton(){
  image = document.querySelector(".logo-pic1");
  image.classList.add("wiggle");
  image = document.querySelector(".logo-pic3");
  image.classList.add("shining-star");
  document.getElementById('startButton').style.display = 'none';
  document.getElementById('stopButton').style.display = 'block';
  interactive = True;
}

function historyFormat(history) {
  if (history == ""){
    return "";
  }
    var lines = history.split("\n");
    var formattedHistory = "";
    lines.forEach(function(line, index) {
        var parts = line.split(": ");
        if (parts.length === 2) {
            var label = parts[0].trim();
            var value = parts[1].trim();
            formattedHistory += "<span style='font-family: main1; font-weight: bold; font-size: 17px; color: black;'>" + label + ": </span>";
            formattedHistory += "<span style='font-family: main1; font-weight: bold; font-size: 15px; color: #007bff;'>" + value + "</span><br>";
        }

        if (line.includes("Search Mode") && line.endsWith("Match")) {
            formattedHistory += "<hr>";
        }
        
    });
    
    return formattedHistory;
    
}

  function linksFormat(links) {
    var lines = links.split("\n");
    var formattedLinks = "";
    lines.forEach(function(line, index) {
      if (line != ""){
          formattedLine = line.split("//");
          formattedLine = formattedLine[1].trim();
          formattedLinks += "<a href=" + line + " target=" + "_blank" + ";><span style='font-weight: bold; font-family: main1; font-size: 15px;'>" + formattedLine + "</span><hr>";
        }
        });
        return formattedLinks;
        }

  document.getElementById('searchForm').addEventListener('submit', function() {
        hideSeekButton();
        localStorage.setItem('urlField', document.getElementById('urlField').value);
        localStorage.setItem('keywordField', document.getElementById('keywordField').value);
        localStorage.setItem('limitField', document.getElementById('limitField').value);
        localStorage.setItem('matchType', document.getElementById('matchType').value);
    });


  window.onload = function() {
        document.getElementById('urlField').value = localStorage.getItem('urlField') || '';
        document.getElementById('keywordField').value = localStorage.getItem('keywordField') || '';
        document.getElementById('limitField').value = localStorage.getItem('limitField') || '';
        document.getElementById('matchType').value = localStorage.getItem('matchType') || 'Partial';
    };
  
function fetchDataAndUpdateDivs() {
      fetch(`index-webcrawler-status.php`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error);
            } else {
                // Access individual values from the JSON response
                crawlerStatus = data.crawlerstatus;
                foundCount = data.foundcount;
                notFoundCount = data.notfoundcount;
                foundLinks = data.found_links;
                searchHistory = data.search_history;
                Viewing_URL = data.Viewing_URL;
                Viewing_Response = data.Viewing_Response;
                
                $webcrawlerStatus = crawlerStatus;
                document.getElementById('foundcount-container').innerHTML = foundCount;
                document.getElementById('notfoundcount-container').innerHTML = notFoundCount;

                foundLinks = linksFormat(foundLinks);
                document.getElementById('foundLinks').innerHTML = foundLinks;

                searchHistory = historyFormat(searchHistory);
                document.getElementById('searchHistory').innerHTML = searchHistory;

                document.getElementById('crawler-response').innerHTML = "<span style='color: #FFAA00;'>AWC:\\abyssseek\\crawler></span> " + Viewing_URL + "<br><br>" + Viewing_Response;

            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
                
        }

function webcrawlercontrols() {
  if ($webcrawlerStatus == "RUNNING") {
      image = document.querySelector(".logo-pic1");
      image.classList.add("wiggle");
      image = document.querySelector(".logo-pic3");
      image.classList.add("shining-star");
      document.getElementById('startButton').style.display = 'none';
      document.getElementById('stopButton').style.display = 'block';
      setTimeout(webcrawlercontrols, 2000);
      
  } else if ($webcrawlerStatus == "STOPPED" || "Available!") {
      image = document.querySelector(".logo-pic1");
      image.classList.remove("wiggle");
      image = document.querySelector(".logo-pic3");
      image.classList.remove("shining-star");
      document.getElementById('startButton').style.display = 'block';
      document.getElementById('stopButton').style.display = 'none';
      
  }
}

        setInterval(fetchDataAndUpdateDivs, 500);
        setTimeout(webcrawlercontrols, 2000);
        
        

function stopCrawler() {
    fetch('stopcrawler.php', {
        method: 'POST',
    })
    .then(response => {
        console.log('Request successful');
    })
    .catch(error => {
        console.error('Error:', error);
    });
    location.reload();
}

function numbersOnly(event) {
            // Get the key code of the pressed key
            var keyCode = event.which || event.keyCode;

            // Allow only numeric digits (0-9) and special keys like Backspace, Delete, Arrow keys, etc.
            if (!(keyCode >= 48 && keyCode <= 57) && // 0-9
                !(keyCode >= 96 && keyCode <= 105) && // Numpad 0-9
                !(keyCode == 8 || keyCode == 9 || keyCode == 13 || keyCode == 27 || // Backspace, Tab, Enter, Escape
                  keyCode == 37 || keyCode == 39 || keyCode == 46)) { // Arrow keys, Delete
                event.preventDefault();
            }
        }

  </script>
  
  </body>
  </html>

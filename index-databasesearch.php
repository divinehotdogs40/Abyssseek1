<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "abyssseek";
  
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  
$sql = "SELECT url, content FROM pages";
$result = $conn->query($sql);

$domains = [];

?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="webcrawler.css">   
    <title>Abyssseek Database Search</title>
    <style>
        .container {
        position: fixed;
        display: fixed;
        width: 1760px;
        height: 1000px;
        padding: 10px;
        margin-bottom: 10px;
        margin-top: 10px;
        top: 100px;
        left: 20px;
        
    }

    .resultContainer {
        position: fixed;
        display: fixed;
        border: 1px solid #ddd; /* Optional: Add a border around the container */
        padding: 10px; /* Optional: Add padding to the container */
        max-height: 800px; /* Adjust the height as needed */
        overflow-y: auto; /* Add vertical scroll if content overflows */
        background-color: #a8dadc;
    }

    .urlContainer {

        font-family: main1, sans-serif;
        font-size: 13px;
        margin-bottom: 1px; /* Add space between URLs */
        padding: 1px; /* Optional: Add padding to each item */
        color: black;
    }

    .urlContainer a {
    color: black;
    text-decoration: none;
}

.urlContainer a:hover {
    font-weight: bold;
    color: darkblue;
    
}


/* Works on Firefox */
.resultContainer::-webkit-scrollbar {
    width: 15px;
    background: transparent;
}

/* Works on Chrome, Edge, and Safari */
.resultContainer::-webkit-scrollbar-track {
    background: transparent;
}

.resultContainer::-webkit-scrollbar-thumb {
    background-color: #d62828;
    
}

.resultContainer::-webkit-scrollbar-thumb:hover {
    background-color: #003049;
    
}
    </style> 
</head>
<body>
<img src="files/assets/AbyssseekDatabaseSearch2.png" style="position: fixed; top: 63px; left: 675px; width: 900px; height: 50px;">
<div class="container">
    <textarea style="position: fixed; top: 70px; left: 20px; width: 300px; height: 32px; font-family: main3, sans-serif;" id="searchText" placeholder="Enter keyword" required></textarea>
    <button style="position: fixed; top: 70px; left: 335px; font-family: main3, sans-serif; height: 37px;" onclick="searchKeyword();">SEARCH</button>
    <select style="position: fixed; top: 70px; left: 440px; font-family: main3, sans-serif; height: 38px;" id="domainSelect" onchange="filterByDomain()">
        <option value="">All domains</option>
    </select>
</div>
<div class="resultContainer container">
    <?php
    if ($result->num_rows > 0) {
        $counter = 0;
        while ($row = $result->fetch_assoc()) {
            $url = $row["url"];
            $content = $row["content"];
            $domain = parse_url($url, PHP_URL_HOST);
            if (!in_array($domain, $domains)) {
                array_push($domains, $domain);
            }
    ?>
            <div class="urlContainer" data-domain="<?php echo $domain; ?>" data-content="<?php echo htmlspecialchars($content); ?>">
                <a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a>
            </div>
    <?php
            $counter++;
        }
    } else {
    ?>
        <div>No results found.</div>
    <?php
    }
    ?>
</div>



    <script>
    var urls = document.querySelectorAll('.urlContainer');

    function searchKeyword() {
    var keyword = document.getElementById('searchText').value.trim().toLowerCase();
    if (keyword == false){
        console.log("No keyword entered.")
    }
    else{
    urls.forEach(function(urlContainer) {
        var content = urlContainer.getAttribute('data-content').toLowerCase();
        if (content.includes(keyword)) {
            urlContainer.style.display = 'block';
        } else {
            urlContainer.style.display = 'none';
        }
    });
    updateDomainSelect();
}
}




    function updateDomainSelect() {
        var select = document.getElementById('domainSelect');
        select.innerHTML = '<option value="">All domains</option>';
        var domains = [];
        urls.forEach(function(urlContainer) {
            var domain = urlContainer.getAttribute('data-domain');
            if (urlContainer.style.display !== 'none' && !domains.includes(domain)) {
                domains.push(domain);
                var option = document.createElement('option');
                option.text = domain;
                select.add(option);
            }
        });
    }

    function filterByDomain() {
    var selectedDomain = document.getElementById('domainSelect').value;
    urls.forEach(function(urlContainer) {
        var domain = urlContainer.getAttribute('data-domain');
        var content = urlContainer.getAttribute('data-content').toLowerCase();
        var keyword = document.getElementById('searchText').value.trim().toLowerCase();
        if ((selectedDomain === '' || domain === selectedDomain) && content.includes(keyword)) {
            urlContainer.style.display = 'block';
        } else {
            urlContainer.style.display = 'none';
        }
    });
}

</script>

</body>
</html>

<?php
$conn->close();
?>

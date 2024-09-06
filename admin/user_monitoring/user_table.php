<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-Tbd+NcX6qpqp2kfe9lHY0V8nrOlvjaElyHYv8WobLdR+pKnweKH5pUkcgRSehBL7" crossorigin="anonymous">
    <title>Display Table Data</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box; /* Include padding and border in element's total width and height */
    }

    body {
        font: 15px Arial, Helvetica, sans-serif;
        background-color: #f0f8ff; /* Light blue background */
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        width: 80%;
        margin: 20px auto; /* Center the container and provide top margin */
        background-color: white;
        margin-top: 110px;
        padding: 20px;
        border-radius: 0px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow effect */
    }

    .clock-container {
        position: fixed;
        top: 0px;
        left: 50%;
        transform: translateX(-50%);
        text-align: center;
        padding: 20px;
        z-index: 1;
        background-color: #f0f8ff; /* Match body background for seamless look */
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Shadow for container */
    }

    .clock {
        border: 1px solid #606060;
        color: whitesmoke;
        padding: 20px;
        border-radius: 10px;
        background: linear-gradient(135deg, #87cefa, #4682b4); /* Gradient of light blue */
        font-size: 24px;
        margin-bottom: 10px; /* Space below clock */
    }

    #Date {
        font-size: 16px;
        margin-bottom: 10px;
        color: #4682b4; /* Light blue text color */
    }

    .clock ul {
        list-style: none;
        display: flex;
        font-size: 40px;
        gap: 15px;
    }

    .table-container {
        text-align: center;
        margin-top: 20px; /* Space above table */
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: white; /* White background for table */
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        border-radius: 5px;
    }

    th, td {
        border: 2px solid #dddddd;
        text-align: left;
        padding: 7px;
    }

    th {
        background-color: #f2f2f2;
    }

    td:nth-child(3) {
        color: green;
    }

    td:nth-child(4) {
        color: red;
    }

    .pagination {
        text-align: center;
        margin-top: 20px;
    }

    .pagination a {
        display: inline-block;
        padding: 8px 16px;
        margin: 0 4px;
        color: #333;
        text-decoration: none;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #87cefa; /* Light blue background */
        color: white; /* White text color */
        transition: background-color 0.3s ease;
    }

    .pagination a.active {
        background-color: #007bff; /* Darker blue for active page */
        border-color: #007bff;
    }

    .pagination a:hover {
        background-color: #4682b4; /* Darker blue on hover */
    }

    .export-buttons {
        margin-top: 20px;
        text-align: center; /* Center buttons */
    }

    .export-buttons select {
        padding: 10px;
        font-size: 16px;
        background-color: #4682b4; /* Darker blue background */
        color: white;
        border: none;
        border-radius: 5px;
        margin-right: 10px; /* Space between select and buttons */
    }

    .export-buttons button {
        padding: 10px 20px;
        font-size: 16px;
        background-color: #87cefa; /* Light blue button */
        border: none;
        color: white;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .export-buttons button:hover {
        background-color: #4682b4; /* Darker blue on hover */
    }

    h2 {
        margin-bottom: 20px;
    }

    .back-button {
        position: fixed;
        bottom: 20px; /* Adjusted position */
        right: 20px; /* Adjusted position */
        background-color: #87cefa; /* Light blue button */
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .back-button:hover {
        background-color: #4682b4; /* Darker blue on hover */
    }
    .search-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px; /* Adjust as needed */
    }

    .search-container .search-item {
        display: flex;
        align-items: center;
    }

    .search-container label,
    .search-container input,
    .search-container button {
        margin: 5px;
    }

    .search-container .search-item input {
        flex: 1;
    }

    .download-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
        }

        .download-container button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .download-container button:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>
<div class="container">
    <div class="search-container">
        <div class="search-item">
            <label for="searchEmail">Search by Email:</label>
            <input type="email" id="searchEmail" name="searchEmail">
            <button onclick="searchByEmail()">Search</button>
        </div>
        <div class="search-item">
            <label for="searchDate">Search by Date:</label>
            <input type="date" id="searchDate" name="searchDate" onchange="fetchDataBySelectedDate()">
            <button onclick="searchByDate()">Search</button>
            <button onclick="downloadCSV()">Download CSV</button> <!-- Download button added here -->
        </div>
    </div>

    <div class="table-container">
        <h2>Seeker Login and Logout</h2>
        <table id="data-table">
            <!-- Table content will be dynamically updated here -->
        </table>
        <!-- Pagination links -->
        <div class="pagination" id="pagination-links">
            <!-- Pagination links will be dynamically updated here -->
        </div>
    </div>
</div>


<!-- Include jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    var currentPage = 1; // Initialize current page
    var recordsPerPage = 10; // Number of records to show per page

    // Function to fetch and display table data along with pagination links
    function fetchTableData(page) {
        $.ajax({
            url: 'fetch_table_data.php', // Replace with your server-side script URL
            type: 'GET',
            data: { page: page, recordsPerPage: recordsPerPage }, // Send current page and records per page to server
            success: function(data) {
                $('#data-table').html(data); // Update the table content
                fetchPaginationLinks(page); // Fetch pagination links for the current page
            },
            error: function(xhr, status, error) {
                console.error("Error fetching table data: " + error);
            }
        });
    }

    // Function to fetch pagination links based on current page
    function fetchPaginationLinks(current) {
        $.ajax({
            url: 'fetch_pagination_links.php', // Replace with your server-side script URL
            type: 'GET',
            data: { current: current, recordsPerPage: recordsPerPage }, // Send current page and records per page to server
            success: function(data) {
                $('#pagination-links').html(data); // Update pagination links
            },
            error: function(xhr, status, error) {
                console.error("Error fetching pagination links: " + error);
            }
        });
    }

    // Event listener for pagination link clicks
    $(document).on('click', '.pagination a', function(event){
        event.preventDefault();
        var page = $(this).data('page'); // Get the page number from data-page attribute
        fetchTableData(page); // Fetch data for the selected page
    });

    // Initial data fetching and display (for the first page)
    $(document).ready(function() {
        fetchTableData(currentPage); // Fetch data for the initial page
    });

    // Function to search login records by date
    function searchByDate() {
        var searchDate = document.getElementById('searchDate').value;
        // You can add validation for the searchDate here if needed

        $.ajax({
            url: 'search_login_by_date.php', // Replace with your server-side script URL
            type: 'GET',
            data: { date: searchDate },
            success: function(data) {
                $('#data-table').html(data); // Update the table content with search results
                $('#pagination-links').empty(); // Clear pagination links if any
            },
            error: function(xhr, status, error) {
                console.error("Error searching by date: " + error);
            }
        });
    }

    // Function to search login records by email
    function searchByEmail() {
        var searchEmail = document.getElementById('searchEmail').value;
        
        // Check if searchEmail is not empty
        if (searchEmail.trim() !== '') {
            $.ajax({
                url: 'search_by_email.php',
                type: 'GET',
                data: { email: searchEmail },
                success: function(data) {
                    $('#data-table').html(data); // Update the table content with search results
                    $('#pagination-links').empty(); // Clear pagination links if any
                },
                error: function(xhr, status, error) {
                    console.error("Error searching by email: " + error);
                }
            });
        } else {
            alert('Please enter an email to search.'); // Optional: Add validation message
        }
    }

    // Function to download CSV records by date
    function downloadCSV() {
        var searchDate = document.getElementById('searchDate').value;
        
        if (searchDate.trim() !== '') {
            window.location.href = 'download_csv.php?date=' + searchDate; // Redirect to download CSV
        } else {
            alert('Please select a date to download records.'); // Optional: Add validation message
        }
    }
</script>
</body>
</html>

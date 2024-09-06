<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abyssseek Create User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <style>
        body {
            background: white;
            /* background: linear-gradient(45deg, #FF0080, #FF6B9E); */
            height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .container {
            background-color: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        }

        .search-container {
            float: right;
            margin-bottom: 10px;
            position: relative;
            background: #287bff;
            border-radius: 20px;
            padding: 5px 10px;
            display: flex;
            align-items: center;
        }

        .search-container input[type=text] {
            padding: 10px 10px;
            margin-left: 10px;
            font-size: 16px;
            border: none;
            background-color: transparent;
            color: white;
            width: 200px;
            box-shadow: none;
        }

        .search-container input[type=text]:focus {
            outline: none;
            box-shadow: none;
        }

        .search-container input[type="text"]::placeholder {
            color: white;
        }

        .search-container button {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            padding: 5px 15px;
            border: none;
            background-color: #287bff;
            color: #fff;
            border-radius: 20px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #287bff;
        }

        /* Add vertical lines to table */
        th,
        td {
            border-left: 1px solid #dee2e6;
        }

        th:first-child,
        td:first-child {
            border-left: none;
        }
    </style>
</head>
<body>

<div class="container mt-5 text-center">
    <h2 class="mb-4" style="background: black; -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Abyssseek Create User</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="search-container">
            <ion-icon name="search-outline"></ion-icon>
            <input type="text" id="myInput" onkeyup="searchTable()" placeholder="Search for Email.." class="form-control">
        </div>
        <div>
            <a class="btn btn-primary me-2" href="/abyssseek/admin/create_user/create.php" role="button"><i class="fa-duotone fa-plus"></i> Add New</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped" id="myTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Position</th>
                    <th>Date_Time</th>
                    <th>Phone Number</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "abyssseek";

                $connection = new mysqli($servername, $username, $password, $database);

                if ($connection->connect_error) {
                    die("Connection failed: " . $connection->connect_error);
                }

                $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
                $limit = 10;
                $offset = ($currentPage - 1) * $limit;

                $currentTime = date('H:i:s');
                $currentDate = date('Y-m-d');
                $sql = "SELECT * FROM created_account ORDER BY ID ASC LIMIT $limit OFFSET $offset";
                $result = $connection->query($sql);

                if (!$result) {
                    die("Invalid query: " . $connection->error);
                }

                $idCounter = $offset + 1; // Initialize ID counter based on offset
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>{$idCounter}</td>
                        <td>{$row['Email']}</td>
                        <td>{$row['First_Name']}</td>
                        <td>{$row['Last_Name']}</td>
                        <td>{$row['Position']}</td>
                        <td>{$row['Date_Time']}</td>
                        <td>{$row['Phone_Number']}</td>
                        <td>
                            <a class='btn btn-primary btn-sm me-1' href='/abyssseek/admin/create_user/edit.php?ID={$row['ID']}'><i class='fa-solid fa-pen-to-square'></i>Edit</a>
                            <a class='btn btn-danger btn-sm' href='/abyssseek/admin/create_user/delete.php?ID={$row['ID']}'><i class='fa-solid fa-trash'></i> Delete</a>
                        </td>
                    </tr>
                    ";
                    $idCounter++; // Increment ID counter
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php
            $sql = "SELECT COUNT(*) AS total FROM created_account";
            $result = $connection->query($sql);
            $data = $result->fetch_assoc();
            $totalPages = ceil($data['total'] / $limit);

            for ($i = 1; $i <= $totalPages; $i++) {
                echo "<li class='page-item'><a class='page-link' href='?page=$i'>$i</a></li>";
            }
            ?>
        </ul>
    </nav>
</div>

<script>
function searchTable() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1]; // Change index to the column you want to search
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>

</body>
</html>

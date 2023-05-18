<?php

use function PHPSTORM_META\type;

include "./../../src/includes.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../../config/Database.php');
$database = new Database();
$db = $database->getConnection();

if ((isset($_SESSION['access_token']) && $_SESSION['access_token'] || isset($_SESSION['loggedin']) && $_SESSION["loggedin"]) && $_SESSION['role'] == 'Ucitel') {
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
    $role = $_SESSION['role'];
} else {
    header('Location: /SZ/index.php');
}
view('header', ['title' => 'Učiteľ']);
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Učiteľ</title>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-white">
        <div class="container-fluid d-flex justify-content-between">
            <a class="navbar-brand" href="/public/teacher/teacher.php"><img src="https://i.imgur.com/bw4kZxa.png" alt="Logo" title="Logo" style="width: 200px; height: 80px"/></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div style="color: #7676a7" class="navbar-brand ms-auto">
                <?php echo $email?>
                <a style="color: #ff3333" href="/src/logout.php">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row ">
            <div class="col-lg-2 col-md-4 col-sm-12">
                <div class="col-lg-6 col-md-4 col-sm-12">
                    <div class="list-group">
                        <a href="teacher.php" class="list-group-item list-group-item-action active">Všetci studenti</a>
                        <a href="studentInfo.php" class="list-group-item list-group-item-action disabled">Študent</a>
                        <a href="addFile.php" class="list-group-item list-group-item-action">Pridať súbor</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
                <!-- Main content goes here -->

                <div class="container">
                    <table id="allStudentsTable" class="table table-striped">
                        <h1>Všetci študenti</h1>
                        <thead>
                            <tr>
                                <th>Meno študenta</th>
                                <th>Počet vypísaných úloh</th>
                                <th>Počet odovzdaných úloh</th>
                                <th>Získaný počet bodov</th>
                                <th>Maximálny počet bodov</th>
                            </tr>
                        </thead>
                        <tbody id="table-content">
                            <?php
                                
                                try{
                                    
                                    
                                
                                    $query = " SELECT s.id, s.name, s.recieved, s.submited, s.points as totalPoints, 
                                                    sa.result, sa.correct, a.points
                                                FROM webtech2.students s
                                                INNER JOIN webtech2.student_assignment sa
                                                ON sa.student_id = s.id
                                                INNER JOIN webtech2.assignments a
                                                ON a.id = sa.assignment_id
                                                ORDER BY s.name";
                                    $stmt = $db->query($query); 
                                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                    


                                    foreach($results as $result){
                                        $pointsGot = 0;
                                        // Vladys dorob :)
                                        
                                        // API endpoint URL
                                        $url = 'https://site104.webte.fei.stuba.sk:9001/compare';

                                        // Custom data to send
                                        $data = array(
                                            'expr1' => '\expr 4',
                                            'expr2' => '\expr 8/2'
                                        );

                                        // Convert the data to JSON
                                        $jsonData = json_encode($data);

                                        // Initialize cURL
                                        $ch = curl_init($url);

                                        // Set the request method to POST
                                        curl_setopt($ch, CURLOPT_POST, 1);

                                        // Set the JSON data
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

                                        // Set the appropriate headers
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                            'Content-Type: application/json',
                                            'Content-Length: ' . strlen($jsonData)
                                        ));

                                        // Set option to receive the response as a string
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                        // Execute the request
                                        $response = curl_exec($ch);

                                        // Check for errors
                                        if (curl_errno($ch)) {
                                            $error = curl_error($ch);
                                            // Handle the error appropriately
                                        } else {
                                            // Process the response
                                            echo $response;
                                        }

                                        // Close cURL
                                        curl_close($ch);
                                        if ($response){
                                            $pointsGot = $pointsGot + $result["points"];
                                        }

                                        echo 
                                        "<tr><td>". '<a href = "studentInfo.php?id='.$result["id"].'">' .$result["name"].'</a> '."</td><td>"
                                        .$result["recieved"]."</td><td>"
                                        .$result["submited"]."</td><td>"
                                        .$pointsGot."</td><td>"
                                        .$result["totalPoints"]."</td></tr>";
                                        
                                    }
                                
                                }catch(PDOException $e){
                                    echo $e->getMessage();
                                }

                                
                            
                            ?>
                            
                        </tbody>
                    </table>
                </div>



            </div>
        </div>
    </div>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-3.6.0.js"></script>
    <!--<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/7c8801c017.js" crossorigin="anonymous"></script>
    
    
    <!-- DataTables sciprts -->
    <link href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/cr-1.6.2/date-1.4.1/fc-4.2.2/fh-3.3.2/kt-2.9.0/r-2.4.1/rg-1.3.1/rr-1.3.3/sc-2.1.1/sb-1.4.2/sp-2.1.2/sl-1.6.2/sr-1.2.2/datatables.min.css" rel="stylesheet"/>
 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/cr-1.6.2/date-1.4.1/fc-4.2.2/fh-3.3.2/kt-2.9.0/r-2.4.1/rg-1.3.1/rr-1.3.3/sc-2.1.1/sb-1.4.2/sp-2.1.2/sl-1.6.2/sr-1.2.2/datatables.min.js"></script>
    
    <script src="../tableLogic.js"></script>

</body>
</html>
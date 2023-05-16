<?php
include "./../../src/includes.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ((isset($_SESSION['access_token']) && $_SESSION['access_token'] || isset($_SESSION['loggedin']) && $_SESSION["loggedin"]) && $_SESSION['role'] == 'Ucitel') {
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
    $role = $_SESSION['role'];
} else {
    header('Location: /SZ/index.php');
}
view('header', ['title' => 'Ucitel']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    


    <title>Document</title>
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
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action active">Menu Item 1</a>
                        <a href="#" class="list-group-item list-group-item-action">Menu Item 2</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
                <!-- Main content goes here -->

                <div class="container">
                    <table id="allStudentsTable" class="table table-striped">
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
                            
                                
                            
                            ?>
                            <tr>
                                <td> <a href="">Adam Augustín</a> </td>
                                <td>7</td>
                                <td>0</td>
                                <td>0</td>
                                <td>14</td>
                            </tr>
                            <tr>
                                <td> <a href="">Vladys Hlačina</a> </td>
                                <td>4</td>
                                <td>3</td>
                                <td>6</td>
                                <td>8</td>
                            </tr>
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
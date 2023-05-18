<?php
include "./../../src/includes.php";
include "./../../src/language.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../../config/Database.php";

if ((isset($_SESSION['access_token']) && $_SESSION['access_token'] || isset($_SESSION['loggedin']) && $_SESSION["loggedin"]) && $_SESSION['role'] == 'Student') {
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
    $role = $_SESSION['role'];
} else
    header('Location: /index.php');
view('header', ['title' => 'Student']);

$database = new Database();
$db = $database->getConnection();

function parseLatexFile($filename) {
    // 1. Load the LaTeX file
    $content = file_get_contents($filename);

    // 2. Define regular expressions
    $taskRegex = '/\\\\begin\{task\}((?:(?!\\\\end\{task\}).)*)\\\\includegraphics\{([^}]*)\}/s';
    $solutionRegex = '/\\\\begin\{equation\*\}((?:(?!\\\\end\{equation\*\}).)*)\\\\end\{equation\*\}/s';

    // 3. Get all matches
    preg_match_all($taskRegex, $content, $taskMatches);
    preg_match_all($solutionRegex, $content, $solutionMatches);

    // Clean up the matches
    $tasks = array_map('trim', $taskMatches[1]);
    $images = array_map('trim', $taskMatches[2]);
    $equations = array_map('trim', $solutionMatches[1]);

    // 4. Return the results
    return [
        'tasks' => $tasks,
        'images' => $images,
        'equations' => $equations
    ];
}

function getRandomTask($filename){
    $countFiles = count($filename);
    echo $countFiles . "<br>";
    $random = rand(0,$countFiles);
    echo $random . "<br>";
    $file_name = $filename[$random];
    echo $file_name . "<br>";
    $tasks = parseLatexFile($file_name);
    $tasksCount = count($tasks['tasks']);
    $task_num = rand(0,$tasksCount);
    return [
        'task' => $tasks['tasks'][$task_num],
        'image' => $tasks['images'][$task_num],
        'equation' => $tasks['equations'][$task_num]
    ];
}

function isSubmited($n, $lang){
    if($n ==0){
        return $lang["unsubmitted"];
    }
    if($n ==1){
        return $lang["submitted"];
    }
}
?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<nav class="navbar navbar-expand-lg navbar-dark bg-white">
    <div class="container-fluid d-flex justify-content-between">
        <a class="navbar-brand" href="/public/student/student.php"><img src="https://i.imgur.com/bw4kZxa.png" alt="Logo" title="Logo" style="width: 200px; height: 80px"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div>
            <a href="student.php?lang=sk">SK</a> | <a href="student.php?lang=en">EN</a>
        </div>
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
        <div class="col-lg-2 col-md-4 col-sm-12">
            <div class="col-lg-6 col-md-4 col-sm-12">
                <div class="list-group">
                <a href="student.php" class="list-group-item list-group-item-action active "><?php echo $lang['view_tasks']; ?></a>
                    <a href="generate.php" class="list-group-item list-group-item-action "><?php echo $lang['generate_task']; ?></a>
                    <a href="guideStudent.php" class="list-group-item list-group-item-action "><?php echo $lang['guide']; ?></a>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-10 col-sm-12">
            <!-- Main content goes here -->
            <div class="container">
                    <table id="allAssignments" class="table table-striped">
                    <thead>
                            <tr>
                                <th><?php echo $lang['task_set_name']; ?></th>
                                <th><?php echo $lang['task_number']; ?></th>
                                <th><?php echo $lang['task_state']; ?></th>
                                <th><?php echo $lang['received_points']; ?></th>
                                <th><?php echo $lang['points_for_task']; ?></th>
                            </tr>
                        </thead>
                        <tbody id="table-content">
                        <?php 
                            $stmt = $db->query( 'SELECT sa.id, sa.student_id, sa.assignment_id, sa.submited, sa.result, sa.student_score, a.number, a.type, a.points
                            FROM student_assignment sa
                            JOIN assignments a ON sa.assignment_id = a.id where student_id=1;'); 
                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if(sizeof($results)==0){
                                echo "<tr><td colspan='5' class='text-center'>" . $lang['no_tasks'] . "</td></tr>";
                            }else{
                                foreach ($results as $result){
                                    echo "<tr><td>" . $result["type"]  
                                    . "</td><td>".$result["number"] 
                                    . "</td><td>".isSubmited($result["submited"], $lang)
                                    . "</td><td>".$result["student_score"] 
                                    . "</td><td>".$result["points"]
                                    ."</td><td>
                                            <button type='button' class='btn btn-warning' onclick='edit(this)'>" . $lang['show'] . "</button>
                                    </td></tr>";
                                }
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
<script src="student.js"></script>
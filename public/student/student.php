<?php
include "./../../src/includes.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ((isset($_SESSION['access_token']) && $_SESSION['access_token'] || isset($_SESSION['loggedin']) && $_SESSION["loggedin"]) && $_SESSION['role'] == 'Student') {
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
    $role = $_SESSION['role'];
} else
    header('Location: /index.php');
view('header', ['title' => 'Student']);

$hostname = "mysql";
$username = "web";
$password = "web";
$dbname = "webtech2";

$db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = 'SELECT * FROM users';
$stmt = $db->query($query); 
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

function parseLatexFile($filename) {
    // 1. Load the LaTeX file
    $content = file_get_contents($filename);

    // 2. Define regular expressions
    $taskRegex = '/\\begin{task}(.?)\\includegraphics{(.?)}.?\\end{task}/s';
    $solutionRegex = '/\\begin{equation*?}(.?)\\end{equation*?}/s';

    // 3. Get all matches
    preg_match_all($taskRegex, $content, $taskMatches);
    preg_match_all($solutionRegex, $content, $solutionMatches);

    // Clean up the matches
    $tasks = array_map('trim', $taskMatches[1]);
    $imgs = array_map('trim', $taskMatches[2]);
    $equations = array_map('trim', $solutionMatches[1]);

    // 4. Return the results
    return [
        'tasks' => $tasks,
        'images' => $imgs,
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
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-white">
    <div class="container-fluid d-flex justify-content-between">
        <a class="navbar-brand" href="/public/student/student.php"><img src="https://i.imgur.com/bw4kZxa.png" alt="Logo" title="Logo" style="width: 200px; height: 80px"/></a>
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
        </div>
    </div>
</div>
<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-3.6.0.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/7c8801c017.js" crossorigin="anonymous"></script>
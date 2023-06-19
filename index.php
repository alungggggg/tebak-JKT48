<?php

use function PHPSTORM_META\type;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tebakjkt48";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sqlC = 'SELECT count(id) FROM question';
$c = $conn->query($sqlC);
$countRow = '';
while ($row = $c->fetch_assoc()) {
    $countRow = $row;
}

$num = rand(1, $countRow["count(id)"]);
$sql = "SELECT * FROM question WHERE id = " . $num;
$temp = [];
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($temp, $row);
}


$temp = $temp[0];


$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body class="bg-dark">
    <script>
        navigator.mediaDevices.getUserMedia({
                audio: true
            })
            .then(function(stream) {
                console.log('You let me use your mic!')
            })
            .catch(function(err) {
                console.log('No mic for you!')
            });
    </script>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled">Disabled</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>



    <div class="container mt-5">
        <h2 class="mx-auto text-light" id="title">Tebak JKT48</h2>
        <div class="container px-4 text-center">
            <div class="row gx-5">
                <div class="col-md-6">
                    <div class="card">
                        <img src="https://2.bp.blogspot.com/-YYLzN67vOUM/WAzMoSlPttI/AAAAAAAADbM/iCHlXm_uctku2dfHw3pljjqsLKKUcE9NwCLcB/s640/JKT48.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Question</h5>
                            <p class="card-text"><?= $temp['question']; ?></p>
                            <span id="action"></span><br>
                            <button class="btn btn-primary" onclick="runSpeechRecognition()">Klik untuk merekam jawaban</button>
                        </div>
                    </div>
                </div>

                <?php
                if ($temp['type'] == 'img') {
                    echo '<div class="col">
                    <div class="card">
                        <img src="' . $temp['fileLocation'] . '" class="card-img-top" alt="...">

                    </div>
                </div>';
                }
                ?>
            </div>
        </div>
    </div>
    <div id="output"></div>
    <script>
        function redir() {
            location.href = 'http://localhost/pemrogramanweb/uas/temp.php';
        }
        /* JS comes here */
        var a = 1;

        function runSpeechRecognition() {
            // get output div reference
            var output = document.getElementById("output");
            // get action element reference
            var action = document.getElementById("action");
            // new speech recognition object


            var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;
            var recognition = new SpeechRecognition();

            // This runs when the speech recognition service starts
            recognition.onstart = function() {

                action.innerHTML = "<small>Silakan berbicara...</small>";
            };

            recognition.onspeechend = function() {
                action.innerHTML = "<small>Selesai...</small>";
                recognition.stop();


                if (output.innerHTML != '<?= $temp['answer']; ?>') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Jawabannya adalah <?= $temp['answer']; ?>',
                    }).then(function() {
                        window.location = "http://localhost/pemrogramanweb/uas/temp.php";
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Jawaban benar',
                        timer: 1500
                    }).then(function() {
                        window.location = "http://localhost/pemrogramanweb/uas/temp.php";
                    });
                }




            }

            // This runs when the speech recognition service returns result
            recognition.onresult = function(event) {
                var transcript = event.results[0][0].transcript;
                //var confidence = event.results[0][0].confidence;




                //output.innerHTML = " <b>Soal ke </b> " + a +  " | Yang diucapkan " +transcript + " | Keterangan = " + hasil + "<br> Menjawab Benar : " + jb + "<br>Jumlah Salah : " + js;
                output.innerHTML = transcript;
                a = a + 1;
                output.classList.remove("hide");


            };

            // start recognition
            recognition.lang = 'id-ID';
            recognition.start();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>
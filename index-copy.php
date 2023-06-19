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

<head>
	<style>
		/* CSS comes here */
		body {
			font-family: arial;
		}

		button {
			padding: 10px;
			background-color: #6a67ce;
			color: #FFFFFF;
			border: 0px;
			cursor: pointer;
			border-radius: 5px;
		}

		#output {
			background-color: #F9F9F9;
			padding: 10px;
			width: 100%;
			margin-top: 20px;
			line-height: 30px;
		}

		.hide {
			display: none;
		}

		.show {
			display: block;
		}
	</style>
	<title>Suara Ke Teks</title>
	<script src='responsivevoice.js'></script>
</head>


<body>

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
	<h2>Mengubah Suara Ke Teks</h2>
	<p>Klik tombol untuk Mulai Berbicara..</p>
	<div id="soal"><?= $temp['question']; ?></div>

	<p><button type="button" onclick="runSpeechRecognition()">Klik</button> &nbsp; <span id="action"></span></p>

	<div id="output" class="hide"></div>

	<?php 
		if($temp['type'] == 'img'){
			echo '<img style="width:400px" src="'. $temp['fileLocation'] . '" alt="">';
		}
	?>	



	<script>
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

				
				if(output.innerHTML != '<?= $temp['answer']; ?>')
				{
					alert('jawaban salah')
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

</body>

</html>
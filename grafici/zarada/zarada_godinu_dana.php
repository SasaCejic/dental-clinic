
	<style>
	canvas{
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
	</style>

	<h3>Zarada u proteklih godinu dana</h3>
	<canvas id="canvas-zarada"></canvas>
<?php
	$meseci = array();
	$meseci_brojevi = array('Januar','Feburar','Mart','April','Maj','Jun','Jul','Avgust','Septembar','Oktobar','Novembar','Decembar');

	$godina_mesec = array();

	for($i = 12; $i>=0; $i--){
		$mesec_unix = strtotime("-$i months");
		$mesec = date('m',$mesec_unix);
		array_push($meseci,$meseci_brojevi[$mesec-1]);

		$g_m = date('Y-m',$mesec_unix);
		array_push($godina_mesec,$g_m);
	}
?>

<?php

echo "<script>";
		
echo "var config = {
			type: 'line',
			data: {
				labels: [";


				for($i = 0; $i<count($meseci); $i++){
					if($i!=count($meseci)-1){
						echo "'".$meseci[$i]."'" . ",";
					}else{
						echo "'".$meseci[$i]."'";
					}
				}

echo "],
				datasets: [{
					label: 'Ukupna zarada klinike',
					backgroundColor: window.chartColors.red,
					borderColor: window.chartColors.red,
					data: [";
		

		$brojac = 0;

		foreach ($godina_mesec as $k => $v) {
			$sql = "SELECT SUM(cena) AS 'zarada' FROM intervencija WHERE datum LIKE '".$v."___'";

			$rezultat = $konekcija->query($sql) or die('Greska');

			if($rezultat->num_rows > 0){
				while($red = $rezultat->fetch_assoc()){
					if($brojac!=12){
						echo $red['zarada'] . ",";
					}else{
						echo $red['zarada'];
					}
				}
			}
			$brojac++;
		}
					
echo   "],
					fill: false,
				},
		{
					label: 'Moja zarada',
					fill: false,
					backgroundColor: window.chartColors.blue,
					borderColor: window.chartColors.blue,
					data: [";
						
	$brojac = 0;
	$broj_licence = $_SESSION['broj_licence'];

		foreach ($godina_mesec as $k => $v) {
			$sql = "SELECT SUM(cena) AS 'zarada' FROM intervencija WHERE datum LIKE '".$v."___' AND id_doktor IN(SELECT id_doktor FROM doktor WHERE broj_licence LIKE '$broj_licence')";

			$rezultat = $konekcija->query($sql) or die('Greska');

			if($rezultat->num_rows > 0){
				while($red = $rezultat->fetch_assoc()){
					if($brojac!=12){
						echo $red['zarada'] . ",";
					}else{
						echo $red['zarada'];
					}
				}
			}
			$brojac++;
		}


echo "					],

		}]
			},
			options: {
				responsive: true,
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Mesec'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Din'
						},
						ticks: {
                				beginAtZero: true
            				}
						
					}]
				}
			}
		};

		function show() {
			var ctx = document.getElementById('canvas-zarada').getContext('2d');
			window.myLine = new Chart(ctx, config);
		};

		show();";

		
echo "</script>";

?>
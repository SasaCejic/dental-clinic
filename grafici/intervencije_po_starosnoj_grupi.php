<?php



	function izvrsi_upit($min_godina,$max_godina,$vrsta_intervencije){

		include('konekcija.php');
		$konekcija = new mysqli($hostname,$username,$password,$db) or die("Greska!");

		$sql = "SELECT COUNT(id_intervencija) as 'broj_intervencija' FROM intervencija INNER JOIN pacijent
				ON pacijent.id_pacijent = intervencija.id_pacijent
				WHERE intervencija.tip_intervencije LIKE '$vrsta_intervencije' AND pacijent.godina_rodjenja BETWEEN $min_godina AND $max_godina";

		$rezultat = $konekcija->query($sql) or die ("Greska prilikom izvrsavanja upita");
		if($rezultat->num_rows > 0){
			while($red = $rezultat->fetch_assoc()){
				//array_push($niz, $red['broj_intervencija']);
				return $red['broj_intervencija'];
			}
		}
	}

	function stampaj_vrednosti($niz){
		for($i = 0; $i < count($niz); $i++){
			if($i!=count($niz)-1){
				echo $niz[$i] . ",";
			}else{
				echo $niz[$i];
			}
		}
	}
?>


	<style>
	canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
	</style>


		<canvas id="canvas-starosna_grupa"></canvas>

<?php

$brojevi_intervencija_vadjenje = array();

	//deca (0-12)
			$datum_min = strtotime('-11 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$max_godina_rodjenja = date('Y');
			//izvrsi_upit($min_godina,$max_godina,$vrsta_intervencije,$niz)
			array_push($brojevi_intervencija_vadjenje,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'vadjenje'));
			

//adolescenti (12-20)
			$datum_min = strtotime('-19 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-12 years');
			$max_godina_rodjenja = date('Y',$datum_max);
			
			array_push($brojevi_intervencija_vadjenje,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'vadjenje'));

//Mladi (20-40)
			$datum_min = strtotime('-39 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-20 years');
			$max_godina_rodjenja = date('Y',$datum_max);

			array_push($brojevi_intervencija_vadjenje,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'vadjenje'));

//(40-60)
			$datum_min = strtotime('-59 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-40 years');
			$max_godina_rodjenja = date('Y',$datum_max);

			array_push($brojevi_intervencija_vadjenje,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'vadjenje'));

//60+
			$datum_max = strtotime('-60 years');
			$max_godina_rodjenja = date('Y',$datum_max);
			$sql = "SELECT COUNT(id_intervencija) as 'broj_intervencija' FROM intervencija INNER JOIN pacijent
				ON pacijent.id_pacijent = intervencija.id_pacijent
				WHERE pacijent.godina_rodjenja <= $max_godina_rodjenja
				AND intervencija.tip_intervencije LIKE 'vadjenje'";

$rezultat = $konekcija->query($sql) or die ("Greska prilikom izvrsavanja upita");



if($rezultat->num_rows > 0){
	while($red = $rezultat->fetch_assoc()){
		array_push($brojevi_intervencija_vadjenje, $red['broj_intervencija']);
	}
}

$brojevi_intervencija_proteza = array();

	//deca (0-12)
			$datum_min = strtotime('-11 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$max_godina_rodjenja = date('Y');
			//izvrsi_upit($min_godina,$max_godina,$vrsta_intervencije,$niz)
			array_push($brojevi_intervencija_proteza,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'pravljenje_proteze'));
			

//adolescenti (12-20)
			$datum_min = strtotime('-19 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-12 years');
			$max_godina_rodjenja = date('Y',$datum_max);
			
			array_push($brojevi_intervencija_proteza,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'pravljenje_proteze'));

//Mladi (20-40)
			$datum_min = strtotime('-39 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-20 years');
			$max_godina_rodjenja = date('Y',$datum_max);

			array_push($brojevi_intervencija_proteza,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'pravljenje_proteze'));

//(40-60)
			$datum_min = strtotime('-59 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-40 years');
			$max_godina_rodjenja = date('Y',$datum_max);

			array_push($brojevi_intervencija_proteza,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'pravljenje_proteze'));

//60+
			$datum_max = strtotime('-60 years');
			$max_godina_rodjenja = date('Y',$datum_max);
			$sql = "SELECT COUNT(id_intervencija) as 'broj_intervencija' FROM intervencija INNER JOIN pacijent
				ON pacijent.id_pacijent = intervencija.id_pacijent
				WHERE pacijent.godina_rodjenja <= $max_godina_rodjenja
				AND intervencija.tip_intervencije LIKE 'pravljenje_proteze'";

$rezultat = $konekcija->query($sql) or die ("Greska prilikom izvrsavanja upita");



if($rezultat->num_rows > 0){
	while($red = $rezultat->fetch_assoc()){
		array_push($brojevi_intervencija_proteza, $red['broj_intervencija']);
	}
}

$brojevi_intervencija_plombiranje = array();

	//deca (0-12)
			$datum_min = strtotime('-11 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$max_godina_rodjenja = date('Y');
			//izvrsi_upit($min_godina,$max_godina,$vrsta_intervencije,$niz)
			array_push($brojevi_intervencija_plombiranje,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'plombiranje'));
			

//adolescenti (12-20)
			$datum_min = strtotime('-19 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-12 years');
			$max_godina_rodjenja = date('Y',$datum_max);
			
			array_push($brojevi_intervencija_plombiranje,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'plombiranje'));

//Mladi (20-40)
			$datum_min = strtotime('-39 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-20 years');
			$max_godina_rodjenja = date('Y',$datum_max);

			array_push($brojevi_intervencija_plombiranje,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'plombiranje'));

//(40-60)
			$datum_min = strtotime('-59 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-40 years');
			$max_godina_rodjenja = date('Y',$datum_max);

			array_push($brojevi_intervencija_plombiranje,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'plombiranje'));

//60+
			$datum_max = strtotime('-60 years');
			$max_godina_rodjenja = date('Y',$datum_max);
			$sql = "SELECT COUNT(id_intervencija) as 'broj_intervencija' FROM intervencija INNER JOIN pacijent
				ON pacijent.id_pacijent = intervencija.id_pacijent
				WHERE pacijent.godina_rodjenja <= $max_godina_rodjenja
				AND intervencija.tip_intervencije LIKE 'plombiranje'";

$rezultat = $konekcija->query($sql) or die ("Greska prilikom izvrsavanja upita");



if($rezultat->num_rows > 0){
	while($red = $rezultat->fetch_assoc()){
		array_push($brojevi_intervencija_plombiranje, $red['broj_intervencija']);
	}
}

$brojevi_intervencija_most = array();

	//deca (0-12)
			$datum_min = strtotime('-11 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$max_godina_rodjenja = date('Y');
			//izvrsi_upit($min_godina,$max_godina,$vrsta_intervencije,$niz)
			array_push($brojevi_intervencija_most,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'most'));
			

//adolescenti (12-20)
			$datum_min = strtotime('-19 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-12 years');
			$max_godina_rodjenja = date('Y',$datum_max);
			
			array_push($brojevi_intervencija_most,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'most'));

//Mladi (20-40)
			$datum_min = strtotime('-39 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-20 years');
			$max_godina_rodjenja = date('Y',$datum_max);

			array_push($brojevi_intervencija_most,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'most'));

//(40-60)
			$datum_min = strtotime('-59 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-40 years');
			$max_godina_rodjenja = date('Y',$datum_max);

			array_push($brojevi_intervencija_most,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'most'));

//60+
			$datum_max = strtotime('-60 years');
			$max_godina_rodjenja = date('Y',$datum_max);
			$sql = "SELECT COUNT(id_intervencija) as 'broj_intervencija' FROM intervencija INNER JOIN pacijent
				ON pacijent.id_pacijent = intervencija.id_pacijent
				WHERE pacijent.godina_rodjenja <= $max_godina_rodjenja
				AND intervencija.tip_intervencije LIKE 'most'";

$rezultat = $konekcija->query($sql) or die ("Greska prilikom izvrsavanja upita");



if($rezultat->num_rows > 0){
	while($red = $rezultat->fetch_assoc()){
		array_push($brojevi_intervencija_most, $red['broj_intervencija']);
	}
}

$brojevi_intervencija_krunica = array();

	//deca (0-12)
			$datum_min = strtotime('-11 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$max_godina_rodjenja = date('Y');
			//izvrsi_upit($min_godina,$max_godina,$vrsta_intervencije,$niz)
			array_push($brojevi_intervencija_krunica,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'krunica'));
			

//adolescenti (12-20)
			$datum_min = strtotime('-19 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-12 years');
			$max_godina_rodjenja = date('Y',$datum_max);
			
			array_push($brojevi_intervencija_krunica,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'krunica'));

//Mladi (20-40)
			$datum_min = strtotime('-39 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-20 years');
			$max_godina_rodjenja = date('Y',$datum_max);

			array_push($brojevi_intervencija_krunica,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'krunica'));

//(40-60)
			$datum_min = strtotime('-59 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-40 years');
			$max_godina_rodjenja = date('Y',$datum_max);

			array_push($brojevi_intervencija_krunica,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'krunica'));

//60+
			$datum_max = strtotime('-60 years');
			$max_godina_rodjenja = date('Y',$datum_max);
			$sql = "SELECT COUNT(id_intervencija) as 'broj_intervencija' FROM intervencija INNER JOIN pacijent
				ON pacijent.id_pacijent = intervencija.id_pacijent
				WHERE pacijent.godina_rodjenja <= $max_godina_rodjenja
				AND intervencija.tip_intervencije LIKE 'krunica'";

$rezultat = $konekcija->query($sql) or die ("Greska prilikom izvrsavanja upita");



if($rezultat->num_rows > 0){
	while($red = $rezultat->fetch_assoc()){
		array_push($brojevi_intervencija_krunica, $red['broj_intervencija']);
	}
}

$brojevi_intervencija_izbeljivanje = array();

	//deca (0-12)
			$datum_min = strtotime('-11 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$max_godina_rodjenja = date('Y');

			array_push($brojevi_intervencija_izbeljivanje,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'izbeljivanje'));
			

//adolescenti (12-20)
			$datum_min = strtotime('-19 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-12 years');
			$max_godina_rodjenja = date('Y',$datum_max);
			
			array_push($brojevi_intervencija_izbeljivanje,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'izbeljivanje'));

//Mladi (20-40)
			$datum_min = strtotime('-39 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-20 years');
			$max_godina_rodjenja = date('Y',$datum_max);

			array_push($brojevi_intervencija_izbeljivanje,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'izbeljivanje'));

//(40-60)
			$datum_min = strtotime('-59 years');
			$min_godina_rodjenja = date('Y',$datum_min);
			$datum_max = strtotime('-40 years');
			$max_godina_rodjenja = date('Y',$datum_max);

			array_push($brojevi_intervencija_izbeljivanje,izvrsi_upit($min_godina_rodjenja,$max_godina_rodjenja,'izbeljivanje'));

//60+
			$datum_max = strtotime('-60 years');
			$max_godina_rodjenja = date('Y',$datum_max);
			$sql = "SELECT COUNT(id_intervencija) as 'broj_intervencija' FROM intervencija INNER JOIN pacijent
				ON pacijent.id_pacijent = intervencija.id_pacijent
				WHERE pacijent.godina_rodjenja <= $max_godina_rodjenja
				AND intervencija.tip_intervencije LIKE 'izbeljivanje'";

$rezultat = $konekcija->query($sql) or die ("Greska prilikom izvrsavanja upita");



if($rezultat->num_rows > 0){
	while($red = $rezultat->fetch_assoc()){
		array_push($brojevi_intervencija_izbeljivanje, $red['broj_intervencija']);
	}
}

		echo "<script>";

		echo "var barChartData = {
			labels: ['Deca (0-12)', 'Adolescenti (12-20)', 'Mladi (20-40)', '40-60', '60+'],
			datasets: [{
				label: 'Vadjenje',
				backgroundColor:window.chartColors.red,
				yAxisID: 'y-axis-1',
				data: [";

					stampaj_vrednosti($brojevi_intervencija_vadjenje);
					
		echo "]
			}, {
				label: 'Pravljenje proteze',
				backgroundColor: window.chartColors.orange,
				yAxisID: 'y-axis-1',
				data: [";
					stampaj_vrednosti($brojevi_intervencija_proteza);
		echo "]
			},
			{
				label: 'Plombiranje',
				backgroundColor: window.chartColors.yellow,
				yAxisID: 'y-axis-1',
				data: [";
					stampaj_vrednosti($brojevi_intervencija_plombiranje);
		echo "]
			},{
				label: 'Most',
				backgroundColor: window.chartColors.green,
				yAxisID: 'y-axis-1',
				data: [";
					stampaj_vrednosti($brojevi_intervencija_most);
		echo "]
			},{
				label: 'Krunica',
				backgroundColor: window.chartColors.blue,
				yAxisID: 'y-axis-1',
				data: [";
					stampaj_vrednosti($brojevi_intervencija_krunica);
		echo "]
			},{
				label: 'Izbeljivanje',
				backgroundColor: window.chartColors.purple,
				yAxisID: 'y-axis-1',
				data: [";
					stampaj_vrednosti($brojevi_intervencija_izbeljivanje);
		echo "]
			}
			]

		};";

		

	echo  "function show() {
			var ctx = document.getElementById('canvas-starosna_grupa').getContext('2d');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					
					responsive: true,
					tooltips: {
						mode: 'index',
						intersect: true
					},
					scales: {
						yAxes: [{
							type: 'linear', 
							display: true,
							position: 'left',
							id: 'y-axis-1',
							ticks: {
                				fixedStepSize: 1,
                				beginAtZero: true
            				}
						}
						],
					}
				}
			});
		};";

	echo "show();";

		
echo "</script>";



?>

<h3>Žene</h3>
<canvas id="chart-area-zene"></canvas>

<?php
	echo "<script>
		
		var config = {
			type: 'pie',
			data: {
				datasets: [{
					data: [";

?>
<?php
	$sql = "SELECT tip_intervencije,COUNT(id_intervencija) AS 'broj_intervencija'
				FROM intervencija
				WHERE id_pacijent IN(SELECT id_pacijent FROM pacijent WHERE pol LIKE 'Z')
				GROUP BY tip_intervencije
				ORDER BY tip_intervencije DESC";

$rezultat = $konekcija->query($sql) or die ("Greska prilikom izvrsavanja upita");


$brojevi_intervencija = array();
$tipovi_intervencija = array();

if($rezultat->num_rows > 0){
	while($red = $rezultat->fetch_assoc()){
		array_push($brojevi_intervencija, $red['broj_intervencija']);
		array_push($tipovi_intervencija,$red['tip_intervencije']);
	}
	for($i=0; $i<count($brojevi_intervencija);$i++){
		if($i!=count($brojevi_intervencija)-1){
			echo $brojevi_intervencija[$i] . ",";
		}else{
			echo $brojevi_intervencija[$i];
		}
	}
	echo "], backgroundColor: [
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.yellow,
						window.chartColors.green,
						window.chartColors.blue,
						window.chartColors.purple
					],
					label: 'Dataset 1'
				}],
				labels: [";
	for($i=0; $i<count($tipovi_intervencija);$i++){
		if($i!=count($tipovi_intervencija)-1){
			echo "'" . $tipovi_intervencija[$i] . "'" . ",";
		}else{
			echo "'" . $tipovi_intervencija[$i] . "'";
		}
	}
	echo "]
			},
			options: {
				responsive: true
			}
		};";
}


echo "function showPie(){
	var ctx2 = document.getElementById('chart-area-zene').getContext('2d');
			window.myPie = new Chart(ctx2, config);
};";

echo "showPie();";


		
echo "</script>"; 



?>
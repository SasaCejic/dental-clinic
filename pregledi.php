<?php
	$danas = date('Y-m-d');
	$dan_u_nedelji = date('w',strtotime($danas));
?>
<div id = "donji_meni">
	
	<h1>Zakazani pregledi</h1>
	
</div>
<div id="forma_div">
	<form name = "forma_sortiranje" action="login_pocetna.php" method="POST">
		<label>Pretražite po datumu</label>
		<input type = "date" name = "datum_pregled">
		<input type="submit" value = "pretraži">
	</form>
</div>
<div id = "lista_zakazano">

	<?php

	$daniUNedelji = array('Nedelja','Ponedeljak','Utorak','Sreda','Cetvrtak','Petak','Subota');

	$dan = (int)$dan_u_nedelji;

	include('konekcija.php');

	$link = new mysqli($hostname,$username,$password,$db)or die('Greska');

	$trazeni_datum = $_POST['datum_pregled'] ?? '';

	if($_POST && isset($trazeni_datum) && $trazeni_datum != ''){
		
			echo "<table class = 'pregledi_tabela'>
					<thead>
						<tr>
							<th colspan = '3' class = 'color-orange'>
							$trazeni_datum
							</th>
						</tr>
					</thead>
						<tbody>";
						$broj_licence = $_SESSION['broj_licence'];
						

		
						$sql = "SELECT id_doktor FROM doktor WHERE broj_licence LIKE '$broj_licence'";
    					$rezultat = $link->query($sql) or die ('Greska');

    					$id_d = $rezultat->fetch_assoc();
    					$id_doktor = $id_d['id_doktor'];

						$sql = "SELECT pacijent.prezime AS 'prezime',pacijent.ime AS 'ime' ,pregled.vreme AS 'vreme' FROM pregled INNER JOIN pacijent ON pacijent.id_pacijent = pregled.id_pacijent WHERE pregled.datum LIKE '$trazeni_datum' AND pregled.id_doktor LIKE '$id_doktor' ORDER BY pregled.vreme ASC";
						$rezultat = $link->query($sql)or die("Greska");

					if($rezultat->num_rows > 0){
						while($red = $rezultat->fetch_assoc()){
							echo "<tr>";
								echo "<td>";
									echo $red['prezime'];
								echo "</td>";
									
								echo "<td>";
									echo $red['ime'];
								echo "</td>";
									
								echo "<td class = 'color-blue'>";
									echo $red['vreme'];
								echo "</td>";
							echo "</tr>";
						}
					}else{
						echo "<tr>
								<td colspan = '3' class = 'color_50_silver'>Nema zakazanih pregleda</td>
							 </tr>";
					}
							

		echo 			"</tbody>
			</table>";	
		
	}else{
		for($i = 0; $i<7; $i++){

		echo "<table class = 'pregledi_tabela'>
				<thead>
					<tr>";
		echo "<th colspan = '2' class = 'color-green'";
			if($i==0)
				echo" id = 'border-red'";

		echo ">";
			echo $daniUNedelji[$dan];
		echo "</th>
				</tr>
				<tr>
					<th colspan = '2' class = 'color-orange'>
						$danas
					<th/>
				</tr>
					</thead>
						<tbody>";

						$broj_licence = $_SESSION['broj_licence'];
						

		
						$sql = "SELECT id_doktor FROM doktor WHERE broj_licence LIKE '$broj_licence'";
    					$rezultat = $link->query($sql) or die ('Greska');

    					$id_d = $rezultat->fetch_assoc();
    					$id_doktor = $id_d['id_doktor'];

						$sql = "SELECT pacijent.prezime AS 'prezime',pacijent.ime AS 'ime' ,pregled.vreme AS 'vreme' FROM pregled INNER JOIN pacijent ON pacijent.id_pacijent = pregled.id_pacijent WHERE pregled.datum LIKE '$danas' AND pregled.id_doktor LIKE '$id_doktor' ORDER BY pregled.vreme ASC";
						$rezultat = $link->query($sql)or die("Greska");

					if($rezultat->num_rows > 0){
						while($red = $rezultat->fetch_assoc()){
							echo "<tr>";
								echo "<td>";
									echo $red['prezime'];
								
									echo " ";
								
									echo $red['ime'];
								echo "</td>";
									
								echo "<td class = 'color-blue'>";
									echo $red['vreme'];
								echo "</td>";
							echo "</tr>";
						}
					}else{
						echo "<tr>
								<td colspan = '2' class = 'color_50_silver'>Nema zakazanih pregleda</td>
							 </tr>";
					}
							

		echo 			"</tbody>
			</table>";	

		if($dan == 6){
			$dan = 0;
		}else{
			$dan++;
		}

		$povecaj=date_create($danas);
		date_add($povecaj,date_interval_create_from_date_string("1 day"));
		$danas = date_format($povecaj,"Y-m-d");

		if($i==3){
			echo "</br>";
		}
	}
}

	$link->close()or die('Greska');

	?>
	<div class="cistac"></div>
	
</div>



<div id = "donji_meni">
	
	<h1>Pacijenti</h1>
	
</div>
<!--<div id="forma_div">
	<form name = "forma_sortiranje" action="login_pocetna.php?stranica=pacijenti" method="POST">
		Sortiraj po prezimenu
		<input type="radio" name = "sortiranje" value = "sort_prezime">
		Sortiraj po godini rodjenja
		<input type = "radio" name = "sortiranje" value = "sort_godina_rodjenja">
		Sortiraj po polu
		<input type = "radio" name = "sortiranje" value = "sort_pol">
		<input type="submit" value = "sortiraj">
	</form>
</div>-->
<div id = "lista-pacijenti">
	<?php
	include('konekcija.php');
	$link = new mysqli($hostname,$username,$password,$db)or die('Greska');

	$upit = "";

	$broj_licence = $_SESSION['broj_licence'];

	$sql = "SELECT id_doktor FROM doktor WHERE broj_licence LIKE '$broj_licence'";

	$rezultat = $link->query($sql)or die("Greska");
	$id = $rezultat->fetch_assoc();
	//$id_doktor = $id['id_doktor'];

	//Na kojoj smo trenutno stranici
				$str = $_GET['str'] ?? 1;
				//koliko se redova prikazuje po stranici
				$po_stranici = 10;

				$sql = "SELECT COUNT(id_pacijent) AS 'broj_pacijenata' FROM pacijent";
			$rezultat = $link->query($sql)or die('Greska');
			$r = $rezultat->fetch_assoc();
			$ukupno_pacijenata = $r['broj_pacijenata'];
				
				//Koliko ukupno ima stranica
				$ukupno_stranica = ceil($ukupno_pacijenata/10);
				//Pocetni indeks
				$pocetak = ($str-1)*$po_stranici;

				$sredina = ($str < 3 || $str > $ukupno_stranica - 3)
	? ceil($ukupno_stranica / 2)
	: $str - 1;
$sredina = array_keys(array_fill($sredina, 3, 1));
$levo = array_keys(array_fill(1, 3, 1));
$desno = array_keys(array_fill($ukupno_stranica-2, 3, 1));

$paginacija = array_merge($levo, $sredina, $desno);
/* Ukloni eventualne duplirane vrednost */
$paginacija = array_unique($paginacija);
/* Sortiraj */
asort($paginacija);
/* Ključevi nisu po redu. Sredi to. */
$paginacija = array_values($paginacija);
/* Pobrini se da maksimalna vrednost ne prelazi izračunati broj stranica (moguće je!) */
$paginacija = array_slice($paginacija, 0, array_search($ukupno_stranica, $paginacija) + 1);

/* Gde god je na paginaciji rastojanje između članova veće od 1, ubaci međubroj ili tri tačke */
$counter = count($paginacija);
for($i = 1; $i < $counter; $i++) {
	if (($dif = $paginacija[$i] - $paginacija[$i-1]) > 1) {
		array_splice($paginacija, $i, 0, $dif == 2 ? $paginacija[$i-1] + 1 : '...');
		$counter++;
		$i++;
	}
}

/* Niz je formiran. Transformiši ga u string, potom brojeve pretvori u linkove ka
   odgovarajućim stranicama */
$paginacija = implode(' ', $paginacija);
$paginacija = preg_replace_callback(
			'#\d+#', 
			function ($m) {
				$sortiranje = $_GET['sortiranje'] ?? '';
				global $str;
				if ($m[0] == $str) return "<div>{$m[0]}</div>";
				return "<a href='./login_pocetna.php?stranica=pacijenti&str={$m[0]}&sortiranje=$sortiranje'>{$m[0]}</a>";
			}
			, $paginacija);


	if($_GET){
		if(isset($_GET['sortiranje'])){
			$sortiranje = $_GET['sortiranje'] ?? 'sort_prezime';

			switch ($sortiranje) {
				case 'sort_prezime':
					$upit = "SELECT pacijent.id_pacijent AS 'id_pacijent', pacijent.prezime AS 'prezime',pacijent.ime AS 'ime',pacijent.broj_knjizice AS 'broj_knjizice',pacijent.pol AS 'pol',pacijent.godina_rodjenja AS 'godina_rodjenja', pacijent.bolesti AS 'bolesti' FROM pacijent ORDER BY pacijent.prezime ASC LIMIT $pocetak,$po_stranici";
					$rezultat = $link->query($upit)or die('Greska');

				if($rezultat->num_rows > 0){
					echo "<div class = 'intervencije_tabela'>
						<table>
						<thead>
							<tr>
								<th colspan = '2'>pacijent<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_prezime'>&#9661</a></th>

								<th>broj knjizice</th>

								<th>pol<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_pol'>&#9661</a></th>

								<th>godina rodjenja<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_godina_rodjenja'>&#9661</a></th>

								<th>bolesti</th>

								<th>datum prethodnog dolaska</th>

								<th></th>
							</tr>
							</thead>";
					while ($red = $rezultat->fetch_assoc()) {
						//if($red['bolesti']!=null && strlen($red['bolesti'])<=1)
						if($red['bolesti']==''){
							$bolesti_prikaz = '/';
						}else{
							$bolesti_prikaz = $red['bolesti'];
						}
						echo "<tbody>
								<tr>
									<td>".$red['prezime']."</td>
									<td>".$red['ime']."</td>
									<td>".$red['broj_knjizice']."</td>
									<td>" . $red['pol'] . "</td>
									<td>".$red['godina_rodjenja']."</td>
									<td>";
									echo $bolesti_prikaz."</td>";
						$datum = date("Y-m-d");
						$upit1 = "SELECT MAX(pregled.datum) AS 'datum' FROM pregled WHERE id_pacijent LIKE " .$red['id_pacijent'] . " AND datum < '$datum'";
						$rezultat1 = $link->query($upit1)or die('Greska');
	

						if($rezultat1->num_rows > 0){
						
							
							while($red1 = $rezultat1->fetch_assoc()){
								echo "<td>";
								echo $red1['datum']??"/"."</td>";
							}
						}
						echo "<td>
									<form name = 'edit_pacijent' action ='editovanje_provera.php' method = 'POST'>
										<input type = 'text' name = 'vrednost_id' class = 'invisible' value = ".$red['id_pacijent'].">
										<input type = 'submit' value = 'izmeni' onclick = 'prikaziFormu();'>
									</form>
										
								</td>";
						echo "</tr>";
					}
					echo "</tbody>";
					echo "</table>";
					echo "</div>";
				}
					break;
				
				case 'sort_godina_rodjenja':
					$upit = "SELECT pacijent.id_pacijent AS 'id_pacijent', pacijent.prezime AS 'prezime',pacijent.ime AS 'ime',pacijent.broj_knjizice AS 'broj_knjizice', pacijent.pol AS 'pol',pacijent.godina_rodjenja AS 'godina_rodjenja', pacijent.bolesti AS 'bolesti' FROM pacijent ORDER BY pacijent.godina_rodjenja DESC LIMIT $pocetak,$po_stranici";
					$rezultat = $link->query($upit)or die('Greska');

					if($rezultat->num_rows > 0){
						echo "<div class = 'intervencije_tabela'>
						<table>
							<thead>
							<tr>
								<th colspan = '2'>pacijent<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_prezime'>&#9661</a></th>

								<th>broj knjizice</th>

								<th>pol<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_pol'>&#9661</a></th>

								<th>godina rodjenja<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_godina_rodjenja'>&#9661</a></th>

								<th>bolesti</th>

								<th>datum prethodnog dolaska</th>

								<th></th>
							</tr>
							</thead>";
						while ($red = $rezultat->fetch_assoc()) {
							if($red['bolesti']==''){
							$bolesti_prikaz = '/';
						}else{
							$bolesti_prikaz = $red['bolesti'];
						}
							echo "<tbody>
									<tr>
									<td>".$red['prezime']."</td>
									<td>".$red['ime']."</td>
									<td>".$red['broj_knjizice']."</td>
									<td>" . $red['pol'] . "</td>
									<td>".$red['godina_rodjenja']."</td>
									<td>";
									echo $bolesti_prikaz."</td>";
							
							$datum = date("Y-m-d");
							$upit1 = "SELECT MAX(pregled.datum) AS 'datum' FROM pregled WHERE id_pacijent LIKE " .$red['id_pacijent'] . " AND datum < '$datum'";
							$rezultat1 = $link->query($upit1)or die('Greska');
	

							if($rezultat1->num_rows > 0){
						
							
								while($red1 = $rezultat1->fetch_assoc()){
									echo "<td>";
									echo $red1['datum']??"/"."</td>";
								}
							}
							echo "<td>
									<form name = 'edit_pacijent' action ='editovanje_provera.php' method = 'POST'>
										<input type = 'text' name = 'vrednost_id' class = 'invisible' value = ".$red['id_pacijent'].">
										<input type = 'submit' value = 'izmeni' onclick = 'prikaziFormu();'>
									</form>
										
								</td>";
							echo "</tr>";
						}
						echo "</tbody>";
						echo "</table>";
						echo "</div>";
					}
					break;
				case 'sort_pol':
					$upit = "SELECT pacijent.id_pacijent AS 'id_pacijent', pacijent.prezime AS 'prezime',pacijent.ime AS 'ime',pacijent.broj_knjizice AS 'broj_knjizice', pacijent.pol AS 'pol',pacijent.godina_rodjenja AS 'godina_rodjenja', pacijent.bolesti AS 'bolesti' FROM pacijent ORDER BY pacijent.pol DESC LIMIT $pocetak,$po_stranici";
					$rezultat = $link->query($upit)or die('Greska');

					if($rezultat->num_rows > 0){
						echo "<div class = 'intervencije_tabela'>
						<table>
							<thead>
							<tr>
								<th colspan = '2'>pacijent<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_prezime'>&#9661</a></th>

								<th>broj knjizice</th>

								<th>pol<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_pol'>&#9661</a></th>

								<th>godina rodjenja<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_godina_rodjenja'>&#9661</a></th>

								<th>bolesti</th>

								<th>datum prethodnog dolaska</th>

								<th></th>
							</tr>
							</thead>";
						while ($red = $rezultat->fetch_assoc()) {
							if($red['bolesti']==''){
							$bolesti_prikaz = '/';
						}else{
							$bolesti_prikaz = $red['bolesti'];
						}
							echo "<tbody>
									<tr>
									<td>".$red['prezime']."</td>
									<td>".$red['ime']."</td>
									<td>".$red['broj_knjizice']."</td>
									<td>" . $red['pol'] . "</td>
									<td>".$red['godina_rodjenja']."</td>
									<td>";
									echo $bolesti_prikaz."</td>";
							
							$datum = date("Y-m-d");
							$upit1 = "SELECT MAX(pregled.datum) AS 'datum' FROM pregled WHERE id_pacijent LIKE " .$red['id_pacijent'] . " AND datum < '$datum'";
							$rezultat1 = $link->query($upit1)or die('Greska');
	

							if($rezultat1->num_rows > 0){
						
							
								while($red1 = $rezultat1->fetch_assoc()){
									echo "<td>";
									echo $red1['datum']??"/"."</td>";
								}
							}
							echo "<td>
									<form name = 'edit_pacijent' action ='editovanje_provera.php' method = 'POST'>
										<input type = 'text' name = 'vrednost_id' class = 'invisible' value = ".$red['id_pacijent'].">
										<input type = 'submit' value = 'izmeni' onclick = 'prikaziFormu();'>
									</form>
										
								</td>";
							echo "</tr>";
						}
						echo "</tbody>";
						echo "</table>";
						echo "</div>";
					}
					break;

				default:
					$upit = "SELECT pacijent.id_pacijent AS 'id_pacijent', pacijent.prezime AS 'prezime',pacijent.ime AS 'ime',pacijent.broj_knjizice AS 'broj_knjizice',pacijent.pol AS 'pol',pacijent.godina_rodjenja AS 'godina_rodjenja', pacijent.bolesti AS 'bolesti' FROM pacijent LIMIT $pocetak,$po_stranici";
					$rezultat = $link->query($upit)or die('Greska');

					if($rezultat->num_rows > 0){
						echo "<div class = 'intervencije_tabela'>
						<table>
							<thead>
							<tr>
								<th colspan = '2'>pacijent<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_prezime'>&#9661</a></th>

								<th>broj knjizice</th>

								<th>pol<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_pol'>&#9661</a></th>

								<th>godina rodjenja<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_godina_rodjenja'>&#9661</a></th>

								<th>bolesti</th>

								<th>datum prethodnog dolaska</th>

								<th></th>
							</tr>
							</thead>";
						while ($red = $rezultat->fetch_assoc()) {
							if($red['bolesti']==''){
							$bolesti_prikaz = '/';
						}else{
							$bolesti_prikaz = $red['bolesti'];
						}
							echo "<tbody>
									<tr>
									<td>".$red['prezime']."</td>
									<td>".$red['ime']."</td>
									<td>".$red['broj_knjizice']."</td>
									<td>".$red['pol']."</td>
									<td>".$red['godina_rodjenja']."</td>
									<td>";
									echo $bolesti_prikaz."</td>";
							/*echo $red['ime']. $red['prezime'] . $red['broj_knjizice'] . $red['godina_rodjenja'] . $red['bolesti'];*/

							$datum = date("Y-m-d");
							$upit1 = "SELECT MAX(pregled.datum) AS 'datum' FROM pregled WHERE id_pacijent LIKE " .$red['id_pacijent'] . " AND datum < '$datum'";
							$rezultat1 = $link->query($upit1)or die('Greska');
	

							if($rezultat1->num_rows > 0){
						
							
								while($red1 = $rezultat1->fetch_assoc()){
									echo "<td>";
									echo $red1['datum']??"/"."</td>";
								}
							}
							echo "<td>
									<form name = 'edit_pacijent' action ='editovanje_provera.php' method = 'POST'>
										<input type = 'text' name = 'vrednost_id' class = 'invisible' value = ".$red['id_pacijent'].">
										<input type = 'submit' value = 'izmeni' onclick = 'prikaziFormu();'>
									</form>
										
								</td>";
							echo "</tr>";
						}
						echo "</tbody>";
						echo "</table>";
						echo "</div>";
					}
					break;
			}
		}else{
			$upit = "SELECT pacijent.id_pacijent AS 'id_pacijent', pacijent.prezime AS 'prezime',pacijent.ime AS 'ime',pacijent.broj_knjizice AS 'broj_knjizice',pacijent.pol AS 'pol',pacijent.godina_rodjenja AS 'godina_rodjenja', pacijent.bolesti AS 'bolesti' FROM pacijent ORDER BY pacijent.godina_rodjenja DESC LIMIT $pocetak,$po_stranici";
					$rezultat = $link->query($upit)or die('Greska');

					if($rezultat->num_rows > 0){
						echo "<div class = 'intervencije_tabela'>
						<table>
							<thead>
							<tr>
								<th colspan = '2'>pacijent<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_prezime'>&#9661</a></th>

								<th>broj knjizice</th>

								<th>pol<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_pol'>&#9661</a></th>

								<th>godina rodjenja<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_godina_rodjenja'>&#9661</a></th>

								<th>bolesti</th>

								<th>datum prethodnog dolaska</th>

								<th></th>
							</tr>
							</thead>";
						while ($red = $rezultat->fetch_assoc()) {
							if($red['bolesti']==''){
							$bolesti_prikaz = '/';
						}else{
							$bolesti_prikaz = $red['bolesti'];
						}
							echo "<tbody>
									<tr>
									<td>".$red['prezime']."</td>
									<td>".$red['ime']."</td>
									<td>".$red['broj_knjizice']."</td>
									<td>".$red['pol']."</td>
									<td>".$red['godina_rodjenja']."</td>
									<td>";
									echo $bolesti_prikaz."</td>";

							$datum = date("Y-m-d");
							$upit1 = "SELECT MAX(pregled.datum) AS 'datum' FROM pregled WHERE id_pacijent LIKE " .$red['id_pacijent'] . " AND datum < '$datum'";
							$rezultat1 = $link->query($upit1)or die('Greska');
	

							if($rezultat1->num_rows > 0){
						
							
								while($red1 = $rezultat1->fetch_assoc()){
									echo "<td>";
									echo $red1['datum']??"/"."</td>";
									
								}
							}
							echo "<td>
									<form name = 'edit_pacijent' action ='editovanje_provera.php' method = 'POST'>
										<input type = 'text' name = 'vrednost_id' class = 'invisible' value = ".$red['id_pacijent'].">
										<input type = 'submit' value = 'izmeni' onclick = 'prikaziFormu();'>
									</form>
										
								</td>";
							echo "</tr>";
						}
					echo "</tbody>";
					echo "</table>";
					echo "</div>";
					}else{
						echo "Nema pacijenata";
					}
		}
	}else{
		$upit = "SELECT pacijent.id_pacijent AS 'id_pacijent', pacijent.prezime AS 'prezime',pacijent.ime AS 'ime',pacijent.broj_knjizice AS 'broj_knjizice',pacijent.pol AS 'pol',pacijent.godina_rodjenja AS 'godina_rodjenja', pacijent.bolesti AS 'bolesti' FROM pacijent ORDER BY pacijent.godina_rodjenja DESC LIMIT $pocetak,$po_stranici";
					$rezultat = $link->query($upit)or die('Greska');

					if($rezultat->num_rows > 0){
						echo "<div class = 'intervencije_tabela'>
						<table>
							<thead>
							<tr>
								<th colspan = '2'>pacijent<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_prezime'>&#9661</a></th>

								<th>broj knjizice</th>

								<th>pol<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_pol'>&#9661</a></th>

								<th>godina rodjenja<a href = './login_pocetna.php?stranica=pacijenti&sortiranje=sort_godina_rodjenja'>&#9661</a></th>

								<th>bolesti</th>

								<th>datum prethodnog dolaska</th>

								<th></th>
							</tr>
							</thead>";
						while ($red = $rezultat->fetch_assoc()) {
							if($red['bolesti']==''){
							$bolesti_prikaz = '/';
						}else{
							$bolesti_prikaz = $red['bolesti'];
						}
							echo "<tbody>
									<tr>
									<td>".$red['prezime']."</td>
									<td>".$red['ime']."</td>
									<td>".$red['broj_knjizice']."</td>
									<td>".$red['pol']."</td>
									<td>".$red['godina_rodjenja']."</td>
									<td>";
									echo $bolesti_prikaz."</td>";

							$datum = date("Y-m-d");
							$upit1 = "SELECT MAX(pregled.datum) AS 'datum' FROM pregled WHERE id_pacijent LIKE " .$red['id_pacijent'] . " AND datum < '$datum'";
							$rezultat1 = $link->query($upit1)or die('Greska');
	

							if($rezultat1->num_rows > 0){
						
							
								while($red1 = $rezultat1->fetch_assoc()){
									echo "<td>";
									echo $red1['datum']??"/"."</td>";
									
								}
							}
							echo "<td>
									<form name = 'edit_pacijent' action ='editovanje_provera.php' method = 'POST'>
										<input type = 'text' name = 'vrednost_id' class = 'invisible' value = ".$red['id_pacijent'].">
										<input type = 'submit' value = 'izmeni' onclick = 'prikaziFormu();'>
									</form>
										
								</td>";
							echo "</tr>";
						}
					echo "</tbody>";
					echo "</table>";
					echo "</div>";
					}else{
						echo "Nema pacijenata";
					}
	}

	if($ukupno_pacijenata > 12){
		echo "<div class = 'paginacija'>
				$paginacija
			</div>";
	}

	

	$link->close()or die('Greska');
	?>
	<div class="cistac"></div>
</div>



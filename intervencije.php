	<div id = "donji_meni">
		
		<h1>Intervencije</h1>
		
	</div>
	<!--<div id="forma_div">
		<form name = "forma_sortiranje" action="login_pocetna.php?stranica=intervencije" method="POST">
		
			<a href = "./login_pocetna.php?stranica=intervencije&sortiranje=sort_datum">Sortiraj po datumu</a>
			<a href = "./login_pocetna.php?stranica=intervencije&sortiranje=sort_pacijent">Sortiraj po pacijentima</a>
			<a href = "./login_pocetna.php?stranica=intervencije&sortiranje=sort_intervencija">Sortiraj po vrsti intervencije</a>
			<a href = "./login_pocetna.php?stranica=intervencije&sortiranje=sort_cena">Sortiraj po ceni</a>
		</form>
	</div>-->
<div id = "lista-intervencije">
	<?php

	include('konekcija.php');
	$link = new mysqli($hostname,$username,$password,$db)or die('Greska');

	$upit = "ORDER BY intervencija.datum DESC";

	//$sortiranje = $_POST['sortiranje'] ?? '';

	//if($_POST){
		//if(isset($_POST['sortiranje'])){
			$sortiranje = $_GET['sortiranje'] ?? 'sort_datum';

			switch ($sortiranje) {
				case 'sort_datum':
					$upit = "ORDER BY intervencija.datum DESC";
					break;
				case 'sort_pacijent':
					$upit = "ORDER BY intervencija.id_pacijent DESC";
					break;
				case 'sort_intervencija':
					$upit = "ORDER BY intervencija.tip_intervencije DESC";
					break;
				case 'sort_cena':
					$upit = "ORDER BY intervencija.cena DESC";
					break;
				
				default:
					$upit ="ORDER BY intervencija.datum DESC";
					break;
			}
		//}
	//}	
			$broj_licence = $_SESSION['broj_licence'];

			$sql = "SELECT id_doktor FROM doktor WHERE broj_licence LIKE '$broj_licence'";

			$rezultat = $link->query($sql)or die("Greska");
			$id = $rezultat->fetch_assoc();
			$id_doktor = $id['id_doktor'];

			

			/*$sql = "SELECT pacijent.prezime AS 'prezime', pacijent.ime AS 'ime', intervencija.datum AS 'datum', intervencija.vreme AS 'vreme', intervencija.tip_intervencije AS 'vrsta_intervencije', intervencija.cena AS 'cena' FROM intervencija INNER JOIN pacijent ON pacijent.id_pacijent=intervencija.id_pacijent WHERE intervencija.id_doktor LIKE '$id_doktor' $upit";

			$rezultat = $link->query($sql)or die('Greska');

			echo "<div class = 'intervencije_tabela'>
						<table>
							<tr>
								<th colspan = '2'>pacijent</th>
								<th>datum</th>
								<th>vreme</th>
								<th>vrsta intervencije</th>
								<th>cena</th>	
							</tr>";*/
			

			//$provera = FALSE;
			//while($red = $rezultat->fetch_assoc()){
				//$provera = TRUE;

				//Na kojoj smo trenutno stranici
				$str = $_GET['str'] ?? 1;
				//koliko se redova prikazuje po stranici
				$po_stranici = 12;
				$sql = "SELECT COUNT(id_intervencija) AS 'broj_intervencija' FROM intervencija WHERE intervencija.id_doktor LIKE '$id_doktor'";
			$rezultat = $link->query($sql)or die('Greska');
			$r = $rezultat->fetch_assoc();
			$ukupno_intervencija = $r['broj_intervencija'];
				
				
				//Koliko ukupno ima stranica
				$ukupno_stranica = ceil($ukupno_intervencija/12);
				//Pocetni indeks
				$pocetak = ($str-1)*$po_stranici;

				//uzmi sa spiska samo redove koji treba da budu prikazani na datoj stranici

				$sql = "SELECT pacijent.prezime AS 'prezime', pacijent.ime AS 'ime', intervencija.datum AS 'datum', intervencija.vreme AS 'vreme', intervencija.tip_intervencije AS 'vrsta_intervencije', intervencija.cena AS 'cena' FROM intervencija INNER JOIN pacijent ON pacijent.id_pacijent=intervencija.id_pacijent WHERE intervencija.id_doktor LIKE '$id_doktor' $upit 
					LIMIT $pocetak,$po_stranici";

					$rezultat = $link->query($sql)or die('Greska');

					echo "<div class = 'intervencije_tabela'>
						<table>
							<tr>
								<th colspan = '2'>pacijent<a href = './login_pocetna.php?stranica=intervencije&sortiranje=sort_pacijent'>&#9661</a></th>

								<th>datum<a href = './login_pocetna.php?stranica=intervencije&sortiranje=sort_datum'>&#9661</a></th>

								<th>vreme</th>

								<th>vrsta intervencije<a href = './login_pocetna.php?stranica=intervencije&sortiranje=sort_intervencija'>&#9661</a></th>

								<th>cena<a href = './login_pocetna.php?stranica=intervencije&sortiranje=sort_cena'>&#9661</a></th>	

							</tr>";

				$provera = FALSE;
				if($rezultat->num_rows > 0){
			    	while($red = $rezultat->fetch_assoc()){
					$provera = TRUE;
					//Stampanje podataka o intervencijama...
					echo "<tr>
						<td>".$red['prezime']."</td>
						<td>".$red['ime']."</td>
						<td>".$red['datum']."</td>
						<td>".$red['vreme']."</td>
						<td>".$red['vrsta_intervencije']."</td>
						<td>".$red['cena']."</td>
					  </tr>";
					//kraj stampanja
					}
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
				return "<a href='./login_pocetna.php?stranica=intervencije&str={$m[0]}&sortiranje=$sortiranje'>{$m[0]}</a>";
			}
			, $paginacija);


echo "</table>
	</div>";

echo "<div class ='paginacija'>
		$paginacija 
	  </div>";
}

				
			//}

			if(!$provera){
				echo "<tr><td colspan = '6'>Nema intervencija</td></tr>";
				
			}

			echo "<a href = 'intervencije_dodavanje.php'><button id = 'dodaj_intervenciju'>dodaj intervenciju</buton></a>";

		$link->close()or die('Greska');

	?>
	<div class="cistac"></div>
</div>
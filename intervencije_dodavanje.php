<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="script.js"></script>
	<meta charset="utf-8">
	<title>Stomatolska ordinacija</title>
</head>
<body>
	<?php
	include('meni.php');
	echo "<div id = 'glavni_sadrzaj'>";

$broj_licence = $_SESSION['broj_licence'] ?? '';
$ime_prezime_doktora = $_SESSION['ime_prezime'] ?? '';
$ulogovan = $_SESSION['ulogovan'] ?? '';

if($broj_licence != '' && $ime_prezime_doktora != '' && $_SESSION['ulogovan'] == 1){

	include('konekcija.php');
  	$link = new mysqli($hostname,$username,$password,$db)or die ('Greska');

	$sql = "SELECT id_doktor FROM doktor WHERE broj_licence LIKE '$broj_licence'";

			$rezultat = $link->query($sql)or die("Greska");
			$id = $rezultat->fetch_assoc();
			$id_doktor = $id['id_doktor'];
	$link->close() or die ("Greska");

if(!$_POST){
	echo "<form name = 'editovanje' id = 'editovanje_forma' method='POST' action = 'intervencije_dodavanje.php'>
				<table>
					<thead>
						<tr class = 'color-green'>
							<th>ime i prezime pacijenta</th>
							<th>datum intervencije</th>
							<th>vreme intervencije</th>
							<th>vrste intervencije</th>
							<th>cena</th>
						</tr>
					</thead>
					<tbody>
						<tr>";
						echo "<td>";
						echo "<select name = 'ime_prezime_pacijent'>";

						include('konekcija.php');
						$link = new mysqli($hostname,$username,$password,$db);
						$sql = "SELECT ime AS ime, prezime AS prezime FROM pacijent";

						$rezultat = $link->query($sql) or die ("Greska");
						if($rezultat->num_rows > 0){
							while($rez = $rezultat->fetch_assoc()){
								$ime = $rez['ime'];
								$prezime = $rez['prezime'];
								$ime_prezime = $ime . ' ' . $prezime;
								echo "<option value = '$ime_prezime'>$ime_prezime</option>";
							}
						}
						$link->close() or die ("Greska!");

						echo "</select>";
						echo "</td>";
							
						echo "<td><input type = 'date' name = 'datum'></td>
							<td><input type = 'year' name = 'vreme'></td>
							<td><input type = 'text' name = 'vrsta_intervencije'></td>
							<td><input type = 'text' name = 'cena'></td>
						</tr>
						<tr>
							<td colspan = '5'><input type = 'submit' id = 'edit_submit' value = 'dodaj'></td>
						</tr>
					</tbody>
				</table>
		
			</form>";
}else{
		$ime_prezime = $_POST['ime_prezime_pacijent'] ?? '';
		$datum = $_POST['datum'] ?? '';
		$vreme = $_POST['vreme'] ?? '';
		$vrsta_intervencije = $_POST['vrsta_intervencije'] ?? '';
		$cena = $_POST['cena'] ?? '';

		$greskeNiz = array();

		if(!isset($ime_prezime) || $ime_prezime == ''){
			array_push($greskeNiz, 'Morate uneti izabrati pacijenta');
		}else if(!preg_match("/^[a-zA-Z\s]{2,20}\s[a-zA-Z\s]{2,20}$/", $ime_prezime)){
			array_push($greskeNiz, 'Niste pravilno uneli ime i prezime pacijenta');
		}else{
			include('konekcija.php');
			$link = new mysqli($hostname,$username,$password,$db)or die ("Greska");
			$sql = "SELECT ime,prezime FROM pacijent";
			$rezultat = $link->query($sql) or die ("Greska");

			$provera = False;
			if($rezultat->num_rows > 0){
				while($rez = $rezultat->fetch_assoc()){
					$_ime = $rez['ime'];
					$_prezime = $rez['prezime'];
					$_ime_prezime = $_ime . " " . $_prezime;
					if($_ime_prezime == $ime_prezime){
						$provera = True;
					}
				}
			}
			if(!$provera){
				array_push($greskeNiz, "Pacijent ne postoji u bazi");
			}
			$link->close() or die ("Greska");
		}

		

if(!isset($datum) || $datum == ''){
    
    array_push($greskeNiz, 'Niste izabrali datum intervencije');
  
  }else if(!preg_match("/^\d{4}\-\d{2}\-\d{2}$/", $datum)){
    
    array_push($greskeNiz, 'Pogrešno ste uneli datum intervencije');

  }

  if(!isset($vreme) || $vreme == ''){
  	array_push($greskeNiz, 'Niste izabrali vreme intervencije');
  }else if(!preg_match("/^\d{2}\:\d{2}$/",$vreme)){
  	array_push($greskeNiz,'Pogresno se uneli vreme intervencije');
  }

  if(!isset($vrsta_intervencije) || $vrsta_intervencije == ''){
  	array_push($greskeNiz, 'Niste upisali vrstu intervencije');
  }else if(!preg_match("/^[\w\s]*$/", $vrsta_intervencije)){
  	array_push($greskeNiz, 'Niste dobro uneli vrstu intervencije');
  }

  if(!isset($cena) || $cena == ''){
  	array_push($greskeNiz, 'Niste uneli cenu intervencije');
  }else if(!preg_match("/^\d+$/", $cena)){
  	array_push($greskeNiz, 'Niste dobro uneli cenu intervencije');
  }


  		if(empty($greskeNiz)){
  			$ime_pr = explode(" ", $ime_prezime);
  			$ime = $ime_pr[0];
  			$prezime = $ime_pr[1];
  			include('konekcija.php');
  			$link = new mysqli($hostname,$username,$password,$db)or die ('Greska');

		$sql = "SELECT id_pacijent FROM pacijent WHERE ime LIKE '$ime' AND prezime LIKE '$prezime'";

		$rezultat = $link->query($sql) or die ('Greska');
		$id_pac = $rezultat->fetch_assoc();
		$id_pacijent = $id_pac['id_pacijent'];

		$sql = "INSERT INTO intervencija(id_pacijent,id_doktor, datum,vreme,tip_intervencije,cena) VALUES('$id_pacijent','$id_doktor','$datum','$vreme','$vrsta_intervencije','$cena')";

echo $sql;	

		$rezultat = $link->query($sql) or die('Greska');

		$link->close() or die ("Greska");

		header("Location:login_pocetna.php?stranica=intervencije", true, 301);
  			exit();

  		}
  		else{
  			echo "<div id = 'greske'>";
  			foreach ($greskeNiz as $greska) {
  				echo $greska . "</br>";
  			}
  			echo "</div>";

  			echo "<form name = 'editovanje' id = 'editovanje_forma' method='POST' action = 'intervencije_dodavanje.php'>
				<table>
					<thead>
						<tr class = 'color-green'>
							<th>ime i prezime pacijenta</th>
							<th>datum intervencije</th>
							<th>vreme intervencije</th>
							<th>vrste intervencije</th>
							<th>cena</th>
						</tr>
					</thead>
					<tbody>
						<tr>";

						echo "<td>";
						echo "<select name = 'ime_prezime_pacijent'>";

						include('konekcija.php');
						$link = new mysqli($hostname,$username,$password,$db);
						$sql = "SELECT ime AS ime, prezime AS prezime FROM pacijent";

						$rezultat = $link->query($sql) or die ("Greska");
						if($rezultat->num_rows > 0){
							while($rez = $rezultat->fetch_assoc()){
								$ime = $rez['ime'];
								$prezime = $rez['prezime'];
								$ime_prezime_zajedno = $ime . ' ' . $prezime;
								echo "<option value = '$ime_prezime_zajedno'>$ime_prezime</option>";
							}
						}
						$link->close() or die ("Greska!");

						echo "</select>";
						echo "</td>";
							
						echo "<td><input type = 'date' name = 'datum'></td>
							<td><input type = 'year' name = 'vreme'></td>
							<td><input type = 'text' name = 'vrsta_intervencije'></td>
							<td><input type = 'text' name = 'cena'></td>
						</tr>
						<tr>
							<td colspan = '5'><input type = 'submit' id = 'edit_submit' value = 'dodaj'></td>
						</tr>
					</tbody>
				</table>
		
			</form>";	

  		}
}
	

						
}else{
	echo "Ulogujte se";
}
echo "</div>";

	include('futer.php');
	?>
</body>
</html>
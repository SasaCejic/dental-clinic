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

if($broj_licence != '' && $ime_prezime_doktora != '' && $ulogovan == 1){

	if($_POST){
		$prezime = $_POST['prezime'] ?? '';
		$ime = $_POST['ime'] ?? '';
		$broj_knjizice = $_POST['broj_knjizice'] ?? '';
		$pol = $_POST['pol'] ?? '';
		$godina_rodjenja = $_POST['godina_rodjenja'] ?? '';
		$bolesti = $_POST['bolesti'] ?? '';
		$id_pacijent_vrednost = $_POST['id_pacijent_vrednost'] ?? '';

		$greskeNiz = array();

		if(!isset($prezime) || $prezime == ''){
			array_push($greskeNiz, 'Morate uneti prezime');
		}else if(!preg_match("/^[a-zA-Z\s]{2,20}$/", $prezime)){
			array_push($greskeNiz, 'Niste pravilno uneli prezime');
		}

		if(!isset($ime) || $ime == ''){
			array_push($greskeNiz, 'Morate uneti ime');
		}else if(!preg_match("/^[a-zA-Z\s]{2,20}$/", $ime)){
			array_push($greskeNiz, 'Niste pravilno uneli ime');
		}

		if(!isset($broj_knjizice) || $broj_knjizice == ''){
    
   		array_push($greskeNiz, 'Morate uneti broj knjižice');
  
  		}else if(!preg_match("/^\d{11}$/", $broj_knjizice)){
    
    	array_push($greskeNiz, 'Niste pravilno uneli broj knjizice');

  		}

  		if(!isset($pol) || $pol==''){
  			array_push($greskeNiz,'Morate uneti pol pacijenta');
  		}else if($pol != 'M' && $pol != 'Z'){
  			array_push($greskeNiz, 'Niste dobro uneli pol pacijenta');
  		}

  		if(!isset($godina_rodjenja) || $godina_rodjenja==''){
  			array_push($greskeNiz,'Morate uneti godinu rodjenja');
  		}
  		else if(!preg_match("/^\d{4}$/", $godina_rodjenja)){
  			array_push($greskeNiz,'Niste pravilno uneli godinu rodjenja');
  		}
  		if($bolesti != '' && !preg_match("/^[\w\,\s\.]*$/", $bolesti)){
  			array_push($greskeNiz,'Niste pravilno uneli bolesti');
  		}

  		if(empty($greskeNiz)){

  			include('konekcija.php');
  			$link = new mysqli($hostname,$username,$password,$db)or die('Greska');

  			
  			$sql = "UPDATE pacijent SET ime='$ime',prezime='$prezime',broj_knjizice='$broj_knjizice',pol='$pol',godina_rodjenja='$godina_rodjenja',bolesti='$bolesti' WHERE id_pacijent LIKE $id_pacijent_vrednost";

  			$rezultat = $link->query($sql)or die ('Greska');

  			/*if($godina_rodjenja != '' && $bolesti != ''){
  				$sql = "UPDATE pacijent SET ime = '$ime',prezime = '$prezime',broj_knjizice = '$broj_knjizice', godina_rodjenja = '$godina_rodjenja', bolesti = '$bolesti' WHERE id_pacijent LIKE $id_pacijent_vrednost";
  			}else if($godina_rodjenja != '' && $bolesti == ''){
  				$sql = "UPDATE pacijent SET ime = '$ime',prezime = '$prezime',broj_knjizice = '$broj_knjizice', godina_rodjenja = '$godina_rodjenja' WHERE id_pacijent LIKE $id_pacijent_vrednost";
  			}else if($godina_rodjenja == '' && $bolesti != ''){
  				$sql = "UPDATE pacijent SET ime = '$ime',prezime = '$prezime',broj_knjizice = '$broj_knjizice', bolesti = '$bolesti' WHERE id_pacijent LIKE $id_pacijent_vrednost";
  			}else{
  				$sql = "UPDATE pacijent SET ime = '$ime',prezime = '$prezime',broj_knjizice = '$broj_knjizice' WHERE id_pacijent LIKE $id_pacijent_vrednost";
  			}*/
  			

  			$link->close() or die ('Greska');

  			header("Location:login_pocetna.php?stranica=pacijenti", true, 301);
  			exit();
  		}else{
  			echo "<div id = 'greske'>";
  				foreach ($greskeNiz as $greska) {
  					echo $greska . "</br>";
  				}
  			echo "</div>";

  			echo "<form name = 'editovanje' id = 'editovanje_forma' method='POST' action = 'editovanje_izmena.php'>
				<table>
					<thead>
						<tr class = 'color-green'>
							<th>prezime</th>
							<th>ime</th>
							<th>broj knjizice</th>
							<th>pol</th>
							<th>godina rodjenja</th>
							<th>bolesti</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><input type = 'text' name = 'prezime' value = ".$prezime."></td>
							<td><input type = 'text' name = 'ime' value = ".$ime."></td>
							<td><input type = 'text' name = 'broj_knjizice' value = ".$broj_knjizice."></td>
							<td><input type = 'text' name = 'pol' value = ".$pol."></td>
							<td><input type = 'year' name = 'godina_rodjenja' value = ".$godina_rodjenja."></td>
							<td><input type = 'text' name = 'bolesti' value = ".$bolesti."></td>
							<td><input type = 'hidden' name = 'id_pacijent_vrednost' value = $id_pacijent_vrednost></td>
						</tr>
						<tr>
							<td colspan = '5'><input type = 'submit' id = 'edit_submit' value = 'izmeni'></td>
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
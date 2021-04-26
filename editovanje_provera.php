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


	$id_pacijent_vrednost = $_POST['vrednost_id'] ?? '';

	if($_POST && isset($id_pacijent_vrednost) && $id_pacijent_vrednost != ''){
		
		include('konekcija.php');
		$link = new mysqli($hostname,$username,$password,$db)or die ('Greska');

		$sql = "SELECT * FROM pacijent WHERE id_pacijent LIKE $id_pacijent_vrednost";

		$rezultat = $link->query($sql) or die ('Greska');
		$red = $rezultat->fetch_assoc();



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
							<td><input type = 'text' name = 'prezime' value = ".$red['prezime']."></td>
							<td><input type = 'text' name = 'ime' value = ".$red['ime']."></td>
							<td><input type = 'text' name = 'broj_knjizice' value = ".$red['broj_knjizice']."></td>
							<td><input type = 'text' name = 'pol' value = ".$red['pol']."></td>
							<td><input type = 'year' name = 'godina_rodjenja' value = ".$red['godina_rodjenja']."></td>
							<td><input type = 'text' name = 'bolesti' value = ".$red['bolesti']."></td>
							<td><input type = 'hidden' name = 'id_pacijent_vrednost' value = $id_pacijent_vrednost></td>
						</tr>
						<tr>
							<td colspan = '5'><input type = 'submit' id = 'edit_submit' value = 'izmeni'></td>
						</tr>
					</tbody>
				</table>
		
			</form>";
		
	}

}else{
	echo "Ulogujte se";
}
echo "</div>";
include('futer.php');

?>

</body>
</html>
<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="script.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>
	<script src="grafici/utils.js"></script>
	<meta charset="utf-8">
	<title>Stomatolska ordinacija</title>
</head>
<body>
	
<?php 

$broj_licence = $_SESSION['broj_licence'] ?? '';
$ime_prezime = $_SESSION['ime_prezime'] ?? '';
$ulogovan = $_SESSION['ulogovan'] ?? '';

if($broj_licence!='' && $ime_prezime!='' && $ulogovan == 1){
	include("meni.php");

	
	$stranica = $_GET['stranica'] ?? '';
	
	echo "<div id = 'glavni_sadrzaj'>";

	switch($stranica){
		case '':
			include('pregledi.php');
			break;
		case 'intervencije':
			include('intervencije.php');
			break;
		case 'pacijenti':
			include('pacijenti.php');
			break;
		case 'statistika':
			include('statistika.php');
			break;
		default:
			echo "Greska 404! Ne postoji takva stranica";
			break;
	}

	echo "</div>";
	include('futer.php');
	
}else{
	echo "Ulogujte se";
} 
 
?>
</body>
</html>
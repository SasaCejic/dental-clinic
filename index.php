<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Stomatoloska ordinacija</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript">
		function start_login(){
			document.getElementById('forma_login').style.display = "block";
			var elem = document.getElementById("overlay");   
  			var pos = 0;
  			var max = screen.height;
  			var id = setInterval(frame,1);
  			function frame() {
    			if (pos == max || (pos>=(max-5) && pos<=(max+5))) {
      			clearInterval(id);
    			} else {
      			pos = pos + 5; 
      			elem.style.top = pos + 'px';  
    			}
  			}
		}

		function start_zakazivanje(){
			document.getElementById('forma_pregled').style.display = "block";
			var elem = document.getElementById("overlay");   
  			var pos = 0;
  			var max = screen.height;
  			var id = setInterval(frame,1);
  			function frame() {
    			if (pos == max || (pos>=(max-5) && pos<=(max+5))) {
      			clearInterval(id);
    			} else {
      			pos = pos + 5; 
      			elem.style.top = pos + 'px';  
    			}
  			}
		}


		
	</script>
</head>
<body>

	<div id="overlay">
		<div id="naziv">Stomatološka</br> ordinacija</div>
		<div id="holder-sekcija">
			<div class="levo">
				<p>Pacijenti</p>
				<input type="button" onclick="start_zakazivanje();" value="Zakaži pregled"/>
			</div>
			<div class="desno">
				<p>Doktori</p>
				<input type="button" onclick="start_login();" value="Uloguj se"/>
			</div>
		</div>
	</div>
	<form name="forma_login" id="forma_login" action = "login_provera.php" method="POST">
		<legend>Ulogujte se</legend>
		
			<input type="text" name="broj_licence" class="tekstualni_unos" placeholder="broj licence" /></br>
			<input type="password" name="lozinka" class="tekstualni_unos" placeholder="lozinka" /></br>
			<img src="captcha.php" width="120" height="30" border="1" alt="CAPTCHA"></br>
			<input type="text" size="6" maxlength="5" name="nasumicanBroj1" value=""></br>
			<small>unesite brojeve sa slike</small></br>
			
			<input type="submit" name="uloguj_se" id="uloguj_se" value="Uloguj se"/>
	</form>
	<?php include('meni_1.php'); ?>
	<form name="forma_pregled" id="forma_pregled" action = "zakazivanje.php" method="POST">
		<legend>Izaberite stomatologa i datum pregleda</legend>
			
			<table>
				<tr>
					<td><label for = "doktor">Stomatolog</label></td>

				
					<td>
					<select name="doktor" class="unos_pregled">
						<?php
						include('konekcija.php');

						$link = new mysqli($hostname, $username, $password, $db) or die ("Greska! Pokusajte ponovo.");
						$sql = "SELECT id_doktor,ime, prezime FROM doktor";
						$rezultat = $link->query($sql) or die ("Greska! Pokusajte ponovo.");
						
						echo "<option value=''>Izaberite stomatologa</option>";
						while($red = $rezultat->fetch_assoc()){
							
							
							echo "<option value='" . $red['id_doktor'] . "'> Dr. " . $red['ime'] . " " . $red['prezime'] . "</option>";
							
							
						}
						$link->close() or die("Greska! Pokusajte ponovo.");
						?>

					</select>
				</td>
			</tr>
			<tr>
					<td>
						<label for="datum_pregleda">Datum</label>
					</td>
					<td>
						<?php
						
						$min_datum_unix = strtotime('+1 day');
						$min_datum = date('Y-m-d',$min_datum_unix);

						$max_datum_unix = strtotime('+1 month');
						$max_datum = date('Y-m-d',$max_datum_unix);

						 ?>
						<input type="date" name="datum_pregleda" class="unos_pregled" <?php echo 'min = '.$min_datum. ' ' . 'max = ' . $max_datum; ?> />
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<img src="captcha.php" width="120" height="30" border="1" alt="CAPTCHA"></br>
						<input type="text" size="6" maxlength="5" name="nasumicanBroj2" value=""></br>
						<small>unesite brojeve sa slike</small></br>
					</td>
				</tr>
				<tr>

					<td colspan="2">
					
					<input type="submit" name="dalje" id="dalje" value="Dalje"/>

				</td>
			</tr>

			</table>
					
	</form>

</body>
</html>
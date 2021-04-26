

<?php

session_start();

function povecajVreme($vreme){
	$vremeTemp  = explode(":", $vreme);
	$sat = (int)$vremeTemp[0];
	$minut = (int)$vremeTemp[1];

	if($sat>=9 && $sat<=16){
		if($minut!=45){
			$minut += 15;
		}else{
			$minut = '00';
			$sat++;
		}
	}
	if($sat==9){
		$vreme = '0' . $sat . ":" . $minut;
	}else{
		$vreme = $sat . ":" . $minut;
	}
	
	return $vreme;
}



$doktor = (int)$_POST['doktor'];
$datum_pregleda = $_POST['datum_pregleda'];
$nasumicanBroj = $_POST['nasumicanBroj2'];

    

 $greskeNiz = array();
      
    
 if(!isset($doktor) || empty($doktor)){
    array_push($greskeNiz, 'Niste izabrali doktora');
  }else if(!preg_match("/^\d+$/",$doktor)){
    array_push($greskeNiz, 'Pogrešno ste uneli ime doktora');
  }else{
  	include('konekcija.php');

  	$link = new mysqli($hostname, $username, $password, $db)or die("Greska");

  	$sql = "SELECT ime FROM doktor WHERE id_doktor LIKE '$doktor'";

  	$izvrsavanje = $link->query($sql)or die ("Greska");

  	$rezultat = (int)$izvrsavanje->fetch_assoc();

  	if(!$rezultat){
  		array_push($greskeNiz, 'Izabrani doktor ne postoji');
  	}

  	$link->close();
  }

            

            $min_datum_unix = strtotime('+1 day');
			$min_datum = date('Y-m-d',$min_datum_unix);

			$max_datum_unix = strtotime('+1 month');
			$max_datum = date('Y-m-d',$max_datum_unix);



if(!isset($datum_pregleda) || empty($datum_pregleda)){
    
    array_push($greskeNiz, 'Niste uneli datum pregleda');
  
  }else if(!preg_match("/^\d{4}\-\d{2}\-\d{2}$/", $datum_pregleda)){
    
    array_push($greskeNiz, 'Pogrešno ste uneli datum pregleda');

  }else if(!($datum_pregleda>=$min_datum) || !($datum_pregleda<=$max_datum)){
    array_push($greskeNiz, 'Izabrani datum pregleda nije validan');
  }

  if(!isset($nasumicanBroj) || empty($nasumicanBroj)){
  	array_push($greskeNiz, 'Niste uneli broj sa slike');
  }else if(!preg_match("/^\d{5}$/",$nasumicanBroj) || $nasumicanBroj!=$_SESSION['nasumicanBroj']){
  	array_push($greskeNiz, 'Pogrešno ste uneli broj sa slike');
  }

//Ime i prezime doktora

            if(isset($doktor) && !empty($doktor) && preg_match("/^\d+$/", $doktor)){
            	include('konekcija.php');

  				$link = new mysqli($hostname, $username, $password, $db)or die("Greska");
				$sql = "SELECT id_doktor FROM doktor WHERE id_doktor LIKE $doktor";
				$rez = $link->query($sql)or die ("Greska");
				
				if($r = $rez->fetch_assoc()){
					$sql = "SELECT ime FROM doktor WHERE id_doktor LIKE $doktor";
					$rezultat = $link->query($sql)or die("Greska");
					$doktorI = $rezultat->fetch_assoc(); 
					$doktorIme = $doktorI['ime'];

					$sql = "SELECT prezime FROM doktor WHERE id_doktor LIKE $doktor";
					$rezultat = $link->query($sql)or die("Greska");
					$doktorP = $rezultat->fetch_assoc();
					$doktorPrezime = $doktorP['prezime'];
				}
				$link->close()or die("Greska");
			}


if(empty($greskeNiz)){

	include('konekcija.php');

	$link = new mysqli($hostname, $username, $password, $db)or die("Greska");

	$sql = "SELECT vreme FROM pregled WHERE id_doktor LIKE $doktor AND datum LIKE '$datum_pregleda'";



	$rezultat = $link->query($sql)or die ("Greska");

	$zakazanoVreme = array();

							
								while($vreme = $rezultat->fetch_assoc()){
									array_push($zakazanoVreme,$vreme);
								}
							

							$slobodnoVreme = array();

							$time = '09:00';

							for($i=0;$i<32;$i++){
								

								$provera = false;

								foreach ($zakazanoVreme as $zakazano) {
									if($zakazano['vreme']==$time){
										$provera = true;
										break;
									}
								}

								if(!$provera){
									array_push($slobodnoVreme, $time);
								}

								$time = povecajVreme($time);

							}



	if(empty($slobodnoVreme)){
		session_destroy();
		echo "
<html>
<head>
	<title>Stomatoloska ordinacija</title>
	<link rel='stylesheet' type='text/css' href='style.css'>
</head>
<body>";
		
		include('meni_1.php');

	echo"	<div id='greske' style='font-size:30px; color:red;'>
      			Za birani datum nema slobodnih termina</br>
      			

      	</div>

      	<form name='forma_pregled' id='forma_pregled' style = 'display:block; margin-top:100px;' action = 'zakazivanje.php' method='POST'>
		<legend>Izaberite stomatologa i datum pregleda</legend>
			
			<table>
				<tr>
					<td><label for = 'doktor'>Stomatolog</label></td>

				
					<td>
					<select name='doktor' class='unos_pregled' >";

						include('konekcija.php');

						$link = new mysqli($hostname, $username, $password, $db) or die ("Greska! Pokusajte ponovo.");
						$sql = "SELECT id_doktor,ime, prezime FROM doktor";
						$rezultat = $link->query($sql) or die ("Greska! Pokusajte ponovo.");
						//$red = $rezultat->fetch_assoc();
						
						if(isset($doktor) && !empty($doktor) && preg_match("/^\d+$/", $doktor)){
									$sql_2 = "SELECT id_doktor FROM doktor WHERE id_doktor LIKE $doktor";
									$rez = $link->query($sql_2)or die ("Greska");
									if($r = $rez->fetch_assoc()){
										echo "<option value='" . $doktor . "'>Dr. " . $doktorIme . " " . $doktorPrezime . "</option>";
									}else{
										echo "<option value=''>izaberite stomatologa</option>";
									}
						}else{
									echo "<option value=''>izaberite stomatologa</option>";
								}

						while($red = $rezultat->fetch_assoc()){

							
							if(isset($doktor) && !empty($doktor) && preg_match("/^\d+$/", $doktor)){
							
							 if($doktor != $red['id_doktor']){
							 	echo "<option value='" . $red['id_doktor'] . "'> Dr. " . $red['ime'] . " " . $red['prezime'] . "</option>";
							}
						}else{
							echo "<option value='" . $red['id_doktor'] . "'> Dr. " . $red['ime'] . " " . $red['prezime'] . "</option>";
						}
							
							
							
						}
						$link->close() or die("Greska! Pokusajte ponovo.");

echo "</select>
				</td>
			</tr>
			<tr>
					<td>
						<label for='datum_pregleda'>Datum</label>
					</td>
					<td>";
						$min_datum_unix = strtotime('+1 day');
						$min_datum = date('Y-m-d',$min_datum_unix);

						$max_datum_unix = strtotime('+1 month');
						$max_datum = date('Y-m-d',$max_datum_unix);

echo "<input type='date' name='datum_pregleda' class='unos_pregled' min = " .$min_datum . " max = " . $max_datum."/>
					</td>
				</tr>
				<tr>
					<td colspan = '2'>
						<img src='captcha.php' width='120' height='30' border='1' alt='CAPTCHA'></br>
						<input type='text' size='6' maxlength='5' name='nasumicanBroj2' value=''></br>
						<small>unesite brojeve sa slike</small></br>
					</td>
				</tr>
				<tr>
				<td colspan='2'>
					
					<input type='submit' name='dalje' id='dalje' value='Dalje'/>

				</td>
			</tr>

			</table>
					
	</form>

  	

 </body>
</html>";
      $link->close()or die ("Greska");
	}else{

    
	echo "
<html>
<head>
	<title>Stomatoloska ordinacija</title>
	<link rel='stylesheet' type='text/css' href='style.css'>
</head>
<body>";

	include('meni_1.php');

	echo "<form name = 'forma_zakazivanje' id = 'forma_zakazivanje' action = 'zakazivanje_provera.php' method = 'POST'>


			<legend>Zakazivanje pregleda</legend>

			<table>

					<tr>

						<td><label for = 'ime'>Ime</label></td>
						<td><input type='text' name='ime' class='unos_pregled' /></td>

					</tr>
					<tr>
					<td>
						<label for='prezime'>Prezime:</label>
					</td>
					<td>
					<input type='text' name='prezime' class='unos_pregled'  />
					</td>
				</tr>
				<tr>
					<td>
						<label for='broj_knjizice'>Broj knjižice:</label>
					</td>
					<td>
					<input type='text' name='broj_knjizice' class='unos_pregled'  />
					</td>
				</tr>
				<tr>
					<td>
						<label for='godina_rojenja'>Godina rođenja:</label>
					</td>
					<td>
						<select name = 'godina_rodjenja' class = 'unos_pregled'>
							<option>Izaberite godinu rodjenja</option>";
							$min = strtotime('-95 years');
              				$minimalna_godina = date('Y',$min);
							$tekuca_godina = date('Y');
							for($i = $minimalna_godina; $i<=$tekuca_godina-2; $i++){
								echo "<option value='$i'>$i</option>";
							}
				echo	"</select>
					</td>
				</tr>
				<tr>
					<td>
						<label for = 'pol'>Pol:</label>
					</td>
					<td>
						M<input type = 'radio' name='pol' value = 'M'>
						Ž<input type = 'radio' name = 'pol' value = 'Z'>
					</td>
				</tr>
				<tr>
					<td><label for = 'vreme_pregleda'>Vreme pregleda</label>
					<td>
						
						<select name = 'vreme_pregleda' class = 'unos_pregled'>";
						

							foreach ($slobodnoVreme as $slobodno) {
								echo "<option value = '" . $slobodno . "'>" . $slobodno . "</option>";
							}


							$link->close()or die("Greska");


	echo"					</select>
						</td>
						</tr>
						<tr>


							<td colspan = '2'><input type = 'submit' name = 'zakazi_pregled' id = 'zakazi_pregled' value = 'Zakaži pregled'></td>
						</tr>

				</table>


	<input type = 'text' name = 'doktor' id = 'doktor' class='invisible' value= $doktor />
	<input type = 'text' name = 'datum_pregleda' id = 'datum_pregleda' class = 'invisible' value= $datum_pregleda />
					



		  </form>

</body>
</html>";
      
    }
      
    
  }else{
  	session_destroy();
    
    echo "
<html>
<head>
	<title>Stomatoloska ordinacija</title>
	<link rel='stylesheet' type='text/css' href='style.css'>
</head>
<body>";

	include('meni_1.php');

  echo "  <div id='greske' style='font-size:30px; color:red;'>";
      			foreach($greskeNiz as $greska){
      				echo $greska . "</br>";
      			}
     echo "</div>";

 echo "    <form name='forma_pregled' id='forma_pregled' style = 'display:block; margin-top:100px;' action = 'zakazivanje.php' method='POST'>
		<legend>Izaberite stomatologa i datum pregleda</legend>
			
			<table>
				<tr>
					<td><label for = 'doktor'>Stomatolog</label></td>

				
					<td>
					<select name='doktor' class='unos_pregled' >";

						include('konekcija.php');

						$link = new mysqli($hostname, $username, $password, $db) or die ("Greska! Pokusajte ponovo.");
						$sql = "SELECT id_doktor,ime, prezime FROM doktor";
						$rezultat = $link->query($sql) or die ("Greska! Pokusajte ponovo.");
						//$red = $rezultat->fetch_assoc();
						
						if(isset($doktor) && !empty($doktor) && preg_match("/^\d+$/", $doktor)){
									$sql_2 = "SELECT id_doktor FROM doktor WHERE id_doktor LIKE $doktor";
									$rez = $link->query($sql_2)or die ("Greska");
									if($r = $rez->fetch_assoc()){
										echo "<option value='" . $doktor . "'>Dr. " . $doktorIme . " " . $doktorPrezime . "</option>";
									}else{
										
										echo "<option value=''>izaberite stomatologa</option>";
									}
								}else{
									
									echo "<option value=''>izaberite stomatologa</option>";
								}

						while($red = $rezultat->fetch_assoc()){

							if(isset($doktor) && !empty($doktor) && preg_match("/^\d+$/", $doktor)){
							
							 if($doktor != $red['id_doktor']){
							 	echo "<option value='" . $red['id_doktor'] . "'> Dr. " . $red['ime'] . " " . $red['prezime'] . "</option>";
							}

						}else{
							echo "<option value='" . $red['id_doktor'] . "'> Dr. " . $red['ime'] . " " . $red['prezime'] . "</option>";
						}
							
							
							
						}
						$link->close() or die("Greska! Pokusajte ponovo.");

echo "</select>
				</td>
			</tr>
			<tr>
					<td>
						<label for='datum_pregleda'>Datum</label>
					</td>
					<td>";
						$min_datum_unix = strtotime('+1 day');
						$min_datum = date('Y-m-d',$min_datum_unix);

						$max_datum_unix = strtotime('+1 month');
						$max_datum = date('Y-m-d',$max_datum_unix);

$vrednost_datuma = $datum_pregleda ?? '';

echo "<input type='date' name='datum_pregleda' class='unos_pregled' min = '" .$min_datum . "' max = " . $max_datum . " value = $vrednost_datuma >"; 

	echo "</td>
				</tr>
				<tr>
					<td colspan = '2'>
						<img src='captcha.php' width='120' height='30' border='1' alt='CAPTCHA'></br>
						<input type='text' size='6' maxlength='5' name='nasumicanBroj2' value=''></br>
						<small>unesite brojeve sa slike</small></br>
					</td>
				</tr>
				<tr>

					<td colspan='2'>
					
					<input type='submit' name='dalje' id='dalje' value='Dalje'/>

				</td>
			</tr>

			</table>
					
	</form>";


  echo "

</body>
</html>";

      
    
  }


?>

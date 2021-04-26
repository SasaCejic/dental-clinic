
	<?php

		session_start();
		
		$broj_licence = $_POST['broj_licence'];
    
    	$lozinka = $_POST['lozinka'];

    	$nasumicanBroj = $_POST['nasumicanBroj1'];

    	$greskeNiz = array();
    


    if(!isset($broj_licence) || empty($broj_licence)){
    
    	array_push($greskeNiz, 'Niste uneli broj licence');
    
    
  }
  else if(!preg_match("/^\d{6}$/",$broj_licence)){
    
    array_push($greskeNiz, 'Pogrešno ste uneli broj licence');
    
  }

  

  if(!isset($lozinka) || empty($lozinka)){
   
    
    array_push($greskeNiz, 'Niste uneli lozinku');
    
     
  }
  else if(!preg_match("/^[a-zA-Z\d]{8,20}$/",$lozinka)){
    
    array_push($greskeNiz, 'Pogrešno ste uneli lozinku');
    
  }

  if(!isset($nasumicanBroj) || empty($nasumicanBroj)){
  	array_push($greskeNiz, 'Niste uneli broj sa slike');
  }else if(!preg_match("/^\d{5}$/",$nasumicanBroj) || $nasumicanBroj!=$_SESSION['nasumicanBroj']){
  	array_push($greskeNiz, 'Pogrešno ste uneli broj sa slike');
  }

  


  if(empty($greskeNiz)){

	// include('konekcija.php');
	$hostname = 'localhost';
$username = 'root';
$password = '';
$db = 'stomatoloska_ordinacija';

    $link = new mysqli('localhost:3307','root','','stomatoloska_ordinacija') or die ('Greska! Pokusajte ponovo!');

    
    $sql = "SELECT id_doktor FROM doktor WHERE broj_licence LIKE '$broj_licence' AND lozinka LIKE '$lozinka'";
    $rezultat = $link->query($sql) or die ('Greska');

    $id_doktor = $rezultat->fetch_assoc();


    if($id_doktor){

    	$sql = "SELECT prezime,ime FROM doktor WHERE broj_licence LIKE '$broj_licence' AND lozinka LIKE '$lozinka'";
    	$rezultat = $link->query($sql)or die ("Greska");
    	$rez = $rezultat->fetch_assoc();
    	$ime = $rez['ime'];
    	$prezime = $rez['prezime'];
    	$ime_prezime_doktora = $ime . " " . $prezime;

    	$_SESSION['broj_licence'] = $broj_licence;
    	$_SESSION['ime_prezime'] = $ime_prezime_doktora;
    	$_SESSION['ulogovan'] = 1;
      
    	header("Location: login_pocetna.php", true, 301);
		exit();
      
    }else{
    	session_destroy();
      echo "
      <html>
<head>
	<meta charset='utf-8'>
	<title>Stomatoloska ordinacija</title>
	<link rel='stylesheet' type='text/css' href='style.css'>
</head>
<body>";
	include('meni_1.php');
     
     echo  "<div id='greske'>
      		Pogrešno ste uneli podatke</br>
      </div>

      		<form name='forma_login' id='forma_login' style = 'margin-top:100px; display:block;' action = 'login_provera.php' method='POST'>
			<legend>Ulogujte se</legend>
		
			<input type='text' name='broj_licence' class='tekstualni_unos' placeholder='broj licence' value='";
			echo $broj_licence ?? '';
			 echo"'/></br>
			<input type='password' name='lozinka' class='tekstualni_unos' placeholder='lozinka' value='";
			echo $lozinka ?? '';
			echo "'/></br>
			<img src='captcha.php' width='120' height='30' border='1' alt='CAPTCHA'></br>
			<input type='text' size='6' maxlength='5' name='nasumicanBroj1' value=''></br>
			<small>unesite brojeve sa slike</small></br>
			<input type='submit' name='uloguj_se' id='uloguj_se' value='Uloguj se'/>
	</form>

</body>
</html>";
    }

    $link->close()or die ('Greska!');

  }else{
  	session_destroy();
    echo "
      <html>
<head>
	<meta charset='utf-8'>
	<title>Stomatoloska ordinacija</title>
	<link rel='stylesheet' type='text/css' href='style.css'>
</head>
<body>";

	include('meni_1.php');

     echo" <div id='greske'>";

      	foreach($greskeNiz as $greska){
      		echo $greska . "</br>";
      	}
      		
 echo  "</div>

      		<form name='forma_login' id='forma_login' style = 'margin-top:100px; display:block;' action = 'login_provera.php' method='POST'>
			<legend>Ulogujte se</legend>
		
			<input type='text' name='broj_licence' class='tekstualni_unos' placeholder='broj licence' value='";
			echo $broj_licence ?? '';
			 echo"'/></br>
			<input type='password' name='lozinka' class='tekstualni_unos' placeholder='lozinka' value='";
			echo $lozinka ?? '';
			echo "'/></br>
			<img src='captcha.php' width='120' height='30' border='1' alt='CAPTCHA'></br>
			<input type='text' size='6' maxlength='5' name='nasumicanBroj1' value=''></br>
			<small>unesite brojeve sa slike</small></br>
			<input type='submit' name='uloguj_se' id='uloguj_se' value='Uloguj se'/>
	</form>

</body>
</html>";
  }


	?>

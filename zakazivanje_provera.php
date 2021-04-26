
	<?php

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

		
		$ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $broj_knjizice = $_POST['broj_knjizice'];
    $godina_rodjenja = $_POST['godina_rodjenja'];
    $pol = $_POST['pol'] ?? '';
    $doktor = (int)$_POST['doktor'];
    $datum_pregleda = $_POST['datum_pregleda']; // yyyy-mm-dd
    $vreme_pregleda = $_POST['vreme_pregleda']; //hh:mm

    

    	$greskeNiz = array();
      $pogresanTermin = false;
    
    

  if(!isset($ime) || empty($ime)){
    
    array_push($greskeNiz, 'Niste uneli svoje ime');
  
  }else if(!preg_match("/^[a-zA-Z\s]{2,20}$/", $ime)){
    
    array_push($greskeNiz, 'Pogrešno ste uneli svoje ime');

  }

  if(!isset($prezime) || empty($prezime)){
    
    array_push($greskeNiz, 'Niste uneli svoje prezime');
  
  }else if(!preg_match("/^[a-zA-Z\s]{2,20}$/", $prezime)){
    
    array_push($greskeNiz, 'Pogrešno ste uneli svoje prezime');

  }

  if(!isset($broj_knjizice) || empty($broj_knjizice)){
    
    array_push($greskeNiz, 'Niste uneli broj knjižice');
  
  }else if(!preg_match("/^\d{11}$/", $broj_knjizice)){
    
    array_push($greskeNiz, 'Pogrešno ste uneli broj knjižice');

  }

  $min_godina_rodjenja_unix = strtotime('-95 years');
  $min_godina_rodjenja = date('Y',$min_godina_rodjenja_unix);

  $max_godina_rodjenja_unix = strtotime('-2 years');
  $max_godina_rodjenja = date('Y',$max_godina_rodjenja_unix);

  if(!isset($godina_rodjenja) || empty($godina_rodjenja) || $godina_rodjenja=='Izaberite godinu rodjenja'){
    array_push($greskeNiz, 'Niste uneli godinu rodjenja');
  }else if(!preg_match("/^\d{4}$/", $godina_rodjenja)){
    array_push($greskeNiz, 'Niste ispravno uneli godinu rodjenja');
  }else{
    if(!($godina_rodjenja>=$min_godina_rodjenja) || !($godina_rodjenja<=$max_godina_rodjenja)){
      array_push($greskeNiz, 'Uneta godina rodjenja nije validna');
    }
  }

  if(!isset($pol) || empty($pol)){
    array_push($greskeNiz, 'Niste uneli pol');
  }else if($pol != 'M' && $pol != 'Z'){
    array_push($greskeNiz, 'Niste ispravno uneli pol');
  }

  
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
    
    array_push($greskeNiz, 'Niste izabrali datum pregleda');
  
  }else if(!preg_match("/^\d{4}\-\d{2}\-\d{2}$/", $datum_pregleda)){
    
    array_push($greskeNiz, 'Pogrešno ste uneli datum pregleda');

  }else if(!($datum_pregleda>=$min_datum) || !($datum_pregleda<=$max_datum)){
      array_push($greskeNiz, 'Izabrani datum pregleda nije validan');
  }




// PROVERA VREMENA PREGLEDA

            if(!isset($vreme_pregleda) || empty($vreme_pregleda)){
              
              array_push($greskeNiz,'Niste uneli vreme pregleda');

            }else if(!preg_match("/^\d{2}\:\d{2}$/", $vreme_pregleda)){

              array_push($greskeNiz,'Pogrešno ste uneli vreme pregleda');

            }else{

              if(!($vreme_pregleda>='09:00' && $vreme_pregleda<='16:45')){

                array_push($greskeNiz,'Izabrano vreme pregleda nije validno');

              }else{

                if(empty($greskeNiz)){
                  include('konekcija.php');

                  $link = new mysqli($hostname,$username,$password,$db) or die ('Greska! Pokusajte ponovo!');

    
                  $sql = "SELECT id_pregled FROM pregled WHERE  id_doktor LIKE '$doktor' AND datum LIKE '$datum_pregleda' AND vreme LIKE '$vreme_pregleda'";
                  $rezultat = $link->query($sql) or die ('Greska');

                  $id_pregled = (int)$rezultat->fetch_assoc();

                 

                    if($id_pregled){
                      array_push($greskeNiz, 'Izabrani termin je zauzet');
                      $pogresanTermin = true;

                      echo "
                      <html>
<head>
  <title>Stomatoloska ordinacija</title>
  <link rel='stylesheet' type='text/css' href='style.css'>
</head>
<body>
      <div id='greske'>
         Termin koji tražite nije slobodan.</br>
                      
     </div>
     <form name = 'forma_zakazivanje' id = 'forma_zakazivanje' style='margin-top:50px;' action = 'zakazivanje_provera.php' method = 'POST'>


      <legend>Zakazivanje pregleda</legend>

      <table>

          <tr>

            <td><label for = 'ime'>Ime</label></td>
            <td><input type='text' name='ime' class='unos_pregled' value ='";

  echo $ime ?? '';


    echo "' /></td>

          </tr>
          <tr>
          <td>
            <label for='prezime'>Prezime:</label>
          </td>
          <td>
          <input type='text' name='prezime' class='unos_pregled' value = '";

echo $prezime ?? '';

echo  "'/>
          </td>
        </tr>
        <tr>
          <td>
            <label for='broj_knjizice'>Broj knjižice:</label>
          </td>
          <td>
          <input type='text' name='broj_knjizice' class='unos_pregled' value ='";

echo $broj_knjizice ?? '';


  echo "'/>
          </td>
        </tr>
        <tr>
          <td>
            <label for='godina_rojenja'>Godina rođenja:</label>
          </td>
          <td>
            <select name = 'godina_rodjenja' class = 'unos_pregled' value = '";
            echo $godina_rodjenja ?? '';
            echo "'>
              <option>Izaberite godinu rodjenja</option>";
              $min = strtotime('-95 years');
              $minimalna_godina = date('Y',$min);
              $tekuca_godina = date('Y');
              for($i = $minimalna_godina; $i<=$tekuca_godina-2; $i++){
                echo "<option value='$i'>$i</option>";
              }
        echo  "</select>
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


  echo"         </select>
            </td>
            </tr>
            <tr>


              <td colspan = '2'><input type = 'submit' name = 'zakazi_pregled' id = 'zakazi_pregled' value = 'Zakaži pregled'></td>
            </tr>

        </table>


  <input type = 'text' name = 'doktor' id = 'doktor' class='invisible' value='";
  echo  $doktor ?? '';
  echo "'/>
  <input type = 'text' name = 'datum_pregleda' id = 'datum_pregleda' class = 'invisible' value='";

  echo  $datum_pregleda ?? '';


  echo "'/>
          
        </form>

</body>
</html>";

                    }
                  


                  $link->close() or die("Greska!");
                }
                
              }
            }



  


  if(empty($greskeNiz)){

    include('konekcija.php');

    $link = new mysqli($hostname,$username,$password,$db) or die ('Greska! Pokusajte ponovo!');

    
    $sql = "SELECT id_pacijent FROM pacijent WHERE  ime LIKE '$ime' AND prezime LIKE '$prezime' AND broj_knjizice LIKE '$broj_knjizice'";
    $rezultat = $link->query($sql) or die ('Greska');

    $id_pacijent = (int)$rezultat->fetch_assoc();


    if(!$id_pacijent){

      $sql = "INSERT INTO pacijent(ime, prezime, broj_knjizice,godina_rodjenja,pol) VALUES('$ime', '$prezime', '$broj_knjizice',$godina_rodjenja,'$pol')";
      $rezultat = $link->query($sql) or die ('Greska');

      

      //------------------------------------------



      $sql = "SELECT id_pacijent FROM pacijent WHERE  ime LIKE '$ime' AND prezime LIKE '$prezime' AND broj_knjizice LIKE '$broj_knjizice'";
    $rezultat = $link->query($sql) or die ('Greska');

    $id = $rezultat->fetch_assoc();

    //$id_pacijent = $id['id_pacijent'];


    $sql = "INSERT INTO pregled(id_doktor , id_pacijent, datum, vreme) VALUES('$doktor'," . $id['id_pacijent'] . ", '$datum_pregleda', '$vreme_pregleda')";
    $rezultat = $link->query($sql) or die ('Greska');



  $sql = "SELECT vreme FROM pregled WHERE id_doktor LIKE $doktor AND datum LIKE '$datum_pregleda'";
  //$sql = "SELECT vreme from "



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


    $link->close()or die ('Greska!');

      
    	echo "

<html>
<head>
  <title>Stomatoloska ordinacija</title>
  <link rel='stylesheet' type='text/css' href='style.css'>
</head>
<body>";
  include('meni_1.php');
  echo "<div id='obavestenje' style='font-size:30px; color:green;'>
            Uspešno ste zakazali pregled</br>
            <a href='index.php'>početna strana</a>
      </div>

</body>
</html>";
      
    }else{

      $sql = "SELECT id_pacijent FROM pacijent WHERE  ime LIKE '$ime' AND prezime LIKE '$prezime' AND broj_knjizice LIKE '$broj_knjizice'";
    $rezultat = $link->query($sql) or die ('Greska');

    $id = $rezultat->fetch_assoc();
    /*
    $sql = "SELECT id_doktor FROM doktor WHERE ime LIKE '$doktorIme' AND prezime LIKE '$doktorPrezime'";
    $rezultat = $link->query($sql) or die ('Greska');

    $id_doktor = (int)$rezultat->fetch_assoc();*/


    $sql = "INSERT INTO pregled(id_doktor , id_pacijent, datum, vreme) VALUES('$doktor', " . $id['id_pacijent'] . ", '$datum_pregleda', '$vreme_pregleda')";
    $rezultat = $link->query($sql) or die ('Greska');


    $link->close()or die ('Greska!');

      echo "
<html>
<head>
  <title>Stomatoloska ordinacija</title>
  <link rel='stylesheet' type='text/css' href='style.css'>
</head>
<body>";
include('meni_1.php');
   echo "<div id='obavestenje' style='font-size:30px; color:green;'>
            Uspešno ste zakazali pregled</br>
            <a href='index.php'>početna strana</a>
      </div>
</body>
</html>";
    }

  
    

  }else{
    if(!$pogresanTermin){

  include('konekcija.php');

  $link = new mysqli($hostname, $username, $password, $db)or die("Greska");

  $sql = "SELECT vreme FROM pregled WHERE id_doktor LIKE $doktor AND datum LIKE '$datum_pregleda'";
  //$sql = "SELECT vreme from "



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

    $link->close()or die ("Greska");

    echo "
<html>
<head>
  <title>Stomatoloska ordinacija</title>
  <link rel='stylesheet' type='text/css' href='style.css'>
</head>
<body>";

include('meni_1.php');

 echo "     <div id='greske'>";
        foreach ($greskeNiz as $greska) {
          echo $greska . "</br>";
        }
                      
echo "     </div>
     <form name = 'forma_zakazivanje' id = 'forma_zakazivanje' style='margin-top:50px;' action = 'zakazivanje_provera.php' method = 'POST'>


      <legend>Zakazivanje pregleda</legend>

      <table>

          <tr>

            <td><label for = 'ime'>Ime</label></td>
            <td><input type='text' name='ime' class='unos_pregled' value ='";

  echo $ime ?? '';


    echo "'/></td>

          </tr>
          <tr>
          <td>
            <label for='prezime'>Prezime:</label>
          </td>
          <td>
          <input type='text' name='prezime' class='unos_pregled' value = '";

echo $prezime ?? '';

echo  "'/>
          </td>
        </tr>
        <tr>
          <td>
            <label for='broj_knjizice'>Broj knjižice:</label>
          </td>
          <td>
          <input type='text' name='broj_knjizice' class='unos_pregled' value ='";

echo $broj_knjizice ?? '';


  echo "'/>
          </td>
        </tr>
        <tr>
          <td>
            <label for='godina_rojenja'>Godina rođenja:</label>
          </td>
          <td>
            <select name = 'godina_rodjenja' class = 'unos_pregled' value = '";
            echo $godina_rodjenja ?? '';
            echo "'>
              <option>Izaberite godinu rodjenja</option>";
              $min = strtotime('-95 years');
              $minimalna_godina = date('Y',$min);
              $tekuca_godina = date('Y');
              for($i = $minimalna_godina; $i<=$tekuca_godina-2; $i++){
                echo "<option value='$i'>$i</option>";
              }
        echo  "</select>
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
            
              if(isset($vreme_pregleda) && !empty($vreme_pregleda) && preg_match("/^\d{2}\:\d{2}$/", $vreme_pregleda) && $vreme_pregleda>='09:00' && $vreme_pregleda<='16:45'){
                    echo "<option value=" . $vreme_pregleda . ">".$vreme_pregleda."</option>";
              }

              foreach ($slobodnoVreme as $slobodno) {
                if(isset($vreme_pregleda) && !empty($vreme_pregleda) && preg_match("/^\d{2}\:\d{2}$/", $vreme_pregleda) && $vreme_pregleda>='09:00' && $vreme_pregleda<='16:45'){

                    if($vreme_pregleda != $slobodno){
                      echo "<option value = '" . $slobodno . "'>" . $slobodno . "</option>";
                    }

                }else{
                  echo "<option value = '" . $slobodno . "'>" . $slobodno . "</option>";
                }
                
              }


              


  echo"         </select>
            </td>
            </tr>
            <tr>


              <td colspan = '2'>
              <input type = 'submit' name = 'zakazi_pregled' id = 'zakazi_pregled' value = 'Zakaži pregled'></td>
            </tr>

        </table>


  <input type = 'text' name = 'doktor' id = 'doktor' class='invisible' value='";
  echo  $doktor ?? '';
  echo "'/>
  <input type = 'text' name = 'datum_pregleda' id = 'datum_pregleda' class = 'invisible' value='";

  echo  $datum_pregleda ?? '';


  echo "'/>
          
        </form>

</body>
</html>";
    }
  }


	?>

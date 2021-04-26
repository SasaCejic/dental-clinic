
<?php
include('konekcija.php');
$konekcija = new mysqli($hostname,$username,$password,$db) or die("Greska!");

?>
<div class = "statistika_segment">
	<h2>Najčešće intervencije po polu</h2>
<?php 
	  echo "<div class='levo'>";
	  	include('grafici/intervencije_po_polu/muskarci.php');
	  echo "</div>";
	 
	 echo "<div class='desno'>";
	  	include('grafici/intervencije_po_polu/zene.php');
	  echo "</div>";
	  
 ?>
 <div class="cistac"></div>
</div>
<div class = "statistika_segment">
	<h2>Najčešće intervencije po starosnoj grupi</h2>
<?php
	include('grafici/intervencije_po_starosnoj_grupi.php');
?>
</div>

<div class = "statistika_segment">
	<h2>Zarada</h2>
<?php
	include('grafici/zarada/zarada_godinu_dana.php');
?>
</div>

<?php

$konekcija->close() or die("Greska!");

?>
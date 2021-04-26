<?php

	

function daLiJeAktivna($strana){
	$trenutna_stranica = $_GET['stranica'] ?? 'pregledi';

	if($trenutna_stranica == $strana){
		echo "class = 'aktivno'";
	}
}

include('konekcija.php');
?>

<div id = "meni">
	<div id = "ime_prezime">
	<?php
		echo "<p>Dr. " .$_SESSION['ime_prezime'] . "</p>";
	?>
	</div>
	<ul>
		<li>
			<a <?php daLiJeAktivna('pregledi'); ?>href="login_pocetna.php">Pregledi</a>
		</li>
		<li>
			<a <?php daLiJeAktivna('intervencije');?> href="./login_pocetna.php?stranica=intervencije">Intervencije</a>
		</li>
		<li>
			<a <?php daLiJeAktivna('pacijenti') ?> href="./login_pocetna.php?stranica=pacijenti">Pacijenti</a>
		</li>
		<li>
			<a <?php daLiJeAktivna('statistika'); ?> href = './login_pocetna.php?stranica=statistika'>Statistika</a>
		</li>
		<li><a class="izloguj_se" href="izloguj_se.php">Izloguj se</a></li>
	</ul>
</div>
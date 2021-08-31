<?php
	$username='wwwmania_bg';
	$password='bozidarka123456bozidarka';
	$host='localhost:3306';

	session_start();
	$korisnickoIme = $_SESSION['korisIme'];
	$idKorisnika = $_SESSION['id'];
		
	$veza=mysqli_connect($host, $username, $password, 'wwwmania_diplomskiBozidarka') or die("Neuspješno povezivanje");
	$baza = mysqli_select_db($veza,'wwwmania_diplomskiBozidarka') or die("Nismo našli bazu");
?>
<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8" />
	<link rel="stylesheet" href="diplomski.css">
    <title> DRUGI PROFIL </title>
</head>

<body>
	<script src="diplomski.js"> </script>

	<header>
	    <h1> FriendShare </h1>
    </header>
    <hr>
    <br>

	<?php
		$imePrezime = mysqli_query($veza,
			"SELECT ime, prezime from korisnik where id_korisnik='".$_GET['korisnik']."'");

		$imePrezimeRezultat = mysqli_fetch_array($imePrezime);

	?>

	<nav>
	<div id="navbar">
		<button> <a href="profil.php"> Profil </a> </button>
		<button> <a href="pocetnaStranica.php"> Početna stranica </a> </button>
	</div>	
		<br><br>
		<form>
			<input type="text" name="pretrazivac">
			<input type="submit" value="Pretraži">
		</form>
		<hr>

		<script>
			window.onscroll = function() {myFunction()};
			var navbar = document.getElementById("navbar");
			var sticky = navbar.offsetTop;
		</script>

	</nav>

	<?php
		$src="";

		$profilnaSlika = mysqli_query( $veza,
			"SELECT slika_profila FROM korisnik where id_korisnik='".$_GET['korisnik']."'");

		$profilnaSlikaRezultat = mysqli_fetch_array($profilnaSlika);

		if($profilnaSlikaRezultat["slika_profila"]) {
			$src = $profilnaSlikaRezultat["slika_profila"];
		}

		print '<div class="slikaProfila">';
		print '<img class="slikaObjava" src="'.$src.'" id="slika">';
		print '</div>';

		$idKorisnika = $_SESSION['id'];

		if(isset($_POST['prijateljstvo'])) {
			$upitImaLiZahtijev = mysqli_query( $veza,
				"select * from `zahtjev`  where (korisnik_id_poslao = '".$idKorisnika."' and korisnik_id_prima = '".$_GET['korisnik']."') or 
				(korisnik_id_prima = '".$idKorisnika."' and korisnik_id_poslao = '".$_GET['korisnik']."');");

			if(!mysqli_fetch_array($upitImaLiZahtijev)) {
				$upit = mysqli_query( $veza,
					"INSERT INTO `zahtjev` (`status`,`korisnik_id_poslao`,`korisnik_id_prima`) VALUES ('poslao',".$idKorisnika.",".$_GET['korisnik'].");");

				print "<script type='text/javascript'>alert('Zahtjev je poslan!');</script>";
			}
		}

	?>

	<section>

		<?php
			print ($imePrezimeRezultat["ime"]."  ".$imePrezimeRezultat["prezime"]);
		
			$poslanZahtjev = mysqli_query($veza,
			"SELECT * from zahtjev where (korisnik_id_poslao=".$idKorisnika." and korisnik_id_prima=".$_GET['korisnik'].") or 
				(korisnik_id_poslao=".$_GET['korisnik']." and korisnik_id_prima=".$idKorisnika.")");

			$poslanZahtjevRezultat = mysqli_fetch_array($poslanZahtjev);

			if(!$poslanZahtjevRezultat[0]) {
				print '<form method="post">';
					print '<input type="submit" name="prijateljstvo" value="Pošalji zahtjev za prijateljstvo">';
				print '</form>';
			}
	
			print '<a href="poruke.php?korisnik='.$_GET['korisnik'].'"> <img src="poruka.png" alt="nema" width="30" height="30"> </a>';
	
		?>
	</section>
	
	<?php
		if(isset($_POST['noviKomentar'])){
			date_default_timezone_set('Europe/Sarajevo');
			$vrijemePostavljanja = date('Y-m-d h:i:s', time());

			$komentar = mysqli_query( $veza,
			"INSERT INTO `komentar` ( `vrijeme_postavljanja`, `sadrzaj_komentara`, `objava_id_objave`, `korisnik_id_korisnik`) 
			VALUES ('".$vrijemePostavljanja."', '".$_POST['sadrzajKomentara']."', '".$_POST['noviKomentarIdObjave']."', '".$idKorisnika."')");

		}

		if(isset($_POST['noviKomentarNaKomentar'])){
			date_default_timezone_set('Europe/Sarajevo');
			$vrijemePostavljanja = date('Y-m-d h:i:s', time());

			$komentar = mysqli_query( $veza,
			"INSERT INTO `komentar` ( `vrijeme_postavljanja`, `sadrzaj_komentara`, `komentar_id_komentara`, `korisnik_id_korisnik`) 
			VALUES ('".$vrijemePostavljanja."', '".$_POST['odgovorNaKomentar']."', '".$_POST['noviKomentarIdKomentar']."', '".$idKorisnika."')");

		}

		function prikaziKomentarnaKomentar($veza, $id, $objavu, $uvlacenje) {

			$komentari;
			if($objavu) {
			$komentari = mysqli_query( $veza,
					"SELECT id_komentara, sadrzaj_komentara, vrijeme_postavljanja, ime, prezime  from komentar join korisnik 
                         on korisnik.id_korisnik=komentar.korisnik_id_korisnik where objava_id_objave=".$id);
			} else {
				$komentari = mysqli_query( $veza,
					"SELECT id_komentara, sadrzaj_komentara, vrijeme_postavljanja, ime, prezime  from komentar join korisnik 
                         on korisnik.id_korisnik=komentar.korisnik_id_korisnik where komentar_id_komentara=".$id);
			}
			$uvlacenje = $uvlacenje + 1;		
			while($komentariRezultat = mysqli_fetch_array($komentari)){
				print "<div style=\"padding-left: ".($uvlacenje*50)."px\"><div class=\"komentar\">
					<b class=\"komentarSadrzaj\">".$komentariRezultat['sadrzaj_komentara']."</b><br> 
					<sub class=\"komentarDatum\">".$komentariRezultat['vrijeme_postavljanja']." </sub> 
					<sub class=\"komentarOsoba\">".$komentariRezultat['ime']." ".$komentariRezultat['prezime']."</sub>
				</div>
				<button class=\"komentarDugme\" onclick=kreirajOdgovorNaKomentar(".$komentariRezultat['id_komentara'].")> Odgovori </button>
				<div id=\"kreirajOdgovor".$komentariRezultat['id_komentara']."\"> </div></div>";
				
				
			    prikaziKomentarnaKomentar($veza, $komentariRezultat['id_komentara'], 0, $uvlacenje);
			}
		}

	$objavaIzBaze = mysqli_query( $veza,
			"SELECT id_objave, slika, tekst, datum_objave FROM objava where korisnik_id_korisnik='".$_GET['korisnik']."'");

		print '<center>';
		while($objavaIzBazeRezultat = mysqli_fetch_array($objavaIzBaze)){
				print '<hr>';
				print '<div>';
				print '<div class="objava">';
				if($objavaIzBazeRezultat["tekst"] != ""){
				print '<p>'.$objavaIzBazeRezultat["tekst"]. '</p>';
				print '<sub id="objavaDatum">'. $objavaIzBazeRezultat['datum_objave'].' </sub>';
				
				}
				print '</div>';
				print '<br>';
			
				if($objavaIzBazeRezultat["slika"] != ""){
					print '<div class="okvir"><div class="okvirSlike">';
					print '<img class="slikaObjava" src="'.$objavaIzBazeRezultat["slika"].'" id="objavaSlika">';
					print '</div><sub id="objavaDatum">'.$objavaIzBazeRezultat['datum_objave'].'</sub>';
					print '</div>';
				}
				print '<br>';
				prikaziKomentarnaKomentar($veza, $objavaIzBazeRezultat["id_objave"], 1, -1);

				print '<form method="post">
							<input type="text" name="sadrzajKomentara">
							<input type="hidden" value="'.$objavaIzBazeRezultat["id_objave"].'" name="noviKomentarIdObjave">
							<input type="submit" name="noviKomentar" value="Komentariši">
						</form>';
				print '</div>';
				print '<br>';
				
			}
		print '</center>';
	?>
	<hr>
    <footer>
	    <p> FriendShare© 2019 </p>
    </footer>

</body>
</html>
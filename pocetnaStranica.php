<?php
	$username='wwwmania_bg';
	$password='bozidarka123456bozidarka';
	$host='localhost:3306';

	session_start();
	$idKorisnika = $_SESSION['id'];
		
	$veza=mysqli_connect($host, $username, $password, 'wwwmania_diplomskiBozidarka') or die("Neuspješno povezivanje");
	$baza = mysqli_select_db($veza,'wwwmania_diplomskiBozidarka') or die("Nismo našli bazu");
?>
<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8" />
	<link rel="stylesheet" href="diplomski.css">
	<script src="diplomski.js" charset="utf-8"> </script>
    <title> POČETNA STRANICA </title>
</head>

<body>
	<header>
	    <h1> FriendShare </h1>
		<form style="float:right;" action="diplomski.php">
			<input type="submit" value="Odjavi">
		</form>
		<br>
    </header>
    <hr>
    <br>

	<nav>
	<div id="navbar">
		<button> <a href="profil.php"> Profil </a> </button>
		<button> <a href="pocetnaStranica.php"> Početna stranica </a> </button>
	</div>	
		<script>
			window.onscroll = function() {myFunction()};
			var navbar = document.getElementById("navbar");
			var sticky = navbar.offsetTop;
			</script>
		<br><br>

		<form method="post" action="rezultatPretrageKorisnika.php">
			<input type="text" name="pretrazivac">
			<input type="submit" value="Pretrazi" name="pretraga">
		</form>
	</nav>
	
	<?php
		$prikazObjava = mysqli_query($veza,
		"select id_objave, korisnik.ime, korisnik.prezime, korisnik.slika_profila, objava.slika, objava.tekst, objava.datum_objave from objava join zahtjev join korisnik
			where (korisnik.id_korisnik=zahtjev.korisnik_id_poslao or korisnik.id_korisnik=zahtjev.korisnik_id_prima) and 
			korisnik.id_korisnik=objava.korisnik_id_korisnik and
			(korisnik_id_poslao=".$idKorisnika." or korisnik_id_prima=".$idKorisnika.") and status='prihvaceno' and korisnik.id_korisnik != ".$idKorisnika." order by id_objave desc ");

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
		print '<center>';
		while($prikazObjavaRezultat = mysqli_fetch_array($prikazObjava)){
			print '<hr>';
			print '<br>';
			if($prikazObjavaRezultat["tekst"] != ""){
				print '<img src="'.$prikazObjavaRezultat["slika_profila"].'" width="30" height="40"> ';
				print $prikazObjavaRezultat["ime"].' ';
				print $prikazObjavaRezultat["prezime"];
				print '<br>';
				print '<div class="objava">';
				print '<p  id="objTeksta">'.$prikazObjavaRezultat["tekst"].'</p>';
				print '<sub id="objavaDatum">'. $prikazObjavaRezultat['datum_objave'].' </sub>';
				print '</div>';
			}
			
			print '<br>';

			if($prikazObjavaRezultat["slika"] != ""){
				print '<img src="'.$prikazObjavaRezultat["slika_profila"].'" width="30" height="40"> ';
				print $prikazObjavaRezultat["ime"].' ';
				print $prikazObjavaRezultat["prezime"];
				print '<br><br>';
				
				print '<div class="okvir"><div class="okvirSlike">';
				print '<img class="slikaObjava" src="'.$prikazObjavaRezultat["slika"].'" id="objavaSlika">';
				print '</div></div>';
			}
			print '<br>';
			prikaziKomentarnaKomentar($veza, $prikazObjavaRezultat["id_objave"], 1, -1);

				print '<form method="post">
							<input type="text" name="sadrzajKomentara">
							<input type="hidden" value="'.$prikazObjavaRezultat["id_objave"].'" name="noviKomentarIdObjave">
							<input type="submit" name="noviKomentar" value="Komentariši">
						</form>';
				print '</div>';
		}
	print '<center>';
	?>

	<hr>
    <footer>
	    <p> FriendShare© 2019 </p>
    </footer>

</body>
</html>
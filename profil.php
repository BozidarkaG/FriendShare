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
    <title> PROFIL </title>
</head>

<body>
	<script charset="UTF-8" src="diplomski.js"> </script>

	<?php
		$imePrezime = mysqli_query($veza,
			"SELECT ime, prezime from korisnik where korisnicko_ime='".$korisnickoIme."'");

		$imePrezimeRezultat = mysqli_fetch_array($imePrezime);
	?>
	<header>
	
		<h1> FriendShare </h1>
		<form style="float:right;" action="diplomski.php">
			<input type="submit" value="Odjavi">
		</form>
		
	</header>
	<br>
	<hr>

	<nav>
	<div id="navbar">
		<button> <a href="profil.php"> Profil </a> </button>
		<button> <a href="pocetnaStranica.php"> Početna stranica </a> </button>
	</div>	
		<br><br>
		
		<form method="post" action="rezultatPretrageKorisnika.php">
			<input type="text" name="pretrazivac">
			<input type="submit" value="Pretraži" name="pretraga">
		</form>
		
		<script>
			window.onscroll = function() {myFunction()};
			var navbar = document.getElementById("navbar");
			var sticky = navbar.offsetTop;
		</script>
	</nav>
	
	<?php
		if(isset($_POST['prihvati'])){
			$prihvatiZahtjev = mysqli_query($veza,
			"UPDATE `zahtjev` SET `status` = 'prihvaceno' WHERE korisnik_id_prima=".$idKorisnika." and korisnik_id_poslao=".$_POST['korisnik_id_poslao']);
		}

		if(isset($_POST['odbij'])){
			$odbijZahtjev = mysqli_query($veza,
			"DELETE FROM `zahtjev` WHERE status='poslao' and korisnik_id_prima=".$idKorisnika." and korisnik_id_poslao=".$_POST['korisnik_id_poslao']);
		}

		$prikazZahtjeva = mysqli_query($veza,
		"select slika_profila, ime, prezime, korisnik_id_poslao from korisnik join zahtjev where korisnik.id_korisnik=zahtjev.korisnik_id_poslao and korisnik_id_prima=".$idKorisnika."
			and status='poslao'");

		
	?>

	<div class="red">
		<div class="kolona">

		<?php
			$src="";

			$profilnaSlika = mysqli_query( $veza,
				"SELECT slika_profila FROM korisnik where korisnicko_ime='".$korisnickoIme."'");

			$profilnaSlikaRezultat = mysqli_fetch_array($profilnaSlika);

			if($profilnaSlikaRezultat["slika_profila"]) {
				$src = $profilnaSlikaRezultat["slika_profila"];
				}

			if(isset($_POST['sacuvaj']))
			{ 

				$putanjaSlike =$_FILES['fileupload']['tmp_name'];
				$nazivSlike =$_FILES['fileupload']['name'];

				if ( !is_dir( "slike/".$korisnickoIme."/" ) ) {
					mkdir( "slike/".$korisnickoIme."/" );       
				}
				if(move_uploaded_file($putanjaSlike,"slike/".$korisnickoIme."/".$nazivSlike)){
					$src = "slike/".$korisnickoIme."/".$nazivSlike;

				$upit = mysqli_query( $veza,
					"UPDATE `korisnik` SET `slika_profila` = '".$src."' WHERE `korisnicko_ime` = '".$korisnickoIme."'");

				print "<script type='text/javascript'>alert('Slika profila postavljena!');</script>";
				} 
			else {
				print "<script type='text/javascript'>alert('Ne postoji');</script>";	
				}
			}

			print '<div class="slikaProfila">';
			print '<img class="slikaObjava" src="'.$src.'" id="slika">';
			print '</div><br>';
		?>


		<form method="post" enctype="multipart/form-data">
			<input type="file" name="fileupload" id="fileupload" onchange=postaviProfilnuSliku(this.files)>
			<input type="submit" name="sacuvaj" value="Sačuvaj">
		</form>
		<br>
		<div class="ime">
		<?php
			print ($imePrezimeRezultat["ime"]."  ".$imePrezimeRezultat["prezime"]);
		?>
		</div>
		</div>

		<div class="kolona">
			<p> Zahtjevi za prijateljstva: </p>
			<table>
				<tbody>
	
				<?php


					while($prikazZahtjevaRezultat = mysqli_fetch_array($prikazZahtjeva)){
						print '<tr><td><img src="'.$prikazZahtjevaRezultat['slika_profila'].'" alt="" width="30" height="50" id="slika"></td>';
						print '<td>'.$prikazZahtjevaRezultat['ime'].' ';
						print $prikazZahtjevaRezultat['prezime'].'</td>';
						print '<td> 
								<form method="post">
									<input type="hidden" name="korisnik_id_poslao" value="'.$prikazZahtjevaRezultat['korisnik_id_poslao'].'">
									<input type="submit" name="prihvati" value="Prihvati zahtjev">
								</form>
							</td>';
						print '<td>
								<form method="post">
									<input type="hidden" name="korisnik_id_poslao" value="'.$prikazZahtjevaRezultat['korisnik_id_poslao'].'">
									<input type="submit" name="odbij" value="Odbij zahtjev">
								</form>
							</td></tr>';
						}
				?>
				</tbody>
			</table>

		<p> Lista prijatelja: </p>
		<table>
			<?php

			$prikazPrijatelja = mysqli_query($veza,
			"select slika_profila, ime, prezime, id_korisnik from korisnik join zahtjev on (id_korisnik=korisnik_id_poslao or id_korisnik=korisnik_id_prima) and  id_korisnik != ".$idKorisnika." 
				where status='prihvaceno' and (korisnik_id_prima=".$idKorisnika."  or korisnik_id_poslao=".$idKorisnika.")"); 

			while($prikazPrijateljaRezultat = mysqli_fetch_array($prikazPrijatelja)){
					print '<tr><td><a href="drugiProfil.php?korisnik='.$prikazPrijateljaRezultat['id_korisnik'].'"><img src="'.$prikazPrijateljaRezultat['slika_profila'].'" alt="" width="30" height="50" id="slika"></td>';
					print '<td>'.$prikazPrijateljaRezultat['ime'].' ';
					print $prikazPrijateljaRezultat['prezime'].'</a></td></tr>';
					}			
			?>
		</table>
		</div> 
	</div>

	<hr>
	<center>
	<article id="okvirObjave">
		<img class="slikaObjava" src="" id="novaObjavaSlika">
		<form method="post" enctype="multipart/form-data">
			<input type="text" name="objavaTeksta">
			<input type="file" name="objavaSlike" id="objavaSlike" onchange=postaviObjavuSlike(this.files)>
			<input type="submit" name="objavi" value="Objavi">
		</form>
	</article>
	<br>
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
		if(isset($_POST['objavi']))
		{ 
			$objavaTeksta = $_POST['objavaTeksta']; 
			$putanjaSlike =$_FILES['objavaSlike']['tmp_name'];
			$nazivSlike =$_FILES['objavaSlike']['name'];

			if ( !is_dir( "slike/".$korisnickoIme."/" ) ) {
				mkdir( "slike/".$korisnickoIme."/" );       
			}

			$objava="";
			if(move_uploaded_file($putanjaSlike,"slike/".$korisnickoIme."/".$nazivSlike)){
				$objava = "slike/".$korisnickoIme."/".$nazivSlike;
			}
	
			date_default_timezone_set('Europe/Sarajevo');
			$vrijeme = date('Y-m-d h:i:s', time());

			if($objava != "" || $objavaTeksta != "") {
				$upitZaObjavuSlike = mysqli_query( $veza,
					"INSERT INTO `objava`(`slika`,`tekst`,`datum_objave`,`korisnik_id_korisnik`) VALUES ('".$objava."','".$objavaTeksta."','".$vrijeme."', ".$idKorisnika.")");
			}
			else {
				print "<script type='text/javascript'>alert('Objava nije uspješna!');</script>";	
			}
		}
		if(isset($_POST['izbrisi'])){
			$brisanjeKomentara = mysqli_query( $veza,
					"DELETE FROM `komentar` WHERE id_komentara =". $_POST['id_komentara']);
		}

		function prikaziKomentarnaKomentar($veza, $id, $objavu, $uvlacenje) {

			$komentari;
			if($objavu) {
			$komentari = mysqli_query( $veza,
					"SELECT id_komentara, sadrzaj_komentara, vrijeme_postavljanja, ime, prezime  from komentar inner join korisnik 
                         on korisnik.id_korisnik=komentar.korisnik_id_korisnik where objava_id_objave=".$id);
			} else {
				$komentari = mysqli_query( $veza,
					"SELECT id_komentara, sadrzaj_komentara, vrijeme_postavljanja, ime, prezime  from komentar inner join korisnik 
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
		
				<form class=\"komIzbrisi\" method=\"post\">
					<input type=\"hidden\" name=\"id_komentara\" value=\"".$komentariRezultat['id_komentara']."\">
					<input class=\"komIzbrisi\" type=\"submit\" name=\"izbrisi\" value=\"Izbriši komentar\">
				</form>
			
				<div id=\"kreirajOdgovor".$komentariRezultat['id_komentara']."\"> </div></div>";
				
				
			    prikaziKomentarnaKomentar($veza, $komentariRezultat['id_komentara'], 0, $uvlacenje);
			}
		}
		if(isset($_POST['izbrisiObjavu'])){
			$brisanjeobjave = mysqli_query( $veza,
					"DELETE FROM `objava` WHERE id_objave =". $_POST['idObjave']);
		}

		$objavaIzBaze = mysqli_query( $veza,
			"SELECT id_objave, datum_objave, slika, tekst FROM objava where korisnik_id_korisnik='".$idKorisnika."' order by id_objave desc");


		while($objavaIzBazeRezultat = mysqli_fetch_array($objavaIzBaze)){
				
				print '<hr><br>';
				print '<div class="objava">';
				if($objavaIzBazeRezultat["tekst"] != ""){
					print '<p>'.$objavaIzBazeRezultat["tekst"]. '</p>';
					print '<div class="okvirVrijemeIzbrisi"><sub id="objavaDatum">'. $objavaIzBazeRezultat['datum_objave'].' </sub>';
					print '<form class="okvirVrijemeIzbrisi" method="post">
							<input type="hidden" value="'.$objavaIzBazeRezultat["id_objave"].'" name="idObjave">
							<input type="submit" name="izbrisiObjavu" value="Izbriši objavu">
						</form></div>';
				}
				print '</div>';
				print '<br>';
				
				if($objavaIzBazeRezultat["slika"] != ""){
					print '<div class="okvir"><div class="okvirSlike">';
					print '<img class="slikaObjava" src="'.$objavaIzBazeRezultat["slika"].'" id="objavaSlika">';
				
					print '</div><div class="okvirVrijemeIzbrisi"><sub id="objavaDatum">'.$objavaIzBazeRezultat['datum_objave'].'</sub>';
					print '<form class="okvirVrijemeIzbrisi" method="post">
							<input type="hidden" value="'.$objavaIzBazeRezultat["id_objave"].'" name="idObjave">
							<input type="submit" name="izbrisiObjavu" value="Izbriši objavu">
						</form></div>';
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
			}
	
	?>
	</center>
	

	<hr>
    <footer>
	    <p> FriendShare© 2019 </p>
    </footer>

</body>
</html>
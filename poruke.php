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
	<meta charset="utf-8" http-equiv="refresh" content="20">
	
	<link rel="stylesheet" href="diplomski.css">
    <title> DIPLOMSKI RAD </title>
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
		<br><br>
		<form method="post" action="rezultatPretrageKorisnika.php">
			<input type="text" name="pretrazivac">
			<input type="submit" value="Pretrazi" name="pretraga">
		</form>
		<script>
			window.onscroll = function() {myFunction()};
			var navbar = document.getElementById("navbar");
			var sticky = navbar.offsetTop;
		</script
	</nav>

	<?php
		if(isset($_POST['posaljiPoruku'])){

			$tekstPoruke = $_POST['tekstPoruke'];
			if(strlen($tekstPoruke) > 0) {
				$poruke = mysqli_query($veza,
					"INSERT INTO `poruke`(`sadrzaj_poruke`,`posiljaoc`,`primaoc` ) 
						VALUES ('".$tekstPoruke."',".$idKorisnika.",".$_GET['korisnik'].")");
			}
			header('Location: poruke.php?korisnik='.$_GET['korisnik']);
		}

		$prikazatiPoruke = mysqli_query($veza,
			"select ime, prezime, sadrzaj_poruke, posiljaoc, primaoc from poruke left join korisnik on poruke.posiljaoc=korisnik.id_korisnik
			where (posiljaoc=".$idKorisnika." and primaoc =".$_GET['korisnik'].") or (posiljaoc=".$_GET['korisnik']." and primaoc =".$idKorisnika.")");

		print '<br> <center>';

		while($redPrikazPoruke = mysqli_fetch_array($prikazatiPoruke)){
			if($idKorisnika == $redPrikazPoruke['posiljaoc']){
				print '<div class="poruka1">';
				print '<sub class="ja">'.$redPrikazPoruke['ime'].' '.$redPrikazPoruke['prezime'].'</sub>';
			}
			else{
				print '<div class="poruka2">';
				print '<sub class="ti">'.$redPrikazPoruke['ime'].' '.$redPrikazPoruke['prezime'].'</sub>';
			}
			print $redPrikazPoruke['sadrzaj_poruke'];
			print '<br>';
			print '</div>';
			}

		print '</center>';
	?>
			

	<center>
		<form method="post">
		<input type="text" name="tekstPoruke">
		<input type="submit" name="posaljiPoruku" value="Pošalji poruku">
	</form>
	</center>

	<hr>
    <footer>
	    <p> FriendShare© 2019 </p>
    </footer>

</body>
</html>
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
    <title> REZULTAT PRETRAGE KORISNIKA </title>
</head>

<body>
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
			<input type="hidden" id="korisnik" name="korisnik">
			<input type="submit" value="Pretrazi" name="pretraga">
		</form>
		<hr>
		<script>
			window.onscroll = function() {myFunction()};
			var navbar = document.getElementById("navbar");
			var sticky = navbar.offsetTop;
		</script>
	</nav>

	<?php
		if(isset($_POST['pretraga'])){

			$pretrazi = $_POST['pretrazivac'];
			$spisakPretrazenihKorisnika = mysqli_query($veza,
				"SELECT ime, prezime, slika_profila, id_korisnik from korisnik where id_korisnik != ".$idKorisnika." and ('".$pretrazi."' LIKE CONCAT(\"%\", ime, \"%\") or '".$pretrazi."' LIKE CONCAT(\"%\", prezime, \"%\"))");

			while($spisak=mysqli_fetch_array($spisakPretrazenihKorisnika)) {
				print '<a href="drugiProfil.php?korisnik='.$spisak['id_korisnik'].'"><img src="'.$spisak['slika_profila'].'" alt="" width="70" height="100" id="slika"></a>';
				print '<a href="drugiProfil.php?korisnik='.$spisak['id_korisnik'].'">'.$spisak['ime'].'</a>';
				print '<a href="drugiProfil.php?korisnik='.$spisak['id_korisnik'].'">'.$spisak['prezime'].'</a>';
				print '<br>';
				}
			}
	?>

	<hr>
	<footer>
		<p> FriendShare© 2019 </p>
	</footer>

</body>
</html>
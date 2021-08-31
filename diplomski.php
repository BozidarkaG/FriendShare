<?php
	$username='wwwmania_bg';
	$password='bozidarka123456bozidarka';
	$host='localhost:3306';

	$veza=mysqli_connect($host, $username, $password, 'wwwmania_diplomskiBozidarka') or die("Neuspješno povezivanje");
	$baza = mysqli_select_db($veza,'wwwmania_diplomskiBozidarka') or die("Nismo našli bazu");
?>
<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8" />
	<link rel="stylesheet" href="diplomski.css">
    <title> DIPLOMSKI RAD </title>
</head>

<body>
	<header>
	    <h1> FriendShare </h1>
    </header>
    <hr>
    <br><br>

	<?php
		if(isset($_POST['provjeraLogovanja']))
			{
			$sifra = $_POST['psw'];
			$korisnickoIme = $_POST['korisIme'];

			//$password_hash = password_hash($sifra, PASSWORD_BCRYPT);
			//die($password_hash);

			$upit = mysqli_query( $veza,
				"select sifra, id_korisnik from korisnik where korisnicko_ime='".$korisnickoIme."'");

			$red = mysqli_fetch_array($upit);

			if(password_verify($sifra, $red['sifra'])){

				session_start();
				$_SESSION['korisIme'] = $korisnickoIme;
				$_SESSION['id'] = $red['id_korisnik'];

				header('Location: profil.php');
			}
			else{
				print "<script type='text/javascript'>alert('Ne postoji korisnik sa ovim korisničkim imenom i šifrom!');</script>";
			}
		}

		if(isset($_POST['registrovanje']))
			{
				$korisnickoIme = mysqli_query( $veza,
					"SELECT * FROM korisnik where korisnicko_ime = '".$_POST['korisIme']."'");
				
				if(mysqli_fetch_array($korisnickoIme)){
					print "<script type='text/javascript'>alert('Već postoji korisnik sa ovim korisničkim imenom!');</script>";
				}
				else{
					$password_hash = password_hash($_POST['psw'], PASSWORD_BCRYPT);

					$upit = mysqli_query( $veza,
						"INSERT INTO `korisnik`(`ime`,`prezime`,`datum_rodjenja`,`korisnicko_ime`,`pol`,`sifra`) VALUES
						('".$_POST['firstname']."','".$_POST['lastname']."','".$_POST['bday']."','".$_POST['korisIme']."','".$_POST['gender']."','".$password_hash."')");
				
					$upitId = mysqli_query( $veza,
						"SELECT id_korisnik FROM korisnik where korisnicko_ime = '".$_POST['korisIme']."'");
					$rezultatupitId = mysqli_fetch_array($upitId);

					session_start();
					$_SESSION['korisIme'] = $_POST['korisIme'];
					$_SESSION['id'] = $rezultatupitId['id_korisnik'];

					header('Location: profil.php');
				}
		}
	?>

	<article class="prijava">
		<h2> Prijavi se </h2>
		<br>

		<form method="post">
			Korisničko ime:<br>
				<input type="text" method="post" name="korisIme" value="">
			<br>
			Šifra:<br>
				<input type="password" method="post" name="psw">
			<br><br>
			<input name="provjeraLogovanja" type="submit" value="Prijavi">
		</form>
	</article>

	<article class="registracija">
		<h2> Registruj se </h2>
		<br>
		<form method="post">
			 Ime:
			 <input type="text" name="firstname">
			 <br><br>
			 Prezime:
			 <input type="text" name="lastname">
			 <br><br>
			 Datum rođenja:
			 <input type="date" name="bday">
			 <br><br>
			 Korisničko ime:
			 <input type="text" name="korisIme">
			 <br><br>
			Pol:
			<input type="radio" name="gender" value="male" > Muško 
			<input type="radio" name="gender" value="female"> Žensko <br><br>
			Šifra:
			<input type="password" name="psw">
			<br><br>
			<input name="registrovanje" type="submit" value="Registrovanje">
		</form> 
	</article>

	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	
    <footer>
	    <p> FriendShare© 2019 </p>
    </footer>

</body>
</html>
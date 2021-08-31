function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}

function postaviProfilnuSliku(fajlovi) {
		var putanja =  window.URL.createObjectURL(fajlovi[0]);
		var slika = document.getElementById("slika");
		slika.src = putanja;
}

function postaviObjavuSlike(fajlovi) {
		var putanja =  window.URL.createObjectURL(fajlovi[0]);
		var novaObjavaSlika = document.getElementById("novaObjavaSlika");
		novaObjavaSlika.src = putanja;
        var okvirObjave = document.getElementById("okvirObjave");
        okvirObjave.className = "okvirSlike";
}

function kreirajOdgovorNaKomentar(idKomentara) {
        var odgovor = document.getElementById("kreirajOdgovor" + idKomentara);
        odgovor.innerHTML =  '<form method="post">' +
                             '<input type="text" name="odgovorNaKomentar">' +
                             '<input type="hidden" value="' + idKomentara + '" name="noviKomentarIdKomentar">' +
							 '<input type="submit" name="noviKomentarNaKomentar" value="Dodaj komentar">' +
						     '</form>';
						    
}


function pozovi(reakSlika, idObjave, idKorisnika){
    var url = 'unosReakcije.php?reakSlika=' + reakSlika + '&&idObjave=' + idObjave + '&&idKorisnika=' + idKorisnika;

    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200){

            var brojacEmoji = document.getElementById("brojacEmoji" + reakSlika + idObjave);
            brojacEmoji.innerHTML = parseInt(brojacEmoji.innerHTML) + 1;
        }
    }
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}
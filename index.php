<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8"/>
	<meta name="descrption" content="Firma budowlana Renomex"/>
	<meta name="keywords" content="firma budowlana, renomex, budowa, budowlanka, "/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Jakub Krajniewski"/>
	<link rel="shortcut icon" href="./strona/image/logo.svg">
	<title>Renomex</title>
	<link rel="stylesheet" href="./strona/Style.css">
	<link rel="stylesheet" href="./strona/fontello/css/fontello.css">

</head>
<body>
    <main>
		<!-- Formularz -->
	<form action="upload.php" method="post" ENCTYPE="multipart/form-data">
		<div>
			Imię:<br/>
			<input type="text" name="inname" value="" /><br/>
			Nazwisko:<br/>
			<input type="text" name="surname" value="" /><br/>
			E-mail:<br/>
			<input type="email" name="mail" value="" /><br/>
			<label>
                <span>Wykształcenie</span>
                <select name="education">
                    <option value="1">podstawowe</option>
                    <option value="2">średnie</option>
                    <option value="3">wyższe</option>
                </select>
            </label><br/>
			<label>Załącznik 1 LM [JPG, PDF, DOC]:</label>
        	<input type="file" id="zalacznik1" name="zalacznik1" accept=".jpg,.pdf,.doc,.docx"><br>
			<div id="cve">
				<div id="cv">
					<label>Załącznik 2 CV [JPG, PDF, DOC]:</label>
					<input type="file" id="zalacznik2" name="zalacznik2" accept=".jpg,.pdf,.doc,.docx"><br>
					<button type="button" onclick="dodajCv()" id="dodajcvv">Dodaj kolejne CV</button>
				</div>	
			</div>

			<div id="staze">
            Staż<br>
            Wprowadź informacje o odbytych stażach:
				<div id="staz">
					<label for="nazwa-firmy">Nazwa firmy:</label>
					<input type="text" name="nazwa-firmy-1" placeholder="Nazwa firmy">

					<label for="starter">Od:</label>
					<input type="date" name="start-1">

					<label for="ender">Do:</label>
					<input type="date" name="end-1">

					<input type="hidden" name="zmienna" value="1">
					<button type="button" onclick="dodajStaz()" id="dodajstazz">Dodaj kolejny staż</button>
				</div>
			</div>
			<input type="submit" value="Wyślij zgłoszenie">
		</div>
 	</form>
	<script>
		// Pobierz przyciski
		const dodajStaz = document.getElementById('dodajstazz');
		const dodajCv = document.getElementById('dodajcvv');
		const stazeContainer = document.getElementById('staze');
		const cvContainer = document.getElementById('cve');

		// Licznik używany do tworzenia unikalnych identyfikatorów dla pól staży
		let stazCount = 2;

		// Ustaw obsługę zdarzenia kliknięcia przycisku "Dodaj staż"
		dodajStaz.addEventListener('click', function() {
		const stazDiv = document.createElement('div');
		stazDiv.setAttribute('class', 'staz');
		stazDiv.innerHTML =`
			<label for="nazwa-firmy">Nazwa firmy:</label>
			<input type="text" name="nazwa-firmy-${stazCount}" placeholder="Nazwa firmy" required>
			<label for="starter">Od:</label>
			<input type="date" name="start-${stazCount}" required>
			<label for="ender">Do:</label>
			<input type="date" name="end-${stazCount}" required>
			<input type="hidden" name="zmienna" value="${stazCount}">
			<button type="button" class="usun-staz">Usuń</button>
			`
		;
		stazeContainer.appendChild(stazDiv);

		//Przycisk usuwania stażu
		const usunStazButton = stazDiv.querySelector('.usun-staz');
		usunStazButton.addEventListener('click', function() {
			stazDiv.remove();
		});
		stazCount++;
		});

		//Dodaje element usuwania przycisku
		dodajCv.addEventListener('click', function() {
		const cvvId = document.getElementById('dodajcvv');
		const cvDiv = document.createElement('div');		
		cvDiv.setAttribute('class', 'cv');
		cvDiv.innerHTML =
			`
			<label>Załącznik 3 CV [JPG, PDF, DOC]:</label>
        	<input type="file" id="zalacznik3" name="zalacznik3" accept=".jpg,.pdf,.doc,.docx"><br>
			`
		;
		cvContainer.appendChild(cvDiv);
		const usuncvButton = cvDiv.querySelector('.usun-cv');
		cvvId.remove();
		});

	</script>
    </main>
</body>
</html>
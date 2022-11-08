<?php

$CHARMAP = ['ö' => 'o', 'Ö' => 'O', 'ó' => 'o', 'Ó' => 'O', 'ő' => 'o', 'Ő' => 'O', 'ú' => 'u', 'Ú' => 'U',
		'ű' => 'u','Ű' => 'U','ü' => 'u', 'Ü' => 'U', 'á' => 'a', 'à' => 'a', 'Á' => 'A', 'é' => 'e', 'É' => 'E',
		'í' => 'i',	'Í' => 'I', ' ' => '-', ':' => '', '.' => ''];

$city['Határon túl']='Határon túl';
$city['Ajka']='Ajka';
$city['Baja']='Baja';
$city['Békéscsaba']='Békéscsaba';
$city['Budaörs']='Budaörs';
$city['Budapest']='Budapest';
$city['Cegléd']='Cegléd';
$city['Debrecen']='Debrecen';
$city['Dunaharaszti']='Dunaharaszti';
$city['Dunakeszi']='Dunakeszi';
$city['Dunaújváros']='Dunaújváros';
$city['Eger']='Eger';
$city['Érd']='Érd';
$city['Esztergom']='Esztergom';
$city['Gödöllő']='Gödöllő';
$city['Gyál']='Gyál';
$city['Gyöngyös']='Gyöngyös';
$city['Győr']='Győr';
$city['Gyula']='Gyula';
$city['Hajdúböszörmény']='Hajdúböszörmény';
$city['Hajdúszoboszló']='Hajdúszoboszló';
$city['Hatvan']='Hatvan';
$city['Hódmezővásárhely']='Hódmezővásárhely';
$city['Jászberény']='Jászberény';
$city['Kaposvár']='Kaposvár';
$city['Karcag']='Karcag';
$city['Kazincbarcika']='Kazincbarcika';
$city['Kecskemét']='Kecskemét';
$city['Keszthely']='Keszthely';
$city['Kiskunfélegyháza']='Kiskunfélegyháza';
$city['Kiskunhalas']='Kiskunhalas';
$city['Komló']='Komló';
$city['Makó']='Makó';
$city['Miskolc']='Miskolc';
$city['Mosonmagyaróvár']='Mosonmagyaróvár';
$city['Nagykanizsa']='Nagykanizsa';
$city['Nagykőrös']='Nagykőrös';
$city['Nyíregyháza']='Nyíregyháza';
$city['Orosháza']='Orosháza';
$city['Ózd']='Ózd';
$city['Pápa']='Pápa';
$city['Pécs']='Pécs';
$city['Salgótarján']='Salgótarján';
$city['Siófok']='Siófok';
$city['Sopron']='Sopron';
$city['Szeged']='Szeged';
$city['Székesfehérvár']='Székesfehérvár';
$city['Szekszárd']='Szekszárd';
$city['Szentendre']='Szentendre';
$city['Szentes']='Szentes';
$city['Szigetszentmiklós']='Szigetszentmiklós';
$city['Szolnok']='Szolnok';
$city['Szombathely']='Szombathely';
$city['Tata']='Tata';
$city['Tatabánya']='Tatabánya';
$city['Törökszentmiklós']='Törökszentmiklós';
$city['Vác']='Vác';
$city['Várpalota']='Várpalota';
$city['Vecsés']='Vecsés';
$city['Veszprém']='Veszprém';
$city['Zalaegerszeg']='Zalaegerszeg';

//$PROFILE_TYPE[0]='eletut';
//$PROFILE_TYPE[1]='tervek';

for ($i=1; $i<=23; $i++) {
	$district[$i]=$i.'. kerület';
}

return [
	'CHARMAP' 		=> $CHARMAP,
	'CITY'			=> $city,
	'DISTRICT'		=> $district,
	'url_intro' 	=> 'bemutatkozas',
	'url_relation' => 'kapcsolat',
	'LENGTH_INTRO' 	=> 150,
    'LENGTH_INTENTION' 	=> 100,
	'LENGTH_INTEREST' => 150
];
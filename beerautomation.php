<?php
/*
 * @ScriptName:
 * BeerAutomation.php
 *
 * @ScriptDescription:
 * Script de domotisation de la pompe à bière
 *
 * @ScriptVersion:
 * V 1.0
 *
 * @ScriptAuthor:
 * Auteur original Aurel
 *
 * 
 */

include ('parametres.php');
 

	//URL locale
	$url =  "http://".$IPeedomus."/api/get?action=periph.caract&periph_id=".$periph_fut."&api_user=".$api_user."&api_secret=".$api_secret."";
	//URL Web -- $url =  "http://api.eedomus.com/get?action=periph.caract&periph_id=".$periph_fut."&api_user=".$api_user."&api_secret=".$api_secret."";
          $arr = json_decode(utf8_encode(file_get_contents($url)));
 		  $value = $arr->body->last_value;
		  $datemaj = $arr->body->last_value_change;


				$dateperiph = new DateTime($datemaj);
				$datemaj = $dateperiph->format('Y-m-d');


	$datetoday = date("Y-m-d");
	$date1 = strtotime($datemaj);
	$date2 = strtotime($datetoday);

 

	$nbJoursTimestamp = $date2 - $date1;
 

	$nbJours = $nbJoursTimestamp/86400; // 86 400 = 60*60*24 (1 heure = 60 secondes * 60 minutes et que 1 jour = 24 heures) 


	if ($nbJours < 8){
		$beerquality = "0";
		}
	elseif($nbJours >= 9 && $nbJours <= 20){
	    $beerquality = "1";
	    }
	elseif($nbJours >= 21 && $nbJours <= 30){
	    $beerquality = "2";
	    }
?>

<style type="text/css">
  body {
    background-color: #ffffff;
	font-family: Arial;
	font-size: 14px;
	color: #ffffff;
	}
div#ok {
	background-color:#2ECC71;
	}
div#nok {
	background-color:#EF4836;
	}
</style>

<?

		$url = "http://$IPeedomus/api/set?action=periph.value";
		$url .= "&api_user=$api_user";
		$url .= "&api_secret=$api_secret";
		$url .= "&periph_id=$etatbiere";
		$url .= "&value=$beerquality";

		$result = file_get_contents($url);

		if (strpos($result, '"success": 1') == false)
		{
		  echo "<div id='nok'>Une erreur est survenue sur l'update de l'etat de la biere: [".$result."]</div>";
		}
		else
		{
		 echo "<div id='ok'>Update etat biere OK</div><br/>";
		}

		$url = "http://$IPeedomus/api/set?action=periph.value";
		$url .= "&api_user=$api_user";
		$url .= "&api_secret=$api_secret";
		$url .= "&periph_id=$agefut";
		$url .= "&value=$nbJours";


		$result = file_get_contents($url);

		if (strpos($result, '"success": 1') == false)
		{
		  echo "<div id='nok'>Une erreur est survenue sur l'update de lage du fut: [".$result."]</div>";
		}
		else
		{
		 echo "<div id='ok'>Update age du fut OK</div><br/>";
		}

?>
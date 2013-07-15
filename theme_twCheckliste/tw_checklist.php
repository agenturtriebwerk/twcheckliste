<?php
	require_once '../config.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="tw_style.css" media="all" />
		<title><? echo "Checkliste"; ?></title>
		<style type="text/css">
			h2, .geprueft span {
				color: <?php echo $conf['farbcode'];?> !important;
			}
			.checkliste_checkbox {
				border: 1px solid <?php echo $conf['farbcode'];?>;
			}
			.geprueft {
				border-bottom: 2px solid <?php echo $conf['farbcode'];?>;
			}
		</style>
	</head>
	<body>

		<table width="100%">
			<tr>
				<td align="left" valign="top"><? echo $content -> headertext; ?></td>
				<td align="right" valign="top"><img src="<?php echo $conf['logo'];?>" border="0" /></td>
			</tr>			
		</table>
		<div class="checklist<? echo $_POST['match_kind'];?>">
		<?
			$content = new tw_checkliste();
			$content -> createChecklist($conf['footertext']);
		?>
		
	</div>
</div>
</div>
	</body>
</html>

<?
class tw_checkliste {
	
	function createChecklist($footertext) {

		$this -> optionGroup = 0;
		$this -> group = 0;
		$this -> eingabefeldAktiv = 0;
		$this -> eingabefeld = "...........................................";
		$this -> eingabefeldshort = ".....................";

		$this -> checkliste = "";
		
		foreach ($_POST['checklist_data'] as $key => $value) {
			/*?><pre><? print_r($value); ?></pre><?*/
			$this->buildEbene1($value, $footertext);

		}
	}
	function buildEbene1($daten, $footertext){
		?><div class="ebene1">
		<h1><? echo $daten[HL]; ?></h1><?
		if(array_key_exists("LP",$daten)){
			$this->buildCheckPoint($daten['LP']);
		}
		if(array_key_exists("H3",$daten)){
			foreach($daten["H3"] as $ebene2){
				$this->buildEbene2($ebene2, $footertext);
			}
		}


		?></div><?
	}
	function buildEbene2($daten, $footertext){
		?><div class="ebene2"><?
		?><h2><?=$daten['HL']?></h2><?
		if(array_key_exists("LP",$daten)){
			$this->buildCheckPoint($daten['LP']);
		}
		if(array_key_exists("H4",$daten)){
			$x = 1;		
			$open = 0;
			foreach($daten["H4"] as $ebene3){			
				if ($x==1){
					?><div class="ebene3_group"><?
					$open = 1;
				}
				
				$this->buildEbene3($ebene3);
				
				if ($x==2){
					?></div><?
					$x = 0;
					$open = 0;
				}				
				$x++;
			}
			
			if ($open == 1){
				?></div><?
			}
		}

		if ($_POST['match_kind'] == 0){
			?><div class="geprueft"><span><? echo $footertext;?></span> <? echo $this -> eingabefeld;?></div><?	
		}
		?></div><?
	}
	function buildEbene3($daten){
		?><div class="ebene3"><?
		?><h3><?=$daten['HL']?></h3><?
		if(array_key_exists("LP",$daten)){
			$this->buildCheckPoint($daten['LP']);
		}
		?></div><?
	}
	function buildCheckPoint($daten){
		?><div class="checkpointlist"><?
		foreach($daten as $listenpunkt){

			if(stripos($listenpunkt,"EINGABEFELDLANG")>-1){
				?><div class="checkliste_option_long"><?
			}else{
				?><div class="checkliste_option"><?
			}
			$listenpunkt=str_replace("EINGABEFELDLANG", $this -> eingabefeld, $listenpunkt);
			$listenpunkt=str_replace("EINGABEFELD", $this -> eingabefeldshort, $listenpunkt);
			?><div class="checkliste_checkbox">&nbsp;</div><?=$listenpunkt?></div><?
		}
		?></div><?
	}

	function checkH1($value) {
		if (stristr($value, 'h1_') != "") {
			$value = "<h1>" . str_replace("h1_", "", strip_tags($value)) . "</h1>";
			return $value;
		}

	}

	function checkH2($value) {
		if (stristr($value, 'h2_') != "") {		
			if ($this -> group == 1) {
				$value = "<h2>" . str_replace("h2_", "", strip_tags($value)) . "</h2>";
			} else {
				$this -> group = 1;
				$value = "<h2 class='first_h2'>" . str_replace("h2_", "", strip_tags($value)) . "</h2>";
			}			
			return $value;
		}

	}

	function checkH3($value) {

		if (stristr($value, 'h3_') != "") {
			$this -> eingabefeldAktiv = 1;
			if ($this -> optionGroup == 1) {
				$value = "</div><div class='optiongroup'><h3>" . str_replace("h3_", "", strip_tags($value)) . "</h3>";
			} else {
				$this -> optionGroup = 1;
				$value = "<div class='optiongroup'><h3>" . str_replace("h3_", "", strip_tags($value)) . "</h3>";
			}

			return $value;
		}

	}

	function checkLI($value) {
		if (stristr($value, 'li_') != "") {
			$value = '<div class="checkliste_option"><div class="checkliste_checkbox">&nbsp;</div>&nbsp;' . str_replace("li_", "", strip_tags($value)) . "</div>";
			$value = str_replace("EINGABEFELD", $this -> eingabefeld, $value);
			return $value;
		}

	}

	function checkP($value) {

		if (stristr($value, 'p_') != "") {
			$this -> eingabefeldAktiv = 1;
			if ($this -> optionGroup == 1) {
				$value = "</div><div class='optiongroup'><p>" . str_replace("p_", "", strip_tags($value)) . "</p>";
			} else {
				$this -> optionGroup = 1;
				$value = "<div class='optiongroup'><p>" . str_replace("p_", "", strip_tags($value)) . "</p>";
			}
			
			$value = str_replace("EINGABEFELD", $this -> eingabefeld, $value);
			return $value;
		}

	}

}
?>
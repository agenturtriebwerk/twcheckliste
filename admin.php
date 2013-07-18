<?php
/**
 * Plugin twcheckliste:
 * v1.0
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author   web@agentur-triebwerk.de
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC'))
	die();

if (!defined('DOKU_LF'))
	define('DOKU_LF', "\n");
if (!defined('DOKU_TAB'))
	define('DOKU_TAB', "\t");
if (!defined('DOKU_PLUGIN'))
	define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');

class admin_plugin_twcheckliste extends DokuWiki_Admin_Plugin {
	private $tgzurl;
	private $tgzfile;
	private $tgzdir;

	public function __construct() {
		global $conf;
	}

	public function getMenuSort() {
		return 555;
	}

	public function handle() {
		if ($_REQUEST['btn_speichern'] && checkSecurityToken()) {
		
			$this-> document_root = pathinfo($_SERVER['SCRIPT_FILENAME']);
			$this-> document_root = $this-> document_root['dirname'];
			
			$config = '
<?
	$conf["logo"] = "' . $_REQUEST['logo'] . '";
	$conf["farbcode"] = "' . $_REQUEST['farbcode'] . '";
	$conf["footertext"] = "' . $_REQUEST['footertext'] . '";
?>';

			$filename = $this-> document_root . '/lib/plugins/twcheckliste/config.php';

			if (!$handle = fopen($filename, "w+")) {
				print "Kann die Datei $filename nicht öffnen";
				exit ;
			}

			if (!fwrite($handle, $config)) {
				print "Kann in die Datei $filename nicht schreiben";
				exit ;
			}

			fclose($handle);
		}
	}

	public function html() {
		global $ID;
		
		$this-> document_root = pathinfo($_SERVER['SCRIPT_FILENAME']);
		$this-> document_root = $this-> document_root['dirname'];
		
		require_once $this-> document_root . '/lib/plugins/twcheckliste/config.php';

		if ($_REQUEST['btn_speichern']) {
			$conf["logo"] = $_REQUEST['logo'];
			$conf["farbcode"] = $_REQUEST['farbcode'];
			$conf["footertext"] = $_REQUEST['footertext'];
		}

		echo '
<div class="plugin_twcheckliste_form">         
	<h1>' . $this -> getLang('menu') . '</h1>  
	<form action="" method="get" id="plugin_twcheckliste_form">
		<input type="hidden" name="do" value="admin" />
		<input type="hidden" name="page" value="twcheckliste" />
		<input type="hidden" name="sectok" value="' . getSecurityToken() . '" />
		<fieldset>
			<legend>
				' . $this -> getLang('label_config') . '
			</legend>
			<label class="block"><span>' . $this -> getLang('label_logo') . '</span>
				<input type="text" size="50" class="edit" value="' . $conf['logo'] . '" name="logo">
			</label>
			<br>
			<label class="block"><span>' . $this -> getLang('label_farbcode') . '</span>
				<input type="text" size="50" class="edit" value="' . $conf['farbcode'] . '" name="farbcode">
			</label>
			<br>
			<label class="block"><span>' . $this -> getLang('label_footertext') . '</span>
				<input type="text" size="50" class="edit" value="' . $conf['footertext'] . '" name="footertext">
			</label>
			<br>
			<input type="submit" class="button" name="btn_speichern" value="' . $this -> getLang('btn_save') . '">
		</fieldset>
	</form>
</div>';

	}

}

// vim:ts=4:sw=4:et:enc=utf-8:

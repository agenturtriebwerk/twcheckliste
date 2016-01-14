<?php
/**
 * Plugin twcheckliste:
 * v1.0
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author   web@agentur-triebwerk.de
 */

if (!defined('DOKU_INC'))
	define('DOKU_INC', realpath(dirname(__FILE__) . '/../../') . '/');
if (!defined('DOKU_PLUGIN'))
	define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');

require_once (DOKU_INC . 'inc/parserutils.php');
require_once (DOKU_PLUGIN . 'syntax.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_twcheckliste extends DokuWiki_Syntax_Plugin {

	function getType() {
		return 'container';
	}

	function getPType() {
		return 'stack';
	}

	function getAllowedTypes() {
		return array('container', 'baseonly', 'substition', 'protected', 'disabled', 'formatting', 'paragraphs');
	}

	function getSort() {
		return 189;
	}

	function accepts($mode) {
		if ($mode == substr(get_class($this), 7))
			return true;
		return parent::accepts($mode);
	}

	function connectTo($mode) {
		$this -> Lexer -> addEntryPattern('<checkliste.*?>(?=.*?</checkliste>)', $mode, 'plugin_twcheckliste');
	}

	function postConnect() {
		$this -> Lexer -> addExitPattern('</checkliste>', 'plugin_twcheckliste');
	}

	function handle($match, $state, $pos, &$handler) {

		switch ($state) {
			case DOKU_LEXER_ENTER :
				$return = array('active' => 'true', 'element' => Array(), 'onHidden' => '', 'onVisible' => '', 'initialState' => 'hidden', 'state' => $state, 'printHead' => true, 'bytepos_start' => $pos, 'edit' => false, 'editText' => $this -> getLang('edit'), 'onExportPdf' => '');
				$match = substr($match, 11, -1);
				if (trim($match) == "angebot") {
					$this -> match_kind = 1;
				} else {
					$this -> match_kind = 0;
				}

				return $return;

			case DOKU_LEXER_UNMATCHED :
				$html = $this -> replaceTags($match);
				print_r($match);
				print_r($html);
				return array($state, $html);
			default :
				return array('state' => $state, 'bytepos_end' => $pos + strlen($match));
		}
	}

	private function _grepOption(&$options, $tag, &$match) {
		preg_match("/$tag *= *\"([^\"]*)\" ?/i", $match, $text);
		if (count($text) != 0) {
			$match = str_replace($text[0], '', $match);
			$options[$tag] = $text[1];
		}
	}

	function render($mode, &$renderer, $data) {

		if ($mode == 'xhtml') {
			switch ($data['state']) {
				case DOKU_LEXER_ENTER :

					$renderer -> doc .= '<link href="/lib/plugins/twcheckliste/style.css" type="text/css" rel="stylesheet" /><form action="lib/plugins/twcheckliste/theme_twCheckliste/tw_checklist.php" method="post" target="_blank"><div class="checkliste" id="checkliste"><input type="hidden" name="match_kind" value="' . $this -> match_kind . '" />';
					break;

				case DOKU_LEXER_UNMATCHED :
					$text = $renderer -> _xmlEntities($data['text']);
					if (preg_match("/^[ \t]*={2,}[^\n]+={2,}[ \t]*$/", $text, $match)) {
						$title = trim($match[0]);
						$level = 7 - strspn($title, '=');
						if ($level < 1)
							$level = 1;
						$title = trim($title, '=');
						$title = trim($title);
						$renderer -> header($title, $level, 0);
					} else {
						$renderer -> doc .= $text;
					}
					
					break;
 
				case DOKU_LEXER_EXIT :
					$renderer -> doc .= '</div><br /><div style="color: red;"><b>Wegen aktuellen Bug im Druck von Webkit Browser bitte Firefox verwenden !!!</b></div><br /><input type="submit" class="button" name="checkliste" value="'.$this -> getLang('btn_generieren').'" /> </form>';
					$renderer -> doc = str_replace ("ROLLE_PM<", "<span class='rolle_pm'>ROLLE_PM</span><", $renderer -> doc);
					$renderer -> doc = str_replace ("UNCHECKED<", "<span class='unchecked'>UNCHECKED</span><", $renderer -> doc);							
							 
					//close hiddenBody and hiddenGlobal
					/*if (array_pop($this -> editableBlocks)) {
					 $renderer -> finishSectionEdit($data['bytepos_end']);
					 }*/
					break;
			}
			return true;
		}

		return false;
	}

	function replaceTags($match) {

		$search = array('#CLsection#i', '#CLarea#i', '#CLcheckpoint#i', '#CLcoption#i');

		$replace = array('h2', 'h3', 'h4', 'li');

		return preg_replace($search, $replace, $match);
	}

}

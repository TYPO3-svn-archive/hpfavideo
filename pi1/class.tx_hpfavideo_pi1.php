<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Hauke Hain <hhpreuss@googlemail.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'FAVideo player' for the 'hpfavideo' extension.
 *
 * @author	Hauke Hain <hhpreuss@googlemail.com>
 * @package	TYPO3
 * @subpackage	tx_hpfavideo
 */
class tx_hpfavideo_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_hpfavideo_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_hpfavideo_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'hpfavideo';	// The extension key.
	var $pi_checkCHash = true;
	var $uploadFolder  = 'uploads/tx_hpfavideo/';
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		$this->siteRelPath = t3lib_extMgm::siteRelPath($this->extKey);
		$this->basePath = $this->conf['basePath'];
		$this->playerID = 'playerID' . $this->cObj->data['uid'];

		// if no valid URL is set by TypoScript use server value
		if (!t3lib_div::isValidUrl($this->basePath)) {
			$this->basePath = 'http://'.$GLOBALS['_SERVER']['HTTP_HOST'];
		}

		// check wheather DAM is loaded
		$this->DAMloaded = t3lib_extMgm::isLoaded('dam');

		// load flexform values
		$this->pi_initPIflexForm();

		// sheet video
		$this->flvfile = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'flvfile', 'video');
		$this->previmg = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'previmg', 'video');
		$this->playerwidth = (int) $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'playerwidth', 'video');
		$this->playerheight = (int) $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'playerheight', 'video');
		$this->videoScaleMode = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'videoScaleMode', 'video');

		if ($this->DAMloaded) {
			$this->flvfiledam = tx_dam_db::getReferencedFiles('tt_content', $this->cObj->data['uid'], 'hpfavideo_flv', 'tx_dam_mm_ref');
			$this->previmgdam = tx_dam_db::getReferencedFiles('tt_content', $this->cObj->data['uid'], 'hpfavideo_img', 'tx_dam_mm_ref');
		}
 
		// sheet display
		$this->skin = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'skin', 'display');
		$this->controlColor = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'controlColor', 'display');
		$this->backgroundColor = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'backgroundColor', 'display');
		$this->videoAlign = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'videoAlign', 'display');
		$this->skinAutoHide = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'skinAutoHide', 'display');
		$this->skinVisible = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'skinVisible', 'display');

		// sheet options
		$this->clickToTogglePlay = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'clickToTogglePlay', 'options');
		$this->autoPlay = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'autoPlay', 'options');
		$this->autoLoad = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'autoLoad', 'options');
		$this->volume = (int) $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'volume', 'options');

		//return $this->pi_wrapInBaseClass($this->showPlayer());
		return $this->showPlayer();
	}

	private function showPlayer() {
		if (false) {
			//TODO: check if everything is set
		} else {
			// include JS files
			$GLOBALS['TSFE']->pSetup['includeJSFooter.'][$this->extKey . '_favideo']  = $this->siteRelPath . 'res/FAVideo.js';
			$GLOBALS['TSFE']->pSetup['includeJSFooter.'][$this->extKey . '_AC_RunActiveContent']  = $this->siteRelPath . 'res/AC_RunActiveContent.js';

			// include JavaScript for initialisating the player
			$GLOBALS['TSFE']->pSetup['jsFooterInline.']['815'] = 'TEXT';
			$GLOBALS['TSFE']->pSetup['jsFooterInline.']['815.']['value'] = $this->getPlayerInitialisationJSCode();
	
			// Div for player insertion with error message in case JavaScript is not enabled
			$content =	'<div id="' . $this->playerID . 'Ident">' .
							$this->cObj->stdWrap($this->pi_getLL('noJavaScript'), $this->conf['errorWrap.']) .
						'</div>';
		}

		return $content;
	}

	private function getPlayerInitialisationJSCode() {
		// These options are currently not used: 
		// playHeadTime, totalTime, bufferTime, playheadUpdateInterval
		$options = 'autoLoad:';

		if ($this->autoLoad) {
			$options .= 'true';
		} else {
			$options .= 'false';
		}

		$options .= ', autoPlay:';

		if ($this->autoPlay) {
			$options .= 'true';
		} else {
			$options .= 'false';
		}

		if ($this->volume > 0) {
			$options .= ', volume:' . $this->volume;
		}

		$options .= ', skinAutoHide:';

		if ($this->skinAutoHide) {
			$options .= 'true';
		} else {
			$options .= 'false';
		}

		$options .= ', skinVisible:';

		if ($this->skinVisible) {
			$options .= 'true';
		} else {
			$options .= 'false';
		}

		$options .= ', clickToTogglePlay:';

		if ($this->clickToTogglePlay) {
			$options .= 'true';
		} else {
			$options .= 'false';
		}

		if (strlen($this->skinPath) > 20) {
			$options .= ', skinPath:\'' . $this->skinPath . '\'';
		}

		$options .= ', videoScaleMode:\'' . $this->videoScaleMode . '\'';
		$options .= ', videoAlign:\'' . $this->videoAlign . '\'';
		$options .= ', previewImagePath:\'' .
					$this->getFirstVideoPreviewImage() . '\'';

		$js =	$this->playerID .
				' = new FAVideo(\'' . $this->playerID . 'Ident\', ' .
				'\'' . $this->getFirstVideo() . '\', ' . $this->playerwidth .
				', ' . $this->playerheight . ' , { ' . $options . ' }, \'' .
				$this->basePath. '/' . $this->siteRelPath . 'res/\');';

		return $js;
	}

	private function getFirstVideo() {
		$filePath = $this->uploadFolder . $this->flvfile;

		if ($this->DAMloaded) {
			$filePath = current($this->flvfiledam['files']);
		}

		return $this->basePath. '/' . $filePath;
	}

	private function getFirstVideoPreviewImage() {
		$filePath = $this->uploadFolder . $this->previmg;

		if ($this->DAMloaded) {
			$filePath = current($this->previmgdam['files']);
		}

		return $this->basePath. '/' . $filePath;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/hpfavideo/pi1/class.tx_hpfavideo_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/hpfavideo/pi1/class.tx_hpfavideo_pi1.php']);
}

?>
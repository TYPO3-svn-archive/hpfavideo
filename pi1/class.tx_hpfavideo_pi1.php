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
 *
 *
 *   55: class tx_hpfavideo_pi1 extends tslib_pibase
 *   69:     function main($content, $conf)
 *  154:     private function showPlayer()
 *  199:     private function getPlayerInitialisationJSCode()
 *  318:     private function getFirstVideo()
 *  333:     private function getFirstVideoPreviewImage()
 *  348:     private function getPlaylistJavaScriptFunctions()
 *  366:     private function getPlaylist()
 *  399:     private function getVideosInformationArray()
 *  427:     private function getSingle($data, $ts)
 *
 * TOTAL FUNCTIONS: 9
 * (This index is automatically created/updated by the extension "extdeveval")
 *
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
	var $prefixId		  = 'tx_hpfavideo_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_hpfavideo_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey				= 'hpfavideo';	// The extension key.
	var $pi_checkCHash = true;
	var $uploadFolder  = 'uploads/tx_hpfavideo/';

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	string		The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		$this->siteRelPath = t3lib_extMgm::siteRelPath($this->extKey);
		$this->playerID = 'playerID' . $this->cObj->data['uid'];

		// load TypoScript values
		$this->basePath = $this->conf['basePath'];

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
		$this->skinPath = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'skin', 'display');
		$this->controlColor = '0x' . str_replace('#', '', $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'controlColor', 'display'));
		$this->backgroundColor = str_replace('#', '', $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'backgroundColor', 'display'));
		$this->videoAlign = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'videoAlign', 'display');
		$this->skinAutoHide = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'skinAutoHide', 'display');
		$this->skinVisible = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'skinVisible', 'display');

		// sheet options
		$this->clickToTogglePlay = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'clickToTogglePlay', 'options');
		$this->autoPlay = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'autoPlay', 'options');
		$this->autoLoad = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'autoLoad', 'options');
		$this->volume = (int) $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'volume', 'options');

 		// overwrite properties
 		if ($this->videoScaleMode == 'default') {
			 $this->videoScaleMode = $this->conf['videoScaleMode'];
		}

 		if ($this->videoAlign == 'default') {
			 $this->videoAlign = $this->conf['videoAlign'];
		}

 		if ($this->skinPath == 'default') {
			 $this->skinPath = $this->conf['skinPath'];
		} else {
			$this->skinPath = 	$this->basePath. '/' . $this->siteRelPath .
								'res/skins/' . $this->skinPath;
		}

 		if ($this->volume == 0) {
			 $this->volume = (int) $this->conf['volume'];
		}

 		if (strlen($this->controlColor) < 8) {
			 $this->controlColor = $this->conf['controlColor'];
		}

 		if (strlen($this->backgroundColor) < 6) {
			 $this->backgroundColor = $this->conf['backgroundColor'];
		}

		//return $this->pi_wrapInBaseClass($this->showPlayer());
		return $this->showPlayer();
	}

	/**
	 * Includes all needed JavaScript and created needed HTML.
	 *
	 * @return	string		HTML
	 */
	private function showPlayer() {
		// Print error if no flv file is set
		if (strlen($this->flvfile) < 1 && strlen(current($this->flvfiledam['files'])) < 1) {
			$content = $this->cObj->stdWrap($this->pi_getLL('noFlvFile'), $this->conf['errorWrap.']);
		} else {
			// include JS files
			$GLOBALS['TSFE']->pSetup['includeJSFooter.'][$this->extKey . '_favideo']  = $this->siteRelPath . 'res/FAVideo.js';
			$GLOBALS['TSFE']->pSetup['includeJSFooter.'][$this->extKey . '_AC_RunActiveContent']  = $this->siteRelPath . 'res/AC_RunActiveContent.js';

			// include JavaScript for initialisating the player
			$GLOBALS['TSFE']->pSetup['jsFooterInline.']['815'] = 'TEXT';
			$GLOBALS['TSFE']->pSetup['jsFooterInline.']['815.']['value'] = $this->getPlayerInitialisationJSCode();

			// Div for player insertion with error message in case JavaScript is not enabled
			$player =	'<div id="' . $this->playerID . 'Ident">' .
							$this->cObj->stdWrap($this->pi_getLL('noJavaScript'), $this->conf['errorWrap.']) .
						'</div>';

			// show playlist
			if ($this->DAMloaded) {
				$playlist = $this->getPlaylist();
				$GLOBALS['TSFE']->pSetup['jsFooterInline.']['815.']['value'] .= $this->getPlaylistJavaScriptFunctions();
			}

			// if Typoscript alignment  is set use it
			if (isset($this->conf['alignment']) && isset($this->conf['alignment.'])) {
				$content .= $this->getSingle(
							array(
								'player'	=> $player,
								'playlist'	=> $playlist
								),
							'alignment');
			} else { // otherwise output the player and then the playlist
				$content .= $player . $playlist;
			}
		}

		return $content;
	}

	/**
	 * Initialises the player via JavaScript with all options.
	 *
	 * @return	string		JavaScript to initialise the player
	 */
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

		if (strlen($this->skinPath) > 10) {
			$options .= ', skinPath:\'' . $this->skinPath . '\'';
		}

		switch ($this->videoScaleMode) {
			case 'maintainAspectRatio':
			case 'exactFit':
			case 'noScale':
				$options .= ', videoScaleMode:\'' . $this->videoScaleMode . '\'';
				break;
		}

		switch ($this->videoAlign) {
			case 'bottom':
			case 'bottomLeft':
			case 'bottomRight':
			case 'top':
			case 'topLeft':
			case 'topRight':
			case 'center':
			case 'right':
			case 'left':
				$options .= ', videoAlign:\'' . $this->videoAlign . '\'';
				break;
		}

		$options .= ', previewImagePath:\'' .
					$this->getFirstVideoPreviewImage() . '\'';

		if (strlen($this->backgroundColor) <> 6) {
			$this->backgroundColor = '000000';
		}

		$js =	$this->playerID .
				' = new FAVideo(\'' . $this->playerID . 'Ident\', ' .
				'\'' . $this->getFirstVideo() . '\', ' . $this->playerwidth .
				', ' . $this->playerheight . ' , { ' . $options . ' }, \'' .
				$this->basePath. '/' . $this->siteRelPath . 'res/\', \'' .
				$this->backgroundColor . '\');';

		if (strlen($this->controlColor) == 8) {
			$js .=  $this->playerID .
					'.setThemeColor(\'' . $this->controlColor . '\');';
		}

		// Loading problems (not ready DOM):
		// Register Listeners and unregister it after it is not needed anymore
		// if the player plays the video it gets scaled (again)
		$js .=	$this->playerID .'.addEventListener(\'stateChange\', this, stateChange);' .
					'function stateChange(event) {' .
						'if (event.state ==  \'playing\') {' . $this->playerID .
							'.setVideoScaleMode(' . $this->playerID .
								'.getVideoScaleMode());' .
							$this->playerID . '.removeEventListener(\'stateChange\', this, stateChange);' .
						'}' .
					'}';
		// after the initialisation the color theme gets applied again and the
		$js .=	$this->playerID .'.addEventListener(\'init\', this, init);' .
					'function init(event) {' .
							$this->playerID . '.setThemeColor(' .
								$this->playerID . '.getThemeColor());' .
						$this->playerID .'.removeEventListener(\'init\', this, init);' .
					'}';

		return $js;
	}

	/**
	 * Returns the path of path to the first video
	 *
	 * @return	string		path to the first video
	 */
	private function getFirstVideo() {
		$filePath = $this->uploadFolder . $this->flvfile;

		if ($this->DAMloaded) {
			$filePath = current($this->flvfiledam['files']);
		}

		return $this->basePath. '/' . $filePath;
	}

	/**
	 * Returns the path of path to the first preview image.
	 *
	 * @return	string		path to the first preview image
	 */
	private function getFirstVideoPreviewImage() {
		$filePath = $this->uploadFolder . $this->previmg;

		if ($this->DAMloaded) {
			$filePath = current($this->previmgdam['files']);
		}

		return $this->basePath. '/' . $filePath;
	}

	/**
	 * Returns JavaScript functions needed by the playlist.
	 *
	 * @return	string		JavaScript functions
	 */
	private function getPlaylistJavaScriptFunctions() {
		return 	'function loadJAVideo(videourl, imgurl) {' .
					$this->playerID.'.setPreviewImagePath(imgurl);' .
					$this->playerID.'.load(videourl);' .
					$this->playerID .
					'.addEventListener(\'stateChange\', this, stateChange);' .
				'}' .
				'function playJAVideo(videourl, imgurl) {' .
					'loadJAVideo(videourl, imgurl);' .
					$this->playerID.'.play(videourl);' .
				'}';
	}

	/**
	 * Returns the TypoScript created playlist
	 *
	 * @return	string		HTML playlist created by TypoScript
	 */
	private function getPlaylist() {
		$videos = $this->getVideosInformationArray();
		$playlist = '';

		// create a playlist only if there are at least 2 videos
		if (sizeof($videos) > 1) {
			if (isset($this->conf['playlist']) && isset($this->conf['playlist.']) && isset($this->conf['playlistItem']) && isset($this->conf['playlistItem.'])) {
				$playlistElements = '';

				foreach ($videos as $video) {
					$playlistElements .= $this->getSingle($video, 'playlistItem');
				}

				$playlist = $this->getSingle(
							array(
								'title'			=> $this->pi_getLL('playlistTitle'),
								'description'	=> $this->pi_getLL('playlistDescription'),
								'playlistItems'	=> $playlistElements
								),
							'playlist');
			} else {
				$playlist = $this->cObj->stdWrap($this->pi_getLL('noTypoScriptSet'), $this->conf['errorWrap.']);
			}
		}

		return $playlist;
	}

	/**
	 * Combines the DAM arrays for the image und video and returns only the needed information.
	 *
	 * @return	array		Array with the title and path of the video, path of the preview image and the DAm uid of both.
	 */
	private function getVideosInformationArray() {
		$videos = array();
		$i = 0;

		foreach ($this->flvfiledam['rows'] as $uid => $video) {
			$videos[$i++] = array(
							'title' 	=> $video['title'],
							'videopath' => $this->basePath. '/' . $this->flvfiledam['files'][$uid],
							'videouid' => $uid);
		}

		$i = 0;

		foreach ($this->previmgdam['rows'] as $uid => $bild) {
			$videos[$i]['imageuid'] = $uid;
			$videos[$i++]['imagepath'] = $this->basePath. '/' . $this->previmgdam['files'][$uid];
		}

		return $videos;
	}

	/**
	 * Parses data through typoscript.
	 *
	 * @param	array		$data: Data which will be passed to the typoscript.
	 * @param	string		$ts: The typoscript which will be called.
	 * @return	[type]		...
	 */
	private function getSingle($data, $ts) {
		$cObj = t3lib_div::makeInstance('tslib_cObj');

		//Set the data array in the local cObj. This data will be available in the ts. E.G. {field:[fieldName]} or field = [fieldName]
		$cObj->data = $data;

		//Parse and return the result.
		return $cObj->cObjGetSingle($this->conf[$ts], $this->conf[$ts.'.']);
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/hpfavideo/pi1/class.tx_hpfavideo_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/hpfavideo/pi1/class.tx_hpfavideo_pi1.php']);
}

?>
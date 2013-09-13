<?php

########################################################################
# Extension Manager/Repository config file for ext "hpfavideo".
#
# Auto generated 14-11-2010 18:33
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Flash Ajax driven videoplayer (FAVideo)',
	'description' => 'Easy to use implementation of the Flash JavaScript player "FAVideo".
More information: http://opensource.adobe.com/wiki/display/favideo/Flash-Ajax+Video+Component',
	'category' => 'plugin',
	'author' => 'Hauke Hain',
	'author_email' => 'hhpreuss@googlemail.com',
	'shy' => '',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => 'uploads/tx_hpfavideo/',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '1.1.2',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'typo3' => '4.4-6.2.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'dam' => '1.1.5',
		),
	),
	'_md5_values_when_last_written' => 'a:77:{s:9:"ChangeLog";s:4:"55ac";s:10:"README.txt";s:4:"6e6c";s:12:"ext_icon.gif";s:4:"637b";s:17:"ext_localconf.php";s:4:"86e6";s:14:"ext_tables.php";s:4:"4987";s:13:"locallang.xml";s:4:"acbc";s:16:"locallang_db.xml";s:4:"2c4a";s:14:"doc/manual.sxw";s:4:"b2f5";s:14:"pi1/ce_wiz.gif";s:4:"d2c2";s:30:"pi1/class.tx_hpfavideo_pi1.php";s:4:"3718";s:38:"pi1/class.tx_hpfavideo_pi1_wizicon.php";s:4:"7429";s:13:"pi1/clear.gif";s:4:"cc11";s:19:"pi1/flexform_ds.xml";s:4:"6872";s:17:"pi1/locallang.xml";s:4:"8308";s:26:"res/AC_RunActiveContent.js";s:4:"ddbe";s:15:"res/FAVideo.fla";s:4:"6be3";s:14:"res/FAVideo.js";s:4:"a680";s:15:"res/FAVideo.swf";s:4:"7b28";s:40:"res/classes/com/adobe/favideo/FAVideo.as";s:4:"2480";s:43:"res/classes/com/adobe/favideo/VideoAlign.as";s:4:"cd24";s:47:"res/classes/com/adobe/favideo/VideoScaleMode.as";s:4:"66e6";s:43:"res/classes/com/adobe/favideo/VideoState.as";s:4:"28d2";s:51:"res/classes/com/adobe/favideo/managers/UIManager.as";s:4:"dacb";s:50:"res/classes/com/adobe/favideo/utils/IdleWatcher.as";s:4:"51cd";s:43:"res/classes/com/adobe/favideo/utils/Tick.as";s:4:"03d9";s:47:"res/classes/com/adobe/favideo/views/BaseView.as";s:4:"77db";s:44:"res/classes/com/adobe/favideo/views/Image.as";s:4:"385c";s:31:"res/skins/ArcticExternalAll.swf";s:4:"729e";s:33:"res/skins/ArcticExternalNoVol.swf";s:4:"c06e";s:36:"res/skins/ArcticExternalPlayMute.swf";s:4:"e687";s:40:"res/skins/ArcticExternalPlaySeekMute.swf";s:4:"08ab";s:27:"res/skins/ArcticOverAll.swf";s:4:"d198";s:29:"res/skins/ArcticOverNoVol.swf";s:4:"6511";s:32:"res/skins/ArcticOverPlayMute.swf";s:4:"9a31";s:36:"res/skins/ArcticOverPlaySeekMute.swf";s:4:"ad60";s:30:"res/skins/ClearExternalAll.swf";s:4:"0e3e";s:32:"res/skins/ClearExternalNoVol.swf";s:4:"a196";s:35:"res/skins/ClearExternalPlayMute.swf";s:4:"cf4c";s:39:"res/skins/ClearExternalPlaySeekMute.swf";s:4:"fe19";s:26:"res/skins/ClearOverAll.swf";s:4:"c5f0";s:28:"res/skins/ClearOverNoVol.swf";s:4:"d4a6";s:31:"res/skins/ClearOverPlayMute.swf";s:4:"509a";s:35:"res/skins/ClearOverPlaySeekMute.swf";s:4:"19ea";s:31:"res/skins/MojaveExternalAll.swf";s:4:"f9c6";s:33:"res/skins/MojaveExternalNoVol.swf";s:4:"84a9";s:36:"res/skins/MojaveExternalPlayMute.swf";s:4:"d27f";s:40:"res/skins/MojaveExternalPlaySeekMute.swf";s:4:"5c16";s:27:"res/skins/MojaveOverAll.swf";s:4:"0759";s:29:"res/skins/MojaveOverNoVol.swf";s:4:"3918";s:32:"res/skins/MojaveOverPlayMute.swf";s:4:"d097";s:36:"res/skins/MojaveOverPlaySeekMute.swf";s:4:"3600";s:30:"res/skins/SteelExternalAll.swf";s:4:"5f52";s:32:"res/skins/SteelExternalNoVol.swf";s:4:"b2b2";s:35:"res/skins/SteelExternalPlayMute.swf";s:4:"a090";s:39:"res/skins/SteelExternalPlaySeekMute.swf";s:4:"eab5";s:26:"res/skins/SteelOverAll.swf";s:4:"f45d";s:28:"res/skins/SteelOverNoVol.swf";s:4:"8dc3";s:31:"res/skins/SteelOverPlayMute.swf";s:4:"7c43";s:35:"res/skins/SteelOverPlaySeekMute.swf";s:4:"b1cf";s:49:"res/skins/javascript/simpleSkin/controlBar_bg.gif";s:4:"45d8";s:60:"res/skins/javascript/simpleSkin/controlBar_nextButtonOff.gif";s:4:"4bc1";s:59:"res/skins/javascript/simpleSkin/controlBar_nextButtonOn.gif";s:4:"219b";s:61:"res/skins/javascript/simpleSkin/controlBar_pauseButtonOff.gif";s:4:"e872";s:60:"res/skins/javascript/simpleSkin/controlBar_pauseButtonOn.gif";s:4:"232f";s:60:"res/skins/javascript/simpleSkin/controlBar_playButtonOff.gif";s:4:"712b";s:59:"res/skins/javascript/simpleSkin/controlBar_playButtonOn.gif";s:4:"45e1";s:60:"res/skins/javascript/simpleSkin/controlBar_prevButtonOff.gif";s:4:"520c";s:59:"res/skins/javascript/simpleSkin/controlBar_prevButtonOn.gif";s:4:"dc80";s:56:"res/skins/javascript/simpleSkin/controlBar_roundLeft.gif";s:4:"00cb";s:57:"res/skins/javascript/simpleSkin/controlBar_roundRight.gif";s:4:"cc28";s:60:"res/skins/javascript/simpleSkin/controlBar_stopButtonOff.gif";s:4:"f2e3";s:59:"res/skins/javascript/simpleSkin/controlBar_stopButtonOn.gif";s:4:"6442";s:62:"res/skins/javascript/simpleSkin/controlBar_volumeButtonOff.gif";s:4:"0cf0";s:61:"res/skins/javascript/simpleSkin/controlBar_volumeButtonOn.gif";s:4:"3332";s:61:"res/skins/javascript/simpleSkin/controlBar_volumeMutedOff.gif";s:4:"33ec";s:60:"res/skins/javascript/simpleSkin/controlBar_volumeMutedOn.gif";s:4:"0e5f";s:31:"static/simplePlaylist/setup.txt";s:4:"1f8b";}',
	'suggests' => array(
	),
);

?>
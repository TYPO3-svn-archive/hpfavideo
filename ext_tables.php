<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}


$version=class_exists('t3lib_utility_VersionNumber')
	? t3lib_utility_VersionNumber::convertVersionNumberToInteger(TYPO3_version)
	: t3lib_div::int_from_ver(TYPO3_version);
if ($version < 6001000) {
	t3lib_div::loadTCA('tt_content');
}

$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages';


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:hpfavideo/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');


if (TYPO3_MODE == 'BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_hpfavideo_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_hpfavideo_pi1_wizicon.php';
}

$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'. $_EXTKEY. '/pi1/flexform_ds.xml');

t3lib_extMgm::addStaticFile($_EXTKEY, 'static/simplePlaylist', 'hpfavideo: Simple Playlist');

?>
<?php
if ( function_exists( 'wfLoadExtension' ) ) {
	wfLoadExtension( 'AddBodyClass' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['AddBodyClass'] = __DIR__ . '/i18n';
	$wgExtensionMessagesFiles['AddBodyClassMagic'] = __DIR__ . '/' . 'AddBodyClass.i18n.magic.php';
	wfWarn(
		'Deprecated PHP entry point used for the AddBodyClass extension. ' .
		'Please use wfLoadExtension instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	);
	return;
} else {
	die( 'This version of the AddBodyClass extension requires MediaWiki 1.25+' );
}

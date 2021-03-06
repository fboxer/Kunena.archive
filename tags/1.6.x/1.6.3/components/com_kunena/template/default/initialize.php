<?php
/**
* @version $Id$
* Kunena Component
* @package Kunena
*
* @Copyright (C) 2011 Kunena Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.kunena.org
**/
defined( '_JEXEC' ) or die();

$document = JFactory::getDocument();
$template = KunenaFactory::getTemplate();
$this->params = $template->params;

// Template requires Mootools 1.2 framework
$template->loadMootools();

// We load mediaxboxadvanced library
CKunenaTools::addStyleSheet ( KUNENA_DIRECTURL . 'js/mediaboxadvanced/css/mediaboxAdv.css');
CKunenaTools::addScript( KUNENA_DIRECTURL . 'js/mediaboxadvanced/js/mediaboxAdv.js' );

// New Kunena JS for default template
// TODO: Need to check if selected template has an override
CKunenaTools::addScript ( KUNENA_DIRECTURL . 'template/default/js/default-min.js' );

$skinner = $this->params->get('enableSkinner', 0);

if (file_exists ( KUNENA_JTEMPLATEPATH .DS. 'css' .DS. 'kunena.forum.css' )) {
	// Load css from Joomla template
	CKunenaTools::addStyleSheet ( KUNENA_JTEMPLATEURL . 'css/kunena.forum-min.css' );

	if ($skinner && file_exists ( KUNENA_JTEMPLATEPATH . '/css/kunena.skinner.css' )){
		CKunenaTools::addStyleSheet ( KUNENA_JTEMPLATEURL . 'css/kunena.skinner-min.css' );
	} elseif (!$skinner && file_exists ( KUNENA_JTEMPLATEPATH . '/css/kunena.default.css' )) {
		CKunenaTools::addStyleSheet ( KUNENA_JTEMPLATEURL . 'css/kunena.default-min.css' );
	}
} else if (file_exists ( KUNENA_ABSTMPLTPATH .DS. 'css' .DS. 'kunena.forum.css' )){
	// Load css from the current template
	CKunenaTools::addStyleSheet ( KUNENA_TMPLTCSSURL );

	if ($skinner && file_exists ( KUNENA_ABSTMPLTPATH . '/css/kunena.skinner.css' )){
		CKunenaTools::addStyleSheet ( KUNENA_TMPLTURL . 'css/kunena.skinner-min.css' );
	} elseif (!$skinner && file_exists ( KUNENA_ABSTMPLTPATH . '/css/kunena.default.css' )) {
		CKunenaTools::addStyleSheet ( KUNENA_TMPLTURL . 'css/kunena.default-min.css' );
	}
} else {
	// Load css from default template
	CKunenaTools::addStyleSheet ( KUNENA_DIRECTURL . 'template/default/css/kunena.forum-min.css' );
	if ($skinner){
		CKunenaTools::addStyleSheet ( KUNENA_DIRECTURL . 'template/default/css/kunena.skinner-min.css' );
	} else {
		CKunenaTools::addStyleSheet ( KUNENA_DIRECTURL . 'template/default/css/kunena.default-min.css' );
	}
}
$cssurl = JURI::base() . "components/com_kunena/template/default/css";
?>
<!--[if lte IE 7]>
<link rel="stylesheet" href="<?php echo $cssurl; ?>/kunena.forum.ie7.css" type="text/css" />
<![endif]-->
<?php
$mediaurl = JURI::base() . "components/com_kunena/template/default/media";

$styles = <<<EOF
	/* Kunena Custom CSS */
EOF;

$forumHeader = $this->params->get('forumHeadercolor', $skinner ? '' : '#5388B4');

if ($forumHeader) {
	$styles .= <<<EOF
	
	#Kunena div.kblock > div.kheader { background: {$forumHeader} !important; }
	#Kunena #ktop { border-color: {$forumHeader}; }
	#Kunena #ktop span.ktoggler { background: {$forumHeader}; }
	#Kunena #ktab a:hover,
	#Kunena #ktab li.Kunena-item-active a	{ background-color: {$forumHeader}; }
	#Kunena #ktab ul.menu li.active a { background-color: {$forumHeader}; }
	#Kunena a:link,
	#Kunena a:visited,
	#Kunena a:active {color: {$forumHeader};}
	#Kunena a:focus {outline: none;}
	#Kunena a:hover {color: #FF0000;}
EOF;
}

$forumLink = $this->params->get('forumLinkcolor', $skinner ? '' : '#5388B4');

if ($forumLink) {
	$styles .= <<<EOF
	#Kunena a:link,
	#Kunena a:visited,
	#Kunena a:active {color: {$forumLink};}
	#Kunena a:focus {outline: none;}
EOF;
}

$announcementHeader = $this->params->get('announcementHeadercolor', $skinner ? '' : '#5388B4');

if ($announcementHeader) {
	$styles .= <<<EOF
	#Kunena div.kannouncement div.kheader { background: {$announcementHeader} !important; }
EOF;
}

$announcementBox = $this->params->get('announcementBoxbgcolor', $skinner ? '' : '#FFFFFF');

if ($announcementBox) {
	$styles .= <<<EOF
	#Kunena div#kannouncement .kanndesc { background: {$announcementBox}; }
EOF;
}

$frontStatsHeader = $this->params->get('frontstatsHeadercolor', $skinner ? '' : '#5388B4');

if ($frontStatsHeader) {
	$styles .= <<<EOF
	#Kunena div.kfrontstats div.kheader { background: {$frontStatsHeader} !important; }
EOF;
}

$onlineHeader = $this->params->get('whoisonlineHeadercolor', $skinner ? '' : '#5388B4');

if ($onlineHeader) {
	$styles .= <<<EOF
	#Kunena div.kwhoisonline div.kheader { background: {$onlineHeader} !important; }
EOF;
}

$styles .= <<<EOF
	#Kunena .kicon-profile { background-image: url("{$mediaurl}/iconsets/profile/{$this->params->get('profileIconset', 'default')}/default.png"); }
	#Kunena .kicon-button { background-image: url("{$mediaurl}/iconsets/buttons/{$this->params->get('buttonIconset', 'default')}/default.png"); }
	#Kunena #kbbcode-toolbar li a,#Kunena #kattachments a { background-image:url("{$mediaurl}/iconsets/editor/{$this->params->get('editorIconset', 'default')}/default.png"); }
	/* End of Kunena Custom CSS */
EOF;

$document->addStyleDeclaration($styles);
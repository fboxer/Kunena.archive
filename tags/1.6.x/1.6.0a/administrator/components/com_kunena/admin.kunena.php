<?php
/**
 * @version $Id$
 * Kunena Component
 * @package Kunena
 *
 * @Copyright (C) 2008 - 2010 Kunena Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.com
 *
 * Based on FireBoard Component
 * @Copyright (C) 2006 - 2007 Best Of Joomla All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.bestofjoomla.com
 *
 * Based on Joomlaboard Component
 * @copyright (C) 2000 - 2004 TSMF / Jan de Graaff / All Rights Reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author TSMF & Jan de Graaff
 **/

defined( '_JEXEC' ) or die();

JToolBarHelper::title('&nbsp;', 'kunena.png');

$view = JRequest::getCmd ( 'view' );
$task = JRequest::getCmd ( 'task' );

require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_kunena' . DS . 'api.php');
kimport('error');

require_once(KPATH_ADMIN.'/install/version.php');
$kn_version = new KunenaVersion();
if ($view == 'install' || $task == 'install' || !$kn_version->checkVersion()) {
	require_once (KPATH_ADMIN . '/install/controller.php');
	$controller = new KunenaControllerInstall();
	$controller->execute( $task );
	$controller->redirect();
	return;
}

require_once(KPATH_SITE.'/lib/kunena.defines.php');
$lang = JFactory::getLanguage();
$lang->load('com_kunena', KUNENA_PATH);
$lang->load('com_kunena', KUNENA_PATH_ADMIN);

// Now that we have the global defines we can use shortcut defines
require_once (KUNENA_PATH_LIB . DS . 'kunena.config.class.php');
require_once (KUNENA_PATH_LIB . DS . 'kunena.version.php');

$kunena_app = & JFactory::getApplication ();
$kunena_config = KunenaFactory::getConfig ();
$kunena_db = JFactory::getDBO ();

// Class structure should be used after this and all the common task should be moved to this class
require_once (KUNENA_PATH . DS . 'class.kunena.php');
require_once (KUNENA_PATH_ADMIN . DS . 'admin.kunena.html.php');

$kn_tables = CKunenaTables::getInstance ();
if ($kn_tables->installed () === false) {
	$kunena_app->enqueueMessage ( JText::_('COM_KUNENA_ERROR_INCOMPLETE_ERROR'), 'error' );
	$kunena_app->enqueueMessage ( JText::_('COM_KUNENA_ERROR_INCOMPLETE_OFFLINE'), 'notice' );
	$kunena_app->enqueueMessage ( JText::_('COM_KUNENA_ERROR_INCOMPLETE_REASONS') );
	$kunena_app->enqueueMessage ( JText::_('COM_KUNENA_ERROR_INCOMPLETE_1') );
	$kunena_app->enqueueMessage ( JText::_('COM_KUNENA_ERROR_INCOMPLETE_2') );
	$kunena_app->enqueueMessage ( JText::_('COM_KUNENA_ERROR_INCOMPLETE_3') );
	$kunena_app->enqueueMessage ( JText::_('COM_KUNENA_ERROR_INCOMPLETE_SUPPORT') . ' <a href="http://www.kunena.com">www.kunena.com</a>' );
	html_Kunena::showFbFooter ();
	return;
}

$cid = JRequest::getVar ( 'cid', array (0 ) );

if (! is_array ( $cid )) {
	$cid = array (0 );
}

$uid = JRequest::getVar ( 'uid', array (0 ) );

if (! is_array ( $uid )) {
	$uid = array ($uid );
}

$order = JRequest::getVar ( 'order', '' );

// initialise some request directives (specifically for J1.5 compatibility)
$no_html = intval ( JRequest::getVar ( 'no_html', 0 ) );
$id = intval ( JRequest::getVar ( 'id', 0 ) );

$pt_stop = "0";

if (! $no_html) {
	html_Kunena::showFbHeader ();
}

$option = JRequest::getCmd ( 'option' );

switch ($task) {
	case "new" :
		editForum ( 0, $option );

		break;

	case "edit" :
		editForum ( $cid [0], $option );

		break;

	case "edit2" :
		editForum ( $uid [0], $option );

		break;

	case "save" :
		saveForum ( $option );

		break;

	case "cancel" :
		cancelForum ( $option );

		break;

	case "publish" :
		publishForum ( $cid, 1, $option );

		break;

	case "unpublish" :
		publishForum ( $cid, 0, $option );

		break;

	case "remove" :
		deleteForum ( $cid, $option );

		break;

	case "orderup" :
		orderForum ( $cid [0], - 1, $option );

		break;

	case "orderdown" :
		orderForum ( $cid [0], 1, $option );

		break;

	case "showconfig" :
		showConfig ( $option );

		break;

	case "saveconfig" :
		saveConfig ( $option );

		break;

	case "defaultconfig" :
		defaultConfig ( $option );

		break;

	case "newmoderator" :
		newModerator ( $option, $id );

		break;

	case "addmoderator" :
		addModerator ( $option, $id, $cid, 1 );

		break;

	case "removemoderator" :
		addModerator ( $option, $id, $cid, 0 );

		break;

	case "showprofiles" :
		showProfiles ( $kunena_db, $option, $order );

		break;

	case "profiles" :
		showProfiles ( $kunena_db, $option, $order );

		break;

	case "logout" :
		logout ( $option, $uid );

		break;

	case "deleteuser" :
		deleteUser ( $option, $uid );

		break;

	case "userprofile" :
		editUserProfile ( $option, $uid );

		break;

	case "userblock" :
		userblock ( $option, $uid, 1 );

		break;

	case "userunblock" :
		userunblock ( $option, $uid, 0 );

		break;

	case "userban" :
		userban ($option, $uid, 1);
		break;

	case "userunban" :
		userunban($option, $uid, 0);
		break;

	case "trashusermessages" :
		trashUserMessages ( $option, $uid );

		break;

	case "moveusermessages" :
		moveUserMessages ( $option, $uid );

		break;

	case "moveusermessagesnow" :
		moveUserMessagesNow ( $option, $cid );

		break;

	case "showinstructions" :
		showInstructions ( $kunena_db, $option );

		break;

	case "showCss" :
		showCss ( $option );

		break;

	case "saveeditcss" :
		$file = JRequest::getVar ( 'file', 1 );
		$csscontent = JRequest::getVar ( 'csscontent', 1 );

		saveCss ( $file, $csscontent, $option );

		break;

	case "instructions" :
		showInstructions ( $kunena_db, $option );

		break;

	case "saveuserprofile" :
		saveUserProfile ( $option );

		break;

	case "pruneforum" :
		pruneforum ( $kunena_db, $option );

		break;

	case "doprune" :
		doprune ( $kunena_db, $option );

		break;

	case "douserssync" :
		douserssync ( $kunena_db, $option );

		break;

	case "syncusers" :
		syncusers ( $kunena_db, $option );

		break;

	case "browseImages" :
		browseUploaded ( $kunena_db, $option, 1 );

		break;

	case "browseFiles" :
		browseUploaded ( $kunena_db, $option, 0 );

		break;

	case "deleteImage" :
		deleteAttachment ( JRequest::getInt ( 'id', 0 ), JURI::base () . "index.php?option=$option&task=browseImages", 'COM_KUNENA_IMGDELETED');

		break;

	case "deleteFile" :
		deleteAttachment ( JRequest::getInt ( 'id', 0 ), JURI::base () . "index.php?option=$option&task=browseFiles", 'COM_KUNENA_FILEDELETED' );

		break;

	case "showAdministration" :
		showAdministration ( $option );

		break;

	case 'recount' :
		CKunenaTools::reCountUserPosts ();
		CKunenaTools::reCountBoards ();
		// Also reset the name info stored with messages
		//CKunenaTools::updateNameInfo();
		$kunena_app->redirect ( JURI::base () . 'index.php?option=com_kunena', JText::_('COM_KUNENA_RECOUNTFORUMS_DONE') );
		break;

	case "showsmilies" :
		showsmilies ( $option );

		break;

	case "uploadsmilies" :
		uploadsmilies ( $option, $cid [0] );

		break;

	case "editsmiley" :
		editsmiley ( $option, $cid [0] );

		break;

	case "savesmiley" :
		savesmiley ( $option, $id );

		break;

	case "deletesmiley" :
		deletesmiley ( $option, $cid );

		break;

	case "newsmiley" :
		newsmiley ( $option );

		break;

	case 'ranks' :
		showRanks ( $option );

		break;

	case "uploadranks" :
		uploadranks ( $option, $cid [0] );

		break;

	case "editRank" :
		editRank ( $option, $cid [0] );

		break;

	case "saveRank" :
		saveRank ( $option, $id );

		break;

	case "deleteRank" :
		deleteRank ( $option, $cid );

		break;

	case "newRank" :
		newRank ( $option );

		break;

	case "showtrashview" :
		showtrashview ( $option );

		break;

	case "showsystemreport" :
		showSystemReport ( $option );

		break;

	case "trashpurge" :
		trashpurge ( $option, $cid );

		break;

	case "deleteitemsnow" :
		deleteitemsnow ( $option, $cid );

		break;

	case "trashrestore" :
		trashrestore ( $option, $cid );

		break;

	case "pollpublish" :
		pollpublish ( $option, $cid, 1 );

		break;

	case "pollunpublish" :
		pollunpublish ( $option, $cid, 0 );

		break;

	case "createmenu" :
		CKunenaTools::createMenu();

		$kunena_app->enqueueMessage ( JText::_('COM_KUNENA_MENU_CREATED') );
		// No break! Need to display the control panel
	case 'cpanel' :
	default :
		html_Kunena::controlPanel ();
		break;
}

$kn_version_warning = $kn_version->getVersionWarning('COM_KUNENA_VERSION_INSTALLED');
if (! empty ( $kn_version_warning )) {
	$kunena_app->enqueueMessage ( $kn_version_warning, 'notice' );
}
if (!$kn_version->checkVersion()) {
	$kunena_app->enqueueMessage ( sprintf ( JText::_('COM_KUNENA_ERROR_UPGRADE'), KUNENA_VERSION ), 'notice' );
	$kunena_app->enqueueMessage ( JText::_('COM_KUNENA_ERROR_UPGRADE_WARN') );
	$kunena_app->enqueueMessage ( sprintf ( JText::_('COM_KUNENA_ERROR_UPGRADE_AGAIN'), KUNENA_VERSION ) );
	$kunena_app->enqueueMessage ( JText::_('COM_KUNENA_ERROR_INCOMPLETE_SUPPORT') . ' <a href="http://www.kunena.com">www.kunena.com</a>' );
}

// Detect errors in CB integration
// TODO: do we need to enable this?
/*
if (is_object ( $kunenaProfile )) {
	$kunenaProfile->enqueueErrors ();
}
*/

html_Kunena::showFbFooter ();

function showAdministration($option) {
	$kunena_app = & JFactory::getApplication ();
	$kunena_db = &JFactory::getDBO ();

	$limit = $kunena_app->getUserStateFromRequest ( "global.list.limit", 'limit', $kunena_app->getCfg ( 'list_limit' ), 'int' );
	$limitstart = $kunena_app->getUserStateFromRequest ( "{$option}.limitstart", 'limitstart', 0, 'int' );
	$levellimit = $kunena_app->getUserStateFromRequest ( "{$option}.limit", 'levellimit', 10, 'int' );

	jimport ( 'joomla.version' );
	$jversion = new JVersion ();
	if ($jversion->RELEASE == 1.5) {
		// Joomla 1.5
		$kunena_db->setQuery ( "SELECT a.*, a.parent>0 AS category, u.name AS editor, g.name AS groupname, h.name AS admingroup
			FROM #__kunena_categories AS a
			LEFT JOIN #__users AS u ON u.id = a.checked_out
			LEFT JOIN #__core_acl_aro_groups AS g ON g.id = a.pub_access
			LEFT JOIN #__core_acl_aro_groups AS h ON h.id = a.admin_access
			ORDER BY a.ordering, a.name" );
	} else {
		// Joomla 1.6
		$kunena_db->setQuery ( "SELECT a.*, a.parent>0 AS category, u.name AS editor, g.title AS groupname, h.title AS admingroup
			FROM #__kunena_categories AS a
			LEFT JOIN #__users AS u ON u.id = a.checked_out
			LEFT JOIN #__usergroups AS g ON g.id = a.pub_access
			LEFT JOIN #__usergroups AS h ON h.id = a.admin_access
			ORDER BY a.ordering, a.name" );
	}
	$rows = $kunena_db->loadObjectList ('id');
	KunenaError::checkDatabaseError();

	// establish the hierarchy of the categories
	$children = array (0 => array());

	// first pass - collect children
	foreach ( $rows as $v ) {
		$list = array();
		$vv = $v;
		while ($vv->parent>0 && isset($rows[$vv->parent]) && !in_array($vv->parent, $list)) {
			$list[] = $vv->id;
			$vv = $rows[$vv->parent];
		}
		if ($vv->parent) {
			$v->parent = -1;
			$v->published = 0;
			$v->name = JText::_('COM_KUNENA_CATEGORY_ORPHAN').' : '.$v->name;
		}
		$children [$v->parent][] = $v;
		$v->location = count ( $children [$v->parent] )-1;
	}

	if (isset($children [-1])) {
		$children [0] = array_merge($children [-1], $children [0]);
		$kunena_app->enqueueMessage ( JText::_('COM_KUNENA_CATEGORY_ORPHAN_DESC'), 'notice' );
	}

	// second pass - get an indent list of the items
	$list = fbTreeRecurse ( 0, '', array (), $children, max ( 0, $levellimit - 1 ) );
	$total = count ( $list );
	if ($limitstart >= $total)
		$limitstart = 0;

	jimport ( 'joomla.html.pagination' );
	$pageNav = new JPagination ( $total, $limitstart, $limit );

	$levellist = JHTML::_ ( 'select.integerList', 1, 20, 1, 'levellimit', 'size="1" onchange="document.adminForm.submit();"', $levellimit );
	// slice out elements based on limits
	$list = array_slice ( $list, $pageNav->limitstart, $pageNav->limit );
	/**
	 *@end
	 */

	html_Kunena::showAdministration ( $list, $children, $pageNav, $option );
}

//---------------------------------------
//-E D I T   F O R U M-------------------
//---------------------------------------
function editForum($uid, $option) {
	$kunena_db = &JFactory::getDBO ();
	$kunena_acl = &JFactory::getACL ();
	$kunena_my = &JFactory::getUser ();
	$kunena_config = KunenaFactory::getConfig ();

	$row = new fbForum ( $kunena_db );
	// load the row from the db table
	if ($uid) $row->load ( $uid );
	$uid = $row->id;

	if ($uid) {
		$row->checkout ( $kunena_my->id );
	} else {
		// New category is by default child of the first section -- this will help new users to do it right
		$kunena_db->setQuery ( "SELECT a.id, a.name FROM #__kunena_categories AS a WHERE parent='0' AND id!='$row->id' ORDER BY ordering" );
		$sections = $kunena_db->loadObjectList ();
		KunenaError::checkDatabaseError();
		$row->parent = empty($sections) ? 0 : $sections[0]->id;
		$row->published = 0;
		$row->ordering = 9999;
		$row->pub_recurse = 1;
		$row->admin_recurse = 1;
		$row->pub_access = 0;
		$row->moderated = 1;
	}

	$catList = array();
	$catList[] = JHTML::_('select.option', 0, JText::_('COM_KUNENA_TOPLEVEL'));
	$categoryList = CKunenaTools::KSelectList('parent', $catList, 'class="inputbox"', true, 'parent', $row->parent);

	// make a standard yes/no list
	$yesno = array ();
	$yesno [] = JHTML::_ ( 'select.option', '0', JText::_('COM_KUNENA_ANN_NO') );
	$yesno [] = JHTML::_ ( 'select.option', '1', JText::_('COM_KUNENA_ANN_YES') );
	//Create all kinds of Lists
	$lists = array ();
	$accessLists = array ();
	//create custom group levels to include into the public group selectList
	$pub_groups = array ();
	$adm_groups = array ();
	$pub_groups [] = JHTML::_ ( 'select.option', 1, JText::_('COM_KUNENA_NOBODY') );
	$pub_groups [] = JHTML::_ ( 'select.option', 0, JText::_('COM_KUNENA_EVERYBODY') );
	$pub_groups [] = JHTML::_ ( 'select.option', - 1, JText::_('COM_KUNENA_ALLREGISTERED') );
	jimport ( 'joomla.version' );
	$jversion = new JVersion ();
	if ($jversion->RELEASE == 1.5) {
		// FIXME: not implemented in J1.6
		$pub_groups = array_merge ( $pub_groups, $kunena_acl->get_group_children_tree ( null, 'Registered', true ) );
		// create admin groups array for use in selectList:
		$adm_groups = array_merge ( $adm_groups, $kunena_acl->get_group_children_tree ( null, 'Public Backend', true ) );
	}
	// Anonymous posts default
	$post_anonymous = array ();
	$post_anonymous [] = JHTML::_ ( 'select.option', '0', JText::_('COM_KUNENA_CATEGORY_ANONYMOUS_X_REG') );
	$post_anonymous [] = JHTML::_ ( 'select.option', '1', JText::_('COM_KUNENA_CATEGORY_ANONYMOUS_X_ANO') );

	//create the access control list
	$accessLists ['pub_access'] = JHTML::_ ( 'select.genericlist', $pub_groups, 'pub_access', 'class="inputbox" size="4"', 'value', 'text', $row->pub_access );
	$accessLists ['admin_access'] = JHTML::_ ( 'select.genericlist', $adm_groups, 'admin_access', 'class="inputbox" size="4"', 'value', 'text', $row->admin_access );
	$lists ['pub_recurse'] = JHTML::_ ( 'select.genericlist', $yesno, 'pub_recurse', 'class="inputbox" size="1"', 'value', 'text', $row->pub_recurse );
	$lists ['admin_recurse'] = JHTML::_ ( 'select.genericlist', $yesno, 'admin_recurse', 'class="inputbox" size="1"', 'value', 'text', $row->admin_recurse );
	$lists ['forumLocked'] = JHTML::_ ( 'select.genericlist', $yesno, 'locked', 'class="inputbox" size="1"', 'value', 'text', $row->locked );
	$lists ['forumModerated'] = JHTML::_ ( 'select.genericlist', $yesno, 'moderated', 'class="inputbox" size="1"', 'value', 'text', $row->moderated );
	$lists ['forumReview'] = JHTML::_ ( 'select.genericlist', $yesno, 'review', 'class="inputbox" size="1"', 'value', 'text', $row->review );
	$lists ['allow_polls'] = JHTML::_ ( 'select.genericlist', $yesno, 'allow_polls', 'class="inputbox" size="1"', 'value', 'text', $row->allow_polls );
	$lists ['allow_anonymous'] = JHTML::_ ( 'select.genericlist', $yesno, 'allow_anonymous', 'class="inputbox" size="1"', 'value', 'text', $row->allow_anonymous );
	$lists ['post_anonymous'] = JHTML::_ ( 'select.genericlist', $post_anonymous, 'post_anonymous', 'class="inputbox" size="1"', 'value', 'text', $row->post_anonymous );
	//get a list of moderators, if forum/category is moderated
	$moderatorList = array ();

	if ($row->moderated == 1 && $uid) {
		$kunena_db->setQuery ( "SELECT * FROM #__kunena_moderation AS a INNER JOIN #__users as u ON a.userid=u.id where a.catid=$row->id" );
		$moderatorList = $kunena_db->loadObjectList ();
		KunenaError::checkDatabaseError();
	}

	html_Kunena::editForum ( $row, $categoryList, $moderatorList, $lists, $accessLists, $option, $kunena_config );
}

function saveForum($option) {
	$kunena_db = &JFactory::getDBO ();
	$kunena_app = & JFactory::getApplication ();

	$kunena_my = &JFactory::getUser ();
	$row = new fbForum ( $kunena_db );
	$id = JRequest::getInt ( 'id', 0, 'post' );
	if ($id) {
		$row->load ( $id );
	}
	if (! $row->save ( JRequest::get('post', JREQUEST_ALLOWRAW), 'parent' )) {
		$kunena_app->enqueueMessage ( $row->getError (), 'error' );
		$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=showAdministration" );
	}
	$row->reorder ();

	$kunena_db->setQuery ( "UPDATE #__kunena_sessions SET allowed='na'" );
	$kunena_db->query ();
	KunenaError::checkDatabaseError();

	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=showAdministration" );
}

function publishForum($cid = null, $publish = 1, $option) {
	$kunena_db = &JFactory::getDBO ();
	$kunena_app = & JFactory::getApplication ();
	$kunena_my = &JFactory::getUser ();
	if (! is_array ( $cid ) || count ( $cid ) < 1) {
		$action = $publish ? 'publish' : 'unpublish';
		echo "<script> alert('" . JText::_('COM_KUNENA_SELECTANITEMTO') . " $action'); window.history.go(-1);</script>\n";
		exit ();
	}

	$cids = implode ( ',', $cid );
	$kunena_db->setQuery ( "UPDATE #__kunena_categories SET published='$publish' WHERE id IN ($cids) AND (checked_out=0 OR (checked_out='$kunena_my->id'))" );
	$kunena_db->query ();
	if (KunenaError::checkDatabaseError()) return;

	if (count ( $cid ) == 1) {
		$row = new fbForum ( $kunena_db );
		$row->checkin ( $cid [0] );
	}

	// we must reset fbSession->allowed, when forum record was changed
	$kunena_db->setQuery ( "UPDATE #__kunena_sessions SET allowed='na'" );
	$kunena_db->query ();
	KunenaError::checkDatabaseError();

	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=showAdministration" );
}

function deleteForum($cid = null, $option) {
	$kunena_db = &JFactory::getDBO ();
	$kunena_app = & JFactory::getApplication ();
	$kunena_my = &JFactory::getUser ();
	if (! is_array ( $cid ) || count ( $cid ) < 1) {
		$action = 'delete';
		echo "<script> alert('" . JText::_('COM_KUNENA_SELECTANITEMTO') . " $action'); window.history.go(-1);</script>\n";
		exit ();
	}

	$cids = implode ( ',', $cid );
	$kunena_db->setQuery ( "DELETE FROM #__kunena_categories" . "\nWHERE id IN ($cids) AND (checked_out=0 OR (checked_out='$kunena_my->id'))" );
	$kunena_db->query ();
	if (KunenaError::checkDatabaseError()) return;

	$kunena_db->setQuery ( "SELECT id, parent FROM #__kunena_messages where catid in ($cids)" );
	$mesList = $kunena_db->loadObjectList ();
	if (KunenaError::checkDatabaseError()) return;

	if (count ( $mesList ) > 0) {
		foreach ( $mesList as $ml ) {
			$kunena_db->setQuery ( "DELETE FROM #__kunena_messages WHERE id = $ml->id" );
			$kunena_db->query ();
			if (KunenaError::checkDatabaseError()) return;

			$kunena_db->setQuery ( "DELETE FROM #__kunena_messages_text WHERE mesid=$ml->id" );
			$kunena_db->query ();
			if (KunenaError::checkDatabaseError()) return;

			//and clear up all subscriptions as well
			if ($ml->parent == 0) { //this was a topic message to which could have been subscribed
				$kunena_db->setQuery ( "DELETE FROM #__kunena_subscriptions WHERE thread=$ml->id" );
				$kunena_db->query ();
				if (KunenaError::checkDatabaseError()) return;
			}
		}
	}

	$kunena_db->setQuery ( "UPDATE #__kunena_sessions SET allowed='na'" );
	$kunena_db->query ();
	KunenaError::checkDatabaseError();

	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=showAdministration" );
}

function cancelForum($option) {
	$kunena_app = & JFactory::getApplication ();

	$kunena_db = &JFactory::getDBO ();
	$row = new fbForum ( $kunena_db );
	$row->bind ( JRequest::get('post', JREQUEST_ALLOWRAW) );
	$row->checkin ();
	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=showAdministration" );
}

function orderForum($uid, $inc, $option) {
	$kunena_app = & JFactory::getApplication ();
	$kunena_db = &JFactory::getDBO ();
	$row = new fbForum ( $kunena_db );
	$row->load ( $uid );

	// Ensure that we have the right ordering
	$where = $row->_db->nameQuote ( 'parent' ) . '=' . $row->_db->quote ( $row->parent );
	$row->reorder ( $where );
	$row->load ( $uid );

	$row->move ( $inc, $where );
	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=showAdministration" );
}

function pollpublish ( $option, $cid = null, $publish = 1 ) {
	$kunena_db = &JFactory::getDBO ();
	$kunena_app = & JFactory::getApplication ();
	$kunena_my = &JFactory::getUser ();
	if (! is_array ( $cid ) || count ( $cid ) < 1) {
		$action = $publish ? 'publish' : 'unpublish';
		echo "<script> alert('" . JText::_('COM_KUNENA_SELECTANITEMTO') . " $action'); window.history.go(-1);</script>\n";
		exit ();
	}

	$cids = implode ( ',', $cid );
	$kunena_db->setQuery ( "UPDATE #__kunena_categories SET allow_polls='$publish' WHERE id IN ($cids) AND (checked_out=0 OR (checked_out='$kunena_my->id'))" );
	$kunena_db->query ();
	if (KunenaError::checkDatabaseError()) return;

	if (count ( $cid ) == 1) {
		$row = new fbForum ( $kunena_db );
		$row->checkin ( $cid [0] );
	}

	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=showAdministration" );
}

function pollunpublish ( $option, $cid = null, $unpublish = 0 ) {
	$kunena_db = &JFactory::getDBO ();
	$kunena_app = & JFactory::getApplication ();
	$kunena_my = &JFactory::getUser ();
	if (! is_array ( $cid ) || count ( $cid ) < 1) {
		$action = $unpublish ? 'unpublish' : 'publish';
		echo "<script> alert('" . JText::_('COM_KUNENA_SELECTANITEMTO') . " $action'); window.history.go(-1);</script>\n";
		exit ();
	}

	$cids = implode ( ',', $cid );
	$kunena_db->setQuery ( "UPDATE #__kunena_categories SET allow_polls='$unpublish' WHERE id IN ($cids) AND (checked_out=0 OR (checked_out='$kunena_my->id'))" );
	$kunena_db->query ();
	if (KunenaError::checkDatabaseError()) return;

	if (count ( $cid ) == 1) {
		$row = new fbForum ( $kunena_db );
		$row->checkin ( $cid [0] );
	}

	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=showAdministration" );
}

//===============================
// Config Functions
//===============================
function showConfig($option) {
	require_once (KUNENA_PATH_LIB.'/kunena.timeformat.class.php');
	$kunena_db = &JFactory::getDBO ();
	$kunena_config = KunenaFactory::getConfig ();

	$lists = array ();

	// the default page when entering Kunena
	$defpagelist = array ();

	$defpagelist [] = JHTML::_ ( 'select.option', 'recent', JText::_('COM_KUNENA_A_FBDEFAULT_PAGE_RECENT') );
	$defpagelist [] = JHTML::_ ( 'select.option', 'my', JText::_('COM_KUNENA_A_FBDEFAULT_PAGE_MY') );
	$defpagelist [] = JHTML::_ ( 'select.option', 'categories', JText::_('COM_KUNENA_A_FBDEFAULT_PAGE_CATEGORIES') );

	// build the html select list
	$lists ['fbdefaultpage'] = JHTML::_ ( 'select.genericlist', $defpagelist, 'cfg_fbdefaultpage', 'class="inputbox" size="1" ', 'value', 'text', $kunena_config->fbdefaultpage );

	// build the html select list

	// RSS
	{
		// options to be used later
		$rss_yesno = array ();
		$rss_yesno [] = JHTML::_ ( 'select.option', '0', JText::_('COM_KUNENA_A_NO') );
		$rss_yesno [] = JHTML::_ ( 'select.option', '1', JText::_('COM_KUNENA_A_YES') );

		// ------

		$rss_type = array ();
		$rss_type [] = JHTML::_ ( 'select.option', 'thread', JText::_('COM_KUNENA_A_RSS_TYPE_THREAD') );
		$rss_type [] = JHTML::_ ( 'select.option', 'post', JText::_('COM_KUNENA_A_RSS_TYPE_POST') );
		$rss_type [] = JHTML::_ ( 'select.option', 'recent', JText::_('COM_KUNENA_A_RSS_TYPE_RECENT') );

		// build the html select list
		$lists ['rss_type'] = JHTML::_ ( 'select.genericlist', $rss_type, 'cfg_rss_type', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->rss_type );

		// ------

		$rss_timelimit = array ();
		$rss_timelimit [] = JHTML::_ ( 'select.option', 'week', JText::_('COM_KUNENA_A_RSS_TIMELIMIT_WEEK') );
		$rss_timelimit [] = JHTML::_ ( 'select.option', 'month', JText::_('COM_KUNENA_A_RSS_TIMELIMIT_MONTH') );
		$rss_timelimit [] = JHTML::_ ( 'select.option', 'year', JText::_('COM_KUNENA_A_RSS_TIMELIMIT_YEAR') );

		// build the html select list
		$lists ['rss_timelimit'] = JHTML::_ ( 'select.genericlist', $rss_timelimit, 'cfg_rss_timelimit', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->rss_timelimit );

		// ------

		$rss_specification = array ();

		$rss_specification [] = JHTML::_ ( 'select.option', 'rss0.91', 'RSS 0.91');
		$rss_specification [] = JHTML::_ ( 'select.option', 'rss1.0', 'RSS 1.0' );
		$rss_specification [] = JHTML::_ ( 'select.option', 'rss2.0', 'RSS 2.0' );
		$rss_specification [] = JHTML::_ ( 'select.option', 'atom1.0', 'Atom 1.0' );

		// build the html select list
		$lists ['rss_specification'] = JHTML::_ ( 'select.genericlist', $rss_specification, 'cfg_rss_specification', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->rss_specification );

		// ------

		$rss_author_format = array ();
		$rss_author_format [] = JHTML::_ ( 'select.option', 'name', JText::_('COM_KUNENA_A_RSS_AUTHOR_FORMAT_NAME') );
		$rss_author_format [] = JHTML::_ ( 'select.option', 'email', JText::_('COM_KUNENA_A_RSS_AUTHOR_FORMAT_EMAIL') );

		// build the html select list
		$lists ['rss_author_format'] = JHTML::_ ( 'select.genericlist', $rss_author_format, 'cfg_rss_author_format', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->rss_author_format );

		// ------

		$rss_word_count = array ();
		$rss_word_count [] = JHTML::_ ( 'select.option', '0', JText::_('COM_KUNENA_A_RSS_WORD_COUNT_ALL') );
		$rss_word_count [] = JHTML::_ ( 'select.option', '50', '50' );
		$rss_word_count [] = JHTML::_ ( 'select.option', '100', '100' );
		$rss_word_count [] = JHTML::_ ( 'select.option', '250', '250' );
		$rss_word_count [] = JHTML::_ ( 'select.option', '500', '500' );
		$rss_word_count [] = JHTML::_ ( 'select.option', '750', '750' );
		$rss_word_count [] = JHTML::_ ( 'select.option', '1000', '1000' );

		// build the html select list
		$lists ['rss_word_count'] = JHTML::_ ( 'select.genericlist', $rss_word_count, 'cfg_rss_word_count', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->rss_word_count );

		// ------

		// build the html select list
		$lists ['rss_allow_html'] = JHTML::_ ( 'select.genericlist', $rss_yesno, 'cfg_rss_allow_html', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->rss_allow_html );

		// ------

		// build the html select list
		$lists ['rss_old_titles'] = JHTML::_ ( 'select.genericlist', $rss_yesno, 'cfg_rss_old_titles', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->rss_old_titles );

		// ------

		// build the html select list - (moved enablerss here, to keep all rss-related features together)
		$lists ['enablerss'] = JHTML::_ ( 'select.genericlist', $rss_yesno, 'cfg_enablerss', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->enablerss );
	}

	// source of avatar picture
	$avlist = array ();
	$avlist [] = JHTML::_ ( 'select.option', 'fb', JText::_('COM_KUNENA_KUNENA') );
	$avlist [] = JHTML::_ ( 'select.option', 'cb', JText::_('COM_KUNENA_CB') );
	$avlist [] = JHTML::_ ( 'select.option', 'jomsocial', JText::_('COM_KUNENA_JOMSOCIAL') );
	$avlist [] = JHTML::_ ( 'select.option', 'aup', JText::_('COM_KUNENA_AUP_ALPHAUSERPOINTS') ); // INTEGRATION ALPHAUSERPOINTS
	// build the html select list
	$lists ['avatar_src'] = JHTML::_ ( 'select.genericlist', $avlist, 'cfg_avatar_src', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->avatar_src );

	// private messaging system to use
	$pmlist = array ();
	$pmlist [] = JHTML::_ ( 'select.option', 'no', JText::_('COM_KUNENA_A_NO') );
	$pmlist [] = JHTML::_ ( 'select.option', 'cb', JText::_('COM_KUNENA_CB') );
	$pmlist [] = JHTML::_ ( 'select.option', 'jomsocial', JText::_('COM_KUNENA_JOMSOCIAL') );
	$pmlist [] = JHTML::_ ( 'select.option', 'uddeim', JText::_('COM_KUNENA_UDDEIM') );

	$lists ['pm_component'] = JHTML::_ ( 'select.genericlist', $pmlist, 'cfg_pm_component', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->pm_component );

	//redundant    $lists['pm_component'] = JHTML::_('select.genericlist',$pmlist, 'cfg_pm_component', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->pm_component);
	// Profile select
	$prflist = array ();
	$prflist [] = JHTML::_ ( 'select.option', 'fb', JText::_('COM_KUNENA_KUNENA') );
	$prflist [] = JHTML::_ ( 'select.option', 'cb', JText::_('COM_KUNENA_CB') );
	$prflist [] = JHTML::_ ( 'select.option', 'jomsocial', JText::_('COM_KUNENA_JOMSOCIAL') );
	$prflist [] = JHTML::_ ( 'select.option', 'aup', JText::_('COM_KUNENA_AUP_ALPHAUSERPOINTS') ); // INTEGRATION ALPHAUSERPOINTS


	$lists ['fb_profile'] = JHTML::_ ( 'select.genericlist', $prflist, 'cfg_fb_profile', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->fb_profile );

	// build the html select list
	// make a standard yes/no list
	$yesno = array ();
	$yesno [] = JHTML::_ ( 'select.option', '0', JText::_('COM_KUNENA_A_NO') );
	$yesno [] = JHTML::_ ( 'select.option', '1', JText::_('COM_KUNENA_A_YES') );
	/* Build the templates list*/
	// This function was modified from the one posted to PHP.net by rockinmusicgv
	// It is available under the readdir() entry in the PHP online manual
	//function get_dirs($directory, $select_name, $selected = "") {
	$listitems [] = JHTML::_ ( 'select.option', '1', JText::_('COM_KUNENA_SELECTTEMPLATE') );

	$templatelist = array();
	$imagesetlist = array();
	$dir = @opendir ( KUNENA_PATH_TEMPLATE );
	if ($dir) {
		while ( ($file = readdir ( $dir )) !== false ) {
			if ($file != ".." && $file != ".") {
				if (is_dir ( KUNENA_PATH_TEMPLATE . DS . $file )) {
					if (! ($file [0] == '.') && is_file ( KUNENA_PATH_TEMPLATE . DS. $file . DS . 'css' . DS . 'kunena.forum.css' )) {
						$templatelist [] = $file;
					}
					if (! ($file [0] == '.') && is_dir ( KUNENA_PATH_TEMPLATE . DS . $file . DS . 'images' )) {
						$imagesetlist [] = $file;
					}
				}
			}
		}

		closedir ( $dir );
	}

	asort ( $templatelist );

	foreach ( $templatelist as $key => $val ) {
		$templatelistitems [] = JHTML::_ ( 'select.option', $val, $val );
	}

	asort ( $imagesetlist );

	foreach ( $imagesetlist as $key => $val ) {
		$imagesetlistitems [] = JHTML::_ ( 'select.option', $val, $val );
	}

	$lists ['jmambot'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_jmambot', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->jmambot );
	$lists ['disemoticons'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_disemoticons', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->disemoticons );
	$lists ['template'] = JHTML::_ ( 'select.genericlist', $templatelistitems, 'cfg_template', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->template );
	$lists ['templateimagepath'] = JHTML::_ ( 'select.genericlist', $imagesetlistitems, 'cfg_templateimagepath', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->templateimagepath );
	$lists ['regonly'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_regonly', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->regonly );
	$lists ['board_offline'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_board_offline', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->board_offline );
	$lists ['pubwrite'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_pubwrite', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->pubwrite );
	$lists ['useredit'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_useredit', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->useredit );
	$lists ['showhistory'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_showhistory', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showhistory );
	$lists ['showannouncement'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_showannouncement', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showannouncement );
	$lists ['avataroncat'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_avataroncat', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->avataroncat );
	$lists ['showchildcaticon'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_showchildcaticon', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showchildcaticon );
	$lists ['showuserstats'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_showuserstats', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showuserstats );
	$lists ['showwhoisonline'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_showwhoisonline', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showwhoisonline );
	$lists ['showpopsubjectstats'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_showpopsubjectstats', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showpopsubjectstats );
	$lists ['showgenstats'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_showgenstats', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showgenstats );
	$lists ['showpopuserstats'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_showpopuserstats', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showpopuserstats );
	$lists ['allowsubscriptions'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_allowsubscriptions', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->allowsubscriptions );
	$lists ['subscriptionschecked'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_subscriptionschecked', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->subscriptionschecked );
	$lists ['allowfavorites'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_allowfavorites', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->allowfavorites );
	$lists ['mailmod'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_mailmod', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->mailmod );
	$lists ['mailadmin'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_mailadmin', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->mailadmin );
	$lists ['showemail'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_showemail', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showemail );
	$lists ['askemail'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_askemail', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->askemail );
	$lists ['changename'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_changename', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->changename );
	$lists ['allowavatarupload'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_allowavatarupload', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->allowavatarupload );
	$lists ['allowavatargallery'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_allowavatargallery', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->allowavatargallery );
	$lists ['avatar_src'] = JHTML::_ ( 'select.genericlist', $avlist, 'cfg_avatar_src', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->avatar_src );

	$ip_opt [] = JHTML::_ ( 'select.option', 'gd2', 'GD2' );
	$ip_opt [] = JHTML::_ ( 'select.option', 'gd1', 'GD1' );
	$ip_opt [] = JHTML::_ ( 'select.option', 'none', JText::_('COM_KUNENA_IMAGE_PROCESSOR_NONE') );

	$lists ['imageprocessor'] = JHTML::_ ( 'select.genericlist', $ip_opt, 'cfg_imageprocessor', 'class="inputbox"', 'value', 'text', $kunena_config->imageprocessor );
	$lists ['showstats'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_showstats', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showstats );
	$lists ['showranking'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_showranking', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showranking );
	$lists ['rankimages'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_rankimages', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->rankimages );
	$lists ['username'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_username', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->username );
	$lists ['shownew'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_shownew', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->shownew );
	$lists ['allowimageupload'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_allowimageupload', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->allowimageupload );
	$lists ['allowimageregupload'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_allowimageregupload', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->allowimageregupload );
	$lists ['allowfileupload'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_allowfileupload', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->allowfileupload );
	$lists ['allowfileregupload'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_allowfileregupload', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->allowfileregupload );
	$lists ['editmarkup'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_editmarkup', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->editmarkup );
	$lists ['showkarma'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_showkarma', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showkarma );
	$lists ['enablepdf'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_enablepdf', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->enablepdf );
	$lists ['enablerulespage'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_enablerulespage', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->enablerulespage );
	$lists ['rules_infb'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_rules_infb', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->rules_infb );
	$lists ['enablehelppage'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_enablehelppage', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->enablehelppage );
	$lists ['help_infb'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_help_infb', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->help_infb );
	$lists ['enableforumjump'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_enableforumjump', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->enableforumjump );
	$lists ['userlist_online'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_userlist_online', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->userlist_online );
	$lists ['userlist_avatar'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_userlist_avatar', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->userlist_avatar );
	$lists ['userlist_name'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_userlist_name', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->userlist_name );
	$lists ['userlist_username'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_userlist_username', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->userlist_username );
	$lists ['userlist_posts'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_userlist_posts', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->userlist_posts );
	$lists ['userlist_karma'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_userlist_karma', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->userlist_karma );
	$lists ['userlist_email'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_userlist_email', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->userlist_email );
	$lists ['userlist_usertype'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_userlist_usertype', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->userlist_usertype );
	$lists ['userlist_joindate'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_userlist_joindate', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->userlist_joindate );
	$lists ['userlist_lastvisitdate'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_userlist_lastvisitdate', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->userlist_lastvisitdate );
	$lists ['userlist_userhits'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_userlist_userhits', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->userlist_userhits );
	$lists ['usernamechange'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_usernamechange', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->usernamechange );
	$lists ['reportmsg'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_reportmsg', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->reportmsg );
	$lists ['captcha'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_captcha', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->captcha );
	$lists ['mailfull'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_mailfull', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->mailfull );
	// New for 1.0.5
	$lists ['showspoilertag'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_showspoilertag', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showspoilertag );
	$lists ['showvideotag'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_showvideotag', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showvideotag );
	$lists ['showebaytag'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_showebaytag', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showebaytag );
	$lists ['trimlongurls'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_trimlongurls', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->trimlongurls );
	$lists ['autoembedyoutube'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_autoembedyoutube', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->autoembedyoutube );
	$lists ['autoembedebay'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_autoembedebay', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->autoembedebay );
	$lists ['highlightcode'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_highlightcode', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->highlightcode );
	// New for 1.5.7 -> integration AlphaUserPoints
	$lists ['alphauserpoints'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_alphauserpoints', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->alphauserpoints );
	$lists ['alphauserpointsrules'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_alphauserpointsrules', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->alphauserpointsrules );
	// New for 1.5.8 -> SEF
	$lists ['sef'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_sef', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->sef );
	$lists ['sefcats'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_sefcats', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->sefcats );
	$lists ['sefutf8'] = JHTML::_ ( 'select.genericlist', $yesno, 'cfg_sefutf8', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->sefutf8 );
	// New for 1.6 -> Hide images and files for guests
	$lists['showimgforguest'] = JHTML::_('select.genericlist', $yesno, 'cfg_showimgforguest', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showimgforguest);
	$lists['showfileforguest'] = JHTML::_('select.genericlist', $yesno, 'cfg_showfileforguest', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showfileforguest);
	// New for 1.6 -> Check Image MIME types
	$lists['checkmimetypes'] = JHTML::_('select.genericlist', $yesno, 'cfg_checkmimetypes', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->checkmimetypes);
    // New for 1.6 -> Avatar Position
    $avpos = array ();
	$avpos[] = JHTML::_('select.option', 'top',JText::_('COM_KUNENA_AV_TOP'));
	$avpos[] = JHTML::_('select.option', 'left',JText::_('COM_KUNENA_AV_LEFT'));
	$avpos[] = JHTML::_('select.option', 'right',JText::_('COM_KUNENA_AV_RIGHT'));
	$avpos[] = JHTML::_('select.option', 'bottom',JText::_('COM_KUNENA_AV_BOTTOM'));
    $lists['avposition'] = JHTML::_('select.genericlist', $avpos, 'cfg_avposition', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->avposition);
	//New for 1.6 -> Poll
	$lists['pollallowvoteone'] = JHTML::_('select.genericlist', $yesno, 'cfg_pollallowvoteone', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->pollallowvoteone);
  	$lists['pollenabled'] = JHTML::_('select.genericlist', $yesno, 'cfg_pollenabled', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->pollenabled);
  	$lists['showpoppollstats'] = JHTML::_('select.genericlist', $yesno, 'cfg_showpoppollstats', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->showpoppollstats);
  	$lists['pollresultsuserslist'] = JHTML::_('select.genericlist', $yesno, 'cfg_pollresultsuserslist', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->pollresultsuserslist);
  	//New for 1.6 -> Choose ordering system
  	$ordering_system_list = array ();
  	$ordering_system_list[] = JHTML::_('select.option', 'new_ord', JText::_('COM_KUNENA_COM_A_ORDERING_SYSTEM_NEW'));
  	$ordering_system_list[] = JHTML::_('select.option', 'old_ord',JText::_('COM_KUNENA_COM_A_ORDERING_SYSTEM_OLD'));
  	$lists['ordering_system'] = JHTML::_('select.genericlist', $ordering_system_list, 'cfg_ordering_system', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->ordering_system);
	// New for 1.6: datetime
	require_once(KUNENA_PATH_LIB .DS. 'kunena.timeformat.class.php');
	$dateformatlist = array ();
	$time = CKunenaTimeformat::internalTime() - 80000;
	$dateformatlist[] = JHTML::_('select.option', 'none', JText::_('COM_KUNENA_OPTION_DATEFORMAT_NONE'));
	$dateformatlist[] = JHTML::_('select.option', 'ago', CKunenaTimeformat::showDate($time, 'ago'));
	$dateformatlist[] = JHTML::_('select.option', 'datetime_today', CKunenaTimeformat::showDate($time, 'datetime_today'));
	$dateformatlist[] = JHTML::_('select.option', 'datetime', CKunenaTimeformat::showDate($time, 'datetime'));
	$lists['post_dateformat'] = JHTML::_('select.genericlist', $dateformatlist, 'cfg_post_dateformat', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->post_dateformat);
	$lists['post_dateformat_hover'] = JHTML::_('select.genericlist', $dateformatlist, 'cfg_post_dateformat_hover', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->post_dateformat_hover);
	// New for 1.6: hide ip
	$lists['hide_ip'] = JHTML::_('select.genericlist', $yesno, 'cfg_hide_ip', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->hide_ip);
	//New for 1.6: Joomsocial Activity Stream Integration disable/enable
	$lists['js_actstr_integration'] = JHTML::_('select.genericlist', $yesno, 'cfg_js_actstr_integration', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->js_actstr_integration);
	//New for 1.6: choose if you want that ghost message box checked by default
	$lists['boxghostmessage'] = JHTML::_('select.genericlist', $yesno, 'cfg_boxghostmessage', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->boxghostmessage);

	kimport('integration.integration');
	$lists['integration_access'] = KunenaIntegration::getConfigOptions('access');
	$lists['integration_activity'] = KunenaIntegration::getConfigOptions('activity');
	$lists['integration_avatar'] = KunenaIntegration::getConfigOptions('avatar');
	$lists['integration_login'] = KunenaIntegration::getConfigOptions('login');
	$lists['integration_profile'] = KunenaIntegration::getConfigOptions('profile');
	$lists['integration_private'] = KunenaIntegration::getConfigOptions('private');

	$listUserDeleteMessage = array();
	$listUserDeleteMessage[] = JHTML::_('select.option', '0', JText::_('COM_KUNENA_A_DELETEMESSAGE_NOT_ALLOWED'));
	$listUserDeleteMessage[] = JHTML::_('select.option', '1', JText::_('COM_KUNENA_A_DELETEMESSAGE_ALLOWED_IF_REPLIES'));
	$listUserDeleteMessage[] = JHTML::_('select.option', '2', JText::_('COM_KUNENA_A_DELETEMESSAGE_ALWAYS_ALLOWED'));
	$lists['userdeletetmessage'] = JHTML::_('select.genericlist', $listUserDeleteMessage, 'cfg_userdeletetmessage', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->userdeletetmessage);

	$latestCategoryIn = array();
	$latestCategoryIn[] = JHTML::_('select.option', '0', JText::_('COM_KUNENA_COM_A_LATESTCATEGORY_IN_HIDE'));
	$latestCategoryIn[] = JHTML::_('select.option', '1', JText::_('COM_KUNENA_COM_A_LATESTCATEGORY_IN_SHOW'));
	$lists['latestcategory_in'] = JHTML::_('select.genericlist', $latestCategoryIn, 'cfg_latestcategory_in', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->latestcategory_in);

	$optionsShowHide = array();
	$optionsShowHide[] = JHTML::_('select.option', 0, JText::_('COM_KUNENA_COM_A_LATESTCATEGORY_SHOWALL'));
	$lists['latestcategory'] = CKunenaTools::KSelectList('cfg_latestcategory[]', $optionsShowHide, 'class="inputbox" multiple="multiple"', false, 'latestcategory', explode(',',$kunena_config->latestcategory));

	$lists['topicicons'] = JHTML::_('select.genericlist', $yesno, 'cfg_topicicons', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->topicicons);

	$lists['onlineusers'] = JHTML::_('select.genericlist', $yesno, 'cfg_onlineusers', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->onlineusers);

	$lists['debug'] = JHTML::_('select.genericlist', $yesno, 'cfg_debug', 'class="inputbox" size="1"', 'value', 'text', $kunena_config->debug);

	html_Kunena::showConfig($kunena_config, $lists, $option);
}

function defaultConfig($option) {
	$kunena_app = JFactory::getApplication ();
	$kunena_config = KunenaFactory::getConfig ();
	$kunena_config->backup ();
	$kunena_config->remove ();

	$kunena_db = &JFactory::getDBO ();
	$kunena_db->setQuery ( "UPDATE #__kunena_sessions SET allowed='na'" );
	$kunena_db->query ();
	KunenaError::checkDatabaseError();

	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=showconfig", JText::_('COM_KUNENA_CONFIG_DEFAULT') );
}

function saveConfig($option) {
	$kunena_app = & JFactory::getApplication ();
	$kunena_config = KunenaFactory::getConfig ();
	$kunena_db = &JFactory::getDBO ();

	foreach ( JRequest::get('post', JREQUEST_ALLOWHTML) as $postsetting => $postvalue ) {
		if (JString::strpos ( $postsetting, 'cfg_' ) === 0) {
			//remove cfg_ and force lower case
			if ( is_array($postvalue) ) {
				$postvalue = implode(',',$postvalue);
			}
			$postname = JString::strtolower ( JString::substr ( $postsetting, 4 ) );

			if ($postname == 'enablerulespage') {
				$postvalue = intval ( $postvalue );
				$kunena_db->setQuery ( "UPDATE #__menu SET published={$postvalue} WHERE menutype='kunenamenu' AND link='index.php?option=com_kunena&func=rules'" );
				$kunena_db->query ();
				KunenaError::checkDatabaseError();
			}
			if ($postname == 'enablehelppage') {
				$postvalue = intval ( $postvalue );
				$kunena_db->setQuery ( "UPDATE #__menu SET published={$postvalue} WHERE menutype='kunenamenu' AND link='index.php?option=com_kunena&func=help'" );
				$kunena_db->query ();
				KunenaError::checkDatabaseError();
			}

			// No matter what got posted, we only store config parameters defined
			// in the config class. Anything else posted gets ignored.
			if (array_key_exists ( $postname, $kunena_config->GetClassVars () )) {
				if (is_numeric ( $postvalue )) {
					eval ( "\$kunena_config->" . $postname . " = " . $postvalue . ";" );
				} else {
					// Rest is treaded as strings
					eval ( "\$kunena_config->" . $postname . " = '" . $postvalue . "';" );
				}
			} else {
				// This really should not happen if assertions are enable
				// fail it and display the current scope of variables for debugging.
				trigger_error ( 'Unknown configuration variable posted.' );
				assert ( 0 );
			}
		}
	}

	$kunena_config->backup ();
	$kunena_config->remove ();
	$kunena_config->create ();

	$kunena_db->setQuery ( "UPDATE #__kunena_sessions SET allowed='na'" );
	$kunena_db->query ();
	KunenaError::checkDatabaseError();

	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=showconfig", JText::_('COM_KUNENA_CONFIGSAVED') );
}

function showInstructions($kunena_db, $option) {
	$kunena_db = &JFactory::getDBO ();
	html_Kunena::showInstructions ( $kunena_db, $option );
}

//===============================
// CSS functions
//===============================
function showCss($option) {
	require_once (KUNENA_PATH_LIB . DS . 'kunena.file.class.php');

	$kunena_config = KunenaFactory::getConfig ();
	$file = KUNENA_PATH_TEMPLATE . DS . $kunena_config->template . DS .'css'. DS . "kunena.forum.css";
	$permission = CKunenaPath::isWritable ( $file );

	if (! $permission) {
		echo "<center><h1><font color=red>" . JText::_('COM_KUNENA_WARNING') . "</font></h1><br />";
		echo "<b>" . JText::_('COM_KUNENA_CFC_FILENAME') . ": " . $file . "</b><br />";
		echo "<b>" . JText::_('COM_KUNENA_CHMOD1') . "</b></center><br /><br />";
	}

	html_Kunena::showCss ( $file, $option );
}

function saveCss($file, $csscontent, $option) {
	require_once (KUNENA_PATH_LIB . DS . 'kunena.file.class.php');

	$kunena_app = & JFactory::getApplication ();
	$tmpstr = JText::_('COM_KUNENA_CSS_SAVE');
	$tmpstr = str_replace ( "%file%", $file, $tmpstr );
	echo $tmpstr;

	if (CKunenaFile::write ( $file, $csscontent )) {
		$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=showCss", JText::_('COM_KUNENA_CFC_SAVED') );
	} else {
		$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=showCss", JText::_('COM_KUNENA_CFC_NOTSAVED') );
	}
}

//===============================
// Moderator Functions
//===============================
function newModerator($option, $id = null) {
	$kunena_app = & JFactory::getApplication ();
	$kunena_db = &JFactory::getDBO ();
	//die ("New Moderator");
	//$limit = intval(JRequest::getVar( 'limit', 10));
	//$limitstart = intval(JRequest::getVar( 'limitstart', 0));
	$limit = $kunena_app->getUserStateFromRequest ( "global.list.limit", 'limit', $kunena_app->getCfg ( 'list_limit' ), 'int' );
	$limitstart = $kunena_app->getUserStateFromRequest ( "{$option}.limitstart", 'limitstart', 0, 'int' );
	$kunena_db->setQuery ( "SELECT COUNT(*) FROM #__users AS a LEFT JOIN #__kunena_users AS b ON a.id=b.userid WHERE b.moderator=1" );
	$total = $kunena_db->loadResult ();
	if (KunenaError::checkDatabaseError()) return;

	if ($limitstart >= $total)
		$limitstart = 0;
	if ($limit == 0 || $limit > 100)
		$limit = 100;

	$kunena_db->setQuery ( "SELECT * FROM #__users AS a LEFT JOIN #__kunena_users AS b ON a.id=b.userid WHERE b.moderator=1", $limitstart, $limit );
	$userList = $kunena_db->loadObjectList ();
	if (KunenaError::checkDatabaseError()) return;
	$countUL = count ( $userList );

	jimport ( 'joomla.html.pagination' );
	$pageNav = new JPagination ( $total, $limitstart, $limit );
	//$id = intval( JRequest::getVar('id') );
	//get forum name
	$forumName = '';
	$kunena_db->setQuery ( "SELECT name FROM #__kunena_categories WHERE id=$id" );
	$forumName = $kunena_db->loadResult ();
	if (KunenaError::checkDatabaseError()) return;

	//get forum moderators
	$kunena_db->setQuery ( "SELECT userid FROM #__kunena_moderation WHERE catid=$id" );
	$moderatorList = $kunena_db->loadObjectList ();
	if (KunenaError::checkDatabaseError()) return;
	$moderators = 0;
	$modIDs [] = array ();

	if (count ( $moderatorList ) > 0) {
		foreach ( $moderatorList as $ml ) {
			$modIDs [] = $ml->userid;
		}

		$moderators = 1;
	} else {
		$moderators = 0;
	}

	html_Kunena::newModerator ( $option, $id, $moderators, $modIDs, $forumName, $userList, $countUL, $pageNav );
}

function addModerator($option, $id, $cid = null, $publish = 1) {
	$kunena_app = & JFactory::getApplication ();
	$kunena_db = &JFactory::getDBO ();
	$kunena_my = &JFactory::getUser ();

	$numcid = count ( $cid );
	$action = "";

	if ($publish == 1) {
		$action = 'add';
	} else {
		$action = 'remove';
	}

	if (! is_array ( $cid ) || count ( $cid ) < 1) {
		echo "<script> alert('" . JText::_('COM_KUNENA_SELECTMODTO') . " $action'); window.history.go(-1);</script>\n";
		exit ();
	}

	if ($action == 'add') {
		for($i = 0, $n = count ( $cid ); $i < $n; $i ++) {
			$kunena_db->setQuery ( "INSERT INTO #__kunena_moderation SET catid='$id', userid='$cid[$i]'" );
			$kunena_db->query ();
			if (KunenaError::checkDatabaseError()) return;
		}
	} else {
		for($i = 0, $n = count ( $cid ); $i < $n; $i ++) {
			$kunena_db->setQuery ( "DELETE FROM #__kunena_moderation WHERE catid='$id' AND userid='$cid[$i]'" );
			$kunena_db->query ();
			if (KunenaError::checkDatabaseError()) return;
		}
	}

	$row = new fbForum ( $kunena_db );
	$row->checkin ( $id );

	$kunena_db->setQuery ( "UPDATE #__kunena_sessions SET allowed='na'" );
	$kunena_db->query ();
	KunenaError::checkDatabaseError();

	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=edit2&uid=" . $id );
}

//===============================
//   User Profile functions
//===============================
function showProfiles($kunena_db, $option, $order) {
	$kunena_app = & JFactory::getApplication ();
	$kunena_db = &JFactory::getDBO ();
	//$limit = intval(JRequest::getVar( 'limit', 10));
	//$limitstart = intval(JRequest::getVar( 'limitstart', 0));
	$limit = $kunena_app->getUserStateFromRequest ( "global.list.limit", 'limit', $kunena_app->getCfg ( 'list_limit' ), 'int' );
	$limitstart = $kunena_app->getUserStateFromRequest ( "{$option}.limitstart", 'limitstart', 0, 'int' );

	$search = $kunena_app->getUserStateFromRequest ( "{$option}.search", 'search', '', 'string' );
	$search = $kunena_db->getEscaped ( JString::trim ( JString::strtolower ( $search ) ) );
	$where = array ();

	if (isset ( $search ) && $search != "") {
		$where [] = "(u.username LIKE '%$search%' OR u.email LIKE '%$search%' OR u.name LIKE '%$search%')";
	}

	$kunena_db->setQuery ( "SELECT COUNT(*) FROM #__kunena_users AS sbu INNER JOIN #__users AS u ON sbu.userid=u.id" . (count ( $where ) ? " WHERE " . implode ( ' AND ', $where ) : "") );
	$total = $kunena_db->loadResult ();
	if (KunenaError::checkDatabaseError()) return;

	if ($limitstart >= $total)
		$limitstart = 0;
	if ($limit == 0 || $limit > 100)
		$limit = 100;

	if ($order == 1) {
		$kunena_db->setQuery ( "SELECT sbu.*,u.*,sess.session_id, banusers.enabled AS usersbanenabled, banusers.bantype, banusers.userid AS banuid FROM #__kunena_users AS sbu" . "\n INNER JOIN #__users AS u" . "\n ON sbu.userid=u.id " . "\n LEFT JOIN #__session AS sess" . "\n ON sess.userid=u.id " . "\n LEFT JOIN #__kunena_banned_users AS banusers" . "\n ON banusers.userid=sbu.userid" . (count ( $where ) ? "\nWHERE " . implode ( ' AND ', $where ) : "") . "\n GROUP BY u.id ORDER BY sbu.moderator DESC", $limitstart, $limit );
	} else if ($order == 2) {
		$kunena_db->setQuery ( "SELECT sbu.*,u.*,sess.session_id, banusers.enabled AS usersbanenabled, banusers.bantype, banusers.userid AS banuid FROM #__kunena_users AS sbu" . "\n INNER JOIN #__users AS u " . "\n ON sbu.userid=u.id " . "\n LEFT JOIN #__session AS sess" . "\n ON sess.userid=u.id " . "\n LEFT JOIN #__kunena_banned_users AS banusers" . "\n ON banusers.userid=sbu.userid" . (count ( $where ) ? "\nWHERE " . implode ( ' AND ', $where ) : "") . "\n GROUP BY u.id ORDER BY u.name ASC ", $limitstart, $limit );
	} else if ($order == 3) {
		$kunena_db->setQuery ( "SELECT sbu.*,u.*,sess.session_id, banusers.enabled AS usersbanenabled, banusers.bantype, banusers.userid AS banuid FROM #__kunena_users AS sbu" . "\n INNER JOIN #__users AS u " . "\n ON sbu.userid=u.id " . "\n LEFT JOIN #__session AS sess" . "\n ON sess.userid=u.id " . "\n LEFT JOIN #__kunena_banned_users AS banusers" . "\n ON banusers.userid=sbu.userid" . (count ( $where ) ? "\nWHERE " . implode ( ' AND ', $where ) : "") . "\n GROUP BY u.id ORDER BY u.username ASC", $limitstart, $limit );
	} else if ($order < 1) {
		$kunena_db->setQuery ( "SELECT sbu.*,u.*,sess.session_id, banusers.enabled AS usersbanenabled, banusers.bantype, banusers.userid AS banuid FROM #__kunena_users AS sbu " . "\n INNER JOIN #__users AS u" . "\n ON sbu.userid=u.id " . "\n LEFT JOIN #__session AS sess" . "\n ON sess.userid=u.id " . "\n LEFT JOIN #__kunena_banned_users AS banusers" . "\n ON banusers.userid=sbu.userid" . (count ( $where ) ? "\nWHERE " . implode ( ' AND ', $where ) : "") . "\n GROUP BY u.id ORDER BY sbu.userid", $limitstart, $limit );
	}

	$profileList = $kunena_db->loadObjectList ();
	if (KunenaError::checkDatabaseError()) return;

	$countPL = count ( $profileList );

	jimport ( 'joomla.html.pagination' );
	$pageNavSP = new JPagination ( $total, $limitstart, $limit );
	html_Kunena::showProfiles ( $option, $profileList, $countPL, $pageNavSP, $order, $search );
}

function editUserProfile($option, $uid) {
	if (empty ( $uid [0] )) {
		echo JText::_('COM_KUNENA_PROFILE_NO_USER');
		return;
	}

	$kunena_db = &JFactory::getDBO ();
	$kunena_acl = &JFactory::getACL ();

	$kunena_db->setQuery ( "SELECT * FROM #__kunena_users LEFT JOIN #__users on #__users.id=#__kunena_users.userid WHERE userid=$uid[0]" );
	$userDetails = $kunena_db->loadObjectList ();
	if (KunenaError::checkDatabaseError()) return;
	$user = $userDetails [0];

	//Mambo userids are unique, so we don't worry about that
	$prefview = $user->view;
	$ordering = $user->ordering;
	$moderator = $user->moderator;
	$userRank = $user->rank;

	//grab all special ranks
	$kunena_db->setQuery ( "SELECT * FROM #__kunena_ranks WHERE rank_special = '1'" );
	$specialRanks = $kunena_db->loadObjectList ();
	if (KunenaError::checkDatabaseError()) return;

	//build select list options
	$yesnoRank [] = JHTML::_ ( 'select.option', '0', JText::_('COM_KUNENA_RANK_NO_ASSIGNED') );
	foreach ( $specialRanks as $ranks ) {
		$yesnoRank [] = JHTML::_ ( 'select.option', $ranks->rank_id, $ranks->rank_title );
	}
	//build special ranks select list
	$selectRank = JHTML::_ ( 'select.genericlist', $yesnoRank, 'newrank', 'class="inputbox" size="5"', 'value', 'text', $userRank );

	// make the select list for the view type
	$yesno [] = JHTML::_ ( 'select.option', 'flat', JText::_('COM_KUNENA_A_FLAT') );
	$yesno [] = JHTML::_ ( 'select.option', 'threaded', JText::_('COM_KUNENA_A_THREADED') );
	// build the html select list
	$selectPref = JHTML::_ ( 'select.genericlist', $yesno, 'newview', 'class="inputbox" size="2"', 'value', 'text', $prefview );
	// make the select list for the moderator flag
	$yesnoMod [] = JHTML::_ ( 'select.option', '1', JText::_('COM_KUNENA_ANN_YES') );
	$yesnoMod [] = JHTML::_ ( 'select.option', '0', JText::_('COM_KUNENA_ANN_NO') );
	// build the html select list
	$selectMod = JHTML::_ ( 'select.genericlist', $yesnoMod, 'moderator', 'class="inputbox" size="2"', 'value', 'text', $moderator );
	// make the select list for the moderator flag
	$yesnoOrder [] = JHTML::_ ( 'select.option', '0', JText::_('COM_KUNENA_USER_ORDER_ASC') );
	$yesnoOrder [] = JHTML::_ ( 'select.option', '1', JText::_('COM_KUNENA_USER_ORDER_DESC') );
	// build the html select list
	$selectOrder = JHTML::_ ( 'select.genericlist', $yesnoOrder, 'neworder', 'class="inputbox" size="2"', 'value', 'text', $ordering );

	//get all subscriptions for this user
	$kunena_db->setQuery ( "select thread from #__kunena_subscriptions where userid=$uid[0]" );
	$subslist = $kunena_db->loadObjectList ();
	if (KunenaError::checkDatabaseError()) return;

	//get all moderation category ids for this user
	$kunena_db->setQuery ( "select catid from #__kunena_moderation where userid=" . $uid [0] );
	$modCatList = $kunena_db->loadResultArray ();
	if (KunenaError::checkDatabaseError()) return;
	if ($moderator && empty($modCatList)) $modCatList[] = 0;

	$categoryList = array();
	$categoryList[] = JHTML::_('select.option', 0, JText::_('COM_KUNENA_GLOBAL_MODERATOR'));
	$modCats = CKunenaTools::KSelectList('catid[]', $categoryList, 'class="inputbox" multiple="multiple"', false, 'kforums', $modCatList);

	//get all IPs used by this user
	$kunena_db->setQuery ( "SELECT ip FROM #__kunena_messages WHERE userid=$uid[0] GROUP BY ip" );
	$iplist = implode("','", $kunena_db->loadResultArray ());
	if (KunenaError::checkDatabaseError()) return;

	$list = array();
	if ($iplist) {
		$iplist = "'{$iplist}'";
		$kunena_db->setQuery ( "SELECT m.ip,m.userid,u.username,COUNT(*) as mescnt FROM #__kunena_messages AS m INNER JOIN #__users AS u ON m.userid=u.id WHERE m.ip IN ({$iplist}) GROUP BY m.userid,m.ip" );
		$list = $kunena_db->loadObjectlist ();
		if (KunenaError::checkDatabaseError()) return;
	}
	$useridslist = array();
	foreach ($list as $item) {
		$useridslist[$item->ip][] = $item;
	}

	html_Kunena::editUserProfile ( $option, $user, $subslist, $selectRank, $selectPref, $selectMod, $selectOrder, $uid [0], $modCats, $useridslist );
}

function saveUserProfile($option) {
	$kunena_app = & JFactory::getApplication ();
	$kunena_db = &JFactory::getDBO ();
	$newview = JRequest::getVar ( 'newview' );
	$newrank = JRequest::getVar ( 'newrank' );
	$signature = JRequest::getVar ( 'message' );
	$deleteSig = JRequest::getVar ( 'deleteSig' );
	$moderator = JRequest::getVar ( 'moderator' );
	$uid = JRequest::getVar ( 'uid' );
	$avatar = JRequest::getVar ( 'avatar' );
	$deleteAvatar = JRequest::getVar ( 'deleteAvatar' );
	$neworder = JRequest::getVar ( 'neworder' );
	$modCatids = JRequest::getVar ( 'catid', array () );

	if ($deleteSig == 1) {
		$signature = "";
	}
	$avatar = '';
	if ($deleteAvatar == 1) {
		$avatar = ",avatar=''";
	}

	$kunena_db->setQuery ( "UPDATE #__kunena_users SET signature={$kunena_db->quote($signature)}, view='$newview',moderator='$moderator', ordering='$neworder', rank='$newrank' $avatar where userid=$uid" );
	$kunena_db->query ();
	if (KunenaError::checkDatabaseError()) return;

	//delete all moderator traces before anyway
	$kunena_db->setQuery ( "DELETE FROM #__kunena_moderation WHERE userid=$uid" );
	$kunena_db->query ();
	if (KunenaError::checkDatabaseError()) return;

	//if there are moderatored forums, add them all
	if ($moderator == 1) {
		if (!empty ( $modCatids ) && !in_array(0, $modCatids)) {
			foreach ( $modCatids as $c ) {
				$kunena_db->setQuery ( "INSERT INTO #__kunena_moderation SET catid='$c', userid='$uid'" );
				$kunena_db->query ();
				if (KunenaError::checkDatabaseError()) return;
			}
		}
	}

	$kunena_db->setQuery ( "UPDATE #__kunena_sessions SET allowed='na' WHERE userid='$uid'" );
	$kunena_db->query ();
	KunenaError::checkDatabaseError();

	$kunena_app->redirect ( JURI::base () . "index.php?option=com_kunena&task=showprofiles" );
}

function trashUserMessages ( $option, $uid ) {
	$kunena_db = &JFactory::getDBO ();
	$kunena_app = & JFactory::getApplication ();

	$path = KUNENA_PATH_LIB.'/kunena.moderation.class.php';
	require_once ($path);
	$kunena_mod = CKunenaModeration::getInstance();

	$uids = implode ( ',', $uid );
	if ($uids) {
		//select only the messages which aren't already in the trash
		$kunena_db->setQuery ( "SELECT id FROM #__kunena_messages WHERE hold!=2 AND userid IN ('$uids')" );
		$idusermessages = $kunena_db->loadObjectList ();
		if (KunenaError::checkDatabaseError()) return;
		foreach ($idusermessages as $messageID) {
			$kunena_mod->deleteMessage($messageID->id, $DeleteAttachments = false);
		}
	}
	$kunena_app->redirect ( JURI::base () . "index.php?option=com_kunena&task=profiles" , JText::_('COM_KUNENA_A_USERMES_TRASHED_DONE'));
}

function moveUserMessages ( $option, $uid ){
	$kunena_db = &JFactory::getDBO ();
	$return = JRequest::getCmd( 'return', 'edituserprofile', 'post' );

	$kunena_db->setQuery ( "SELECT id,parent,name FROM #__kunena_categories" );
	$catsList = $kunena_db->loadObjectList ();
	if (KunenaError::checkDatabaseError()) return;

	foreach ($catsList as $cat) {
		if ($cat->parent) {
			$category[] = JHTML::_('select.option', $cat->id, '...'.$cat->name);
		} else {
			$category[] = JHTML::_('select.option', $cat->id, $cat->name);
		}
	}
	$lists = JHTML::_('select.genericlist', $category, 'cid[]', 'class="inputbox" multiple="multiple" size="5"', 'value', 'text');

	html_Kunena::moveUserMessages ( $option, $return, $uid, $lists );
}

function moveUserMessagesNow ( $option, $cid ) {
	$kunena_mod = CKunenaModeration::getInstance();
	$kunena_db = &JFactory::getDBO ();
	$kunena_app = & JFactory::getApplication ();

	$path = KUNENA_PATH_LIB  .'/kunena.moderation.class.php';
	require_once ($path);
	$kunena_mod = CKunenaModeration::getInstance();

	$uid = JRequest::getVar( 'uid', '', 'post' );
	if ($uid) {
		$kunena_db->setQuery ( "SELECT id,thread FROM #__kunena_messages WHERE hold=0 AND userid IN ('$uid')" );
		$idusermessages = $kunena_db->loadObjectList ();
		if (KunenaError::checkDatabaseError()) return;
		if ( !empty($idusermessages) ) {
			foreach ($idusermessages as $id) {
				$kunena_mod->moveMessage($id->id, $cid[0], $TargetSubject = '', $TargetMessageID = 0);
			}
		}
	}
	$kunena_app->redirect ( JURI::base () . "index.php?option=com_kunena&task=profiles", JText::_('COM_A_KUNENA_USERMES_MOVED_DONE') );
}

function logout ( $option, $uid ) {
	$kunena_app = & JFactory::getApplication ();
	$path = KUNENA_PATH_LIB  .'/kunena.moderation.tools.class.php';
	require_once ($path);
	$user_mod = new CKunenaModerationTools();

	foreach ( $uid as $UserID) {
		$user_mod->logoutUser($UserID);
	}

	$kunena_app->redirect ( JURI::base () . "index.php?option=com_kunena&task=profiles", JText::_('COM_A_KUNENA_USER_LOGOUT_DONE') );
}

function deleteUser ( $option, $uid ) {
	$kunena_app = & JFactory::getApplication ();
	$path = KUNENA_PATH_LIB  .'/kunena.moderation.tools.class.php';
	require_once ($path);
	$user_mod = new CKunenaModerationTools();

	foreach ($uid as $id) {
		$deleteuser = $user_mod->deleteUser($id);
		if (!$deleteuser) {
			$message = $user_mod->getErrorMessage();
		} else {
			$message = JText::_('COM_A_KUNENA_USER_DELETE_DONE');
		}
	}

	$kunena_app->redirect ( JURI::base () . "index.php?option=com_kunena&task=profiles", $message );
}

function userblock ( $option, $uid = null, $block = 1 ) {
	$kunena_app = & JFactory::getApplication ();
	$path = KUNENA_PATH_LIB  .'/kunena.moderation.tools.class.php';
	require_once ($path);
	$user_mod = new CKunenaModerationTools();

	if (! is_array ( $uid ) || count ( $uid ) < 1) {
		$action = $block ? 'userblock' : 'userunblock';
		echo "<script> alert('" . JText::_('COM_KUNENA_SELECTANITEMTO') . " $action'); window.history.go(-1);</script>\n";
		exit ();
	}

	$disableuseraccount = $user_mod->blockUser($uid[0], '0000-00-00 00:00:00', '', '');
	if (!$disableuseraccount) {
		$message = $user_mod->getErrorMessage();
	} else {
		$message = JText::_('COM_A_KUNENA_USER_BLOCKED_DONE');
	}

	$kunena_app->redirect ( JURI::base () . "index.php?option=com_kunena&task=profiles", $message );
}

function userunblock ( $option, $uid = null, $block = 0 ) {
	$kunena_app = & JFactory::getApplication ();
	$path = KUNENA_PATH_LIB  .'/kunena.moderation.tools.class.php';
	require_once ($path);
	$user_mod = new CKunenaModerationTools();

	if (! is_array ( $uid ) || count ( $uid ) < 1) {
		$action = $block ? 'userblock' : 'userunblock';
		echo "<script> alert('" . JText::_('COM_KUNENA_SELECTANITEMTO') . " $action'); window.history.go(-1);</script>\n";
		exit ();
	}

	$enableuseraccount = $user_mod->unblockUser($uid[0]);
	if (!$enableuseraccount) {
			$message = $user_mod->getErrorMessage();
	} else {
			$message = JText::_('COM_A_KUNENA_USER_UNBLOCKED_DONE');
	}

	$kunena_app->redirect ( JURI::base () . "index.php?option=com_kunena&task=profiles", $message );
}

function userban ( $option, $uid, $block=1 ) {
	$kunena_app = & JFactory::getApplication ();
	$path = KUNENA_PATH_LIB  .'/kunena.moderation.tools.class.php';
	require_once ($path);
	$user_mod = new CKunenaModerationTools();

	$banuser = $user_mod->banUser($uid[0], '0000-00-00 00:00:00', '', '');
	if (!$banuser) {
		$message = $user_mod->getErrorMessage();
	} else {
		$message = JText::_('COM_A_KUNENA_USER_BANNED_DONE');
	}

	$kunena_app->redirect ( JURI::base () . "index.php?option=com_kunena&task=profiles", $message );
}

function userunban ( $option, $uid, $block=0 ) {
	$kunena_app = & JFactory::getApplication ();
	$path = KUNENA_PATH_LIB  .'/kunena.moderation.tools.class.php';
	require_once ($path);
	$user_mod = new CKunenaModerationTools();

	$unbanuser = $user_mod->unbanUser($uid[0]);
	if (!$unbanuser) {
		$message = $user_mod->getErrorMessage();
	} else {
		$message = JText::_('COM_A_KUNENA_USER_UNBANNED_DONE');
	}

	$kunena_app->redirect ( JURI::base () . "index.php?option=com_kunena&task=profiles", $message );
}

//===============================
// Prune Forum functions
//===============================
function pruneforum($kunena_db, $option) {
	$forums_list = array ();
	//get forum list; locked forums are excluded from pruning
	$kunena_db->setQuery ( "SELECT a.id as value, a.name as text" . "\nFROM #__kunena_categories AS a" . "\nWHERE a.parent != '0'" . "\nAND a.locked != '1'" . "\nORDER BY parent, ordering" );
	//get all subscriptions for this user
	$forums_list = $kunena_db->loadObjectList ();
	if (KunenaError::checkDatabaseError()) return;
	$forumList ['forum'] = JHTML::_ ( 'select.genericlist', $forums_list, 'prune_forum', 'class="inputbox" size="4"', 'value', 'text', '' );
	html_Kunena::pruneforum ( $option, $forumList );
}

function doprune($kunena_db, $option) {
	require_once (KUNENA_PATH_LIB.'/kunena.timeformat.class.php');
	$kunena_app = & JFactory::getApplication ();

	$catid = JRequest::getInt ( 'prune_forum', - 1 );
	$deleted = 0;

	if ($catid == - 1) {
		echo "<script> alert('" . JText::_('COM_KUNENA_CHOOSEFORUMTOPRUNE') . "'); window.history.go(-1); </script>\n";
		$kunena_app->close ();
	}

	// Convert days to seconds for timestamp functions...
	$prune_days = intval ( JRequest::getVar ( 'prune_days', 36500 ) );
	$prune_date = CKunenaTimeformat::internalTime () - ($prune_days * 86400);

	//get the thread list for this forum
	$kunena_db->setQuery ( "SELECT t.thread, MAX(m.time) AS lasttime
		FROM #__kunena_messages AS m
		LEFT JOIN #__kunena_messages AS t ON m.thread=t.thread AND t.parent=0
		WHERE m.catid={$catid} AND t.ordering = 0
		GROUP BY thread
		HAVING lasttime < {$prune_date}" );
	$threadlist = $kunena_db->loadResultArray ();
	if (KunenaError::checkDatabaseError()) return;

	require_once(KUNENA_PATH_LIB.DS.'kunena.attachments.class.php');
	foreach ( $threadlist as $thread ) {
		//get the id's for all posts belonging to this thread
		$kunena_db->setQuery ( "SELECT id FROM #__kunena_messages WHERE thread={$thread}" );
		$idlist = $kunena_db->loadResultArray ();
		if (KunenaError::checkDatabaseError()) return;

		if (count ( $idlist ) > 0) {
			//prune all messages belonging to the thread
			$deleted += count ($idlist);
			$idlist = implode(',', $idlist);
			$attachments = CKunenaAttachments::getInstance();
			$attachments->deleteMessage($idlist);

			$kunena_db->setQuery ( "DELETE m, t FROM #__kunena_messages AS m INNER JOIN #__kunena_messages_text AS t ON m.id=t.mesid WHERE m.thread={$thread}" );
			$kunena_db->query ();
			if (KunenaError::checkDatabaseError()) return;
		}
		unset ($idlist);
	}
	if (!empty($threadlist)) {
		$threadlist = implode(',', $threadlist);
		//clean all subscriptions to these deleted threads
		$kunena_db->setQuery ( "DELETE FROM #__kunena_subscriptions WHERE thread IN ({$threadlist})" );
		$kunena_db->query ();
		if (KunenaError::checkDatabaseError()) return;

		//clean all favorites to these deleted threads
		$kunena_db->setQuery ( "DELETE FROM #__kunena_favorites WHERE thread IN ({$threadlist})" );
		$kunena_db->query ();
		if (KunenaError::checkDatabaseError()) return;
	}
	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=pruneforum", "" . JText::_('COM_KUNENA_FORUMPRUNEDFOR') . " " . $prune_days . " " . JText::_('COM_KUNENA_PRUNEDAYS') . "; " . JText::_('COM_KUNENA_PRUNEDELETED') . $deleted . " " . JText::_('COM_KUNENA_PRUNETHREADS') );
}

//===============================
// Sync users
//===============================
function syncusers($kunena_db, $option) {
	html_Kunena::syncusers ( $option );
}

function douserssync($kunena_db, $option) {
	$usercache = JRequest::getBool ( 'usercache', 0 );
	$useradd = JRequest::getBool ( 'useradd', 0 );
	$userdel = JRequest::getBool ( 'userdel', 0 );
	$userrename = JRequest::getBool ( 'userrename', 0 );

	$kunena_app = & JFactory::getApplication ();
	$kunena_db = &JFactory::getDBO ();

	if ($usercache) {
		//reset access rights
		$kunena_db->setQuery ( "UPDATE #__kunena_sessions SET allowed='na'" );
		$kunena_db->query ();
		if (KunenaError::checkDatabaseError()) return;
		$kunena_app->enqueueMessage ( JText::_('COM_KUNENA_SYNC_USERS_DO_CACHE') );
	}
	if ($useradd) {
		$kunena_db->setQuery ( "INSERT INTO #__kunena_users (userid) SELECT a.id FROM #__users AS a LEFT JOIN #__kunena_users AS b ON b.userid=a.id WHERE b.userid IS NULL" );
		$kunena_db->query ();
		if (KunenaError::checkDatabaseError()) return;
		$kunena_app->enqueueMessage ( JText::_('COM_KUNENA_SYNC_USERS_DO_ADD') . ' ' . $kunena_db->getAffectedRows () );
	}
	if ($userdel) {
		$kunena_db->setQuery ( "DELETE a FROM #__kunena_users AS a LEFT JOIN #__users AS b ON a.userid=b.id WHERE b.username IS NULL" );
		$kunena_db->query ();
		if (KunenaError::checkDatabaseError()) return;
		$kunena_app->enqueueMessage ( JText::_('COM_KUNENA_SYNC_USERS_DO_DEL') . ' ' . $kunena_db->getAffectedRows () );
	}
	if ($userrename) {
		$cnt = CKunenaTools::updateNameInfo ();
		$kunena_app->enqueueMessage ( JText::_('COM_KUNENA_SYNC_USERS_DO_RENAME') . " $cnt" );
	}

	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=syncusers" );
}

//===============================
// Uploaded Images browser
//===============================
function browseUploaded($kunena_db, $option, $type) {
	$kunena_db = &JFactory::getDBO ();
	$kunena_config = KunenaFactory::getConfig ();

	if ($type) {
		$extensionsAllowed = explode(',',$kunena_config->imagetypes);
	} else {
		$extensionsAllowed = explode(',',$kunena_config->filetypes);
	}

	// type = 1 -> images ; type = 0 -> files

	$image_types =	explode(',',$kunena_config->imagemimetypes);
	$imageTypes = array();
	foreach ($image_types as $images ) {
		$imageTypes[] = "'".trim($images)."'";
	}
	$imageTypes= implode(',',$imageTypes);
	if ($type) {
		$where = ' WHERE filetype IN ('.$imageTypes.')';
	} else {
		$where = ' WHERE filetype NOT IN ('.$imageTypes.')';
	}

	$query = "SELECT a.*, b.catid, b.thread FROM #__kunena_attachments AS a LEFT JOIN #__kunena_messages AS b ON a.mesid=b.id $where";
	$kunena_db->setQuery ( $query );
	$uploaded = $kunena_db->loadObjectlist();
	if (KunenaError::checkDatabaseError()) return;

	html_Kunena::browseUploaded ( $option, $uploaded, $type );
}

function deleteAttachment($id, $redirect, $message) {
	$kunena_app = & JFactory::getApplication ();
	$kunena_db = &JFactory::getDBO ();
	if (! $id) {
		$kunena_app->redirect ( $redirect );
		return;
	}

	require_once (KUNENA_PATH_LIB.DS.'kunena.attachments.class.php');
	$attachments = CKunenaAttachments::getInstance();
	$attachments->deleteAttachment($id);

	$kunena_app->enqueueMessage ( JText::_($message) );
	$kunena_app->redirect ( $redirect );
}

//===============================
//   smiley functions
//===============================
//
// Read a listing of uploaded smilies for use in the add or edit smiley code...
//
function collect_smilies_ranks($path) {
  $smiley_rank_images = JFolder::Files($path,false,false,false,array('index.php'));
  return $smiley_rank_images;
}

function showsmilies($option) {
	$kunena_db = &JFactory::getDBO ();
	$kunena_app = & JFactory::getApplication ();

	$limit = $kunena_app->getUserStateFromRequest ( "global.list.limit", 'limit', $kunena_app->getCfg ( 'list_limit' ), 'int' );
	$limitstart = $kunena_app->getUserStateFromRequest ( "{$option}.limitstart", 'limitstart', 0, 'int' );
	$kunena_db->setQuery ( "SELECT COUNT(*) FROM #__kunena_smileys" );
	$total = $kunena_db->loadResult ();
	if (KunenaError::checkDatabaseError()) return;
	if ($limitstart >= $total)
		$limitstart = 0;
	if ($limit == 0 || $limit > 100)
		$limit = 100;

	$kunena_db->setQuery ( "SELECT * FROM #__kunena_smileys", $limitstart, $limit );
	$smileytmp = $kunena_db->loadObjectList ();
	if (KunenaError::checkDatabaseError()) return;

	$smileypath = smileypath ();

	jimport ( 'joomla.html.pagination' );
	$pageNavSP = new JPagination ( $total, $limitstart, $limit );
	html_Kunena::showsmilies ( $option, $smileytmp, $pageNavSP, $smileypath );

}


	/* *
	 * upload smilies
	 */
	function uploadsmilies()
	{
		$kunena_config = KunenaFactory::getConfig ();
		$kunena_app = & JFactory::getApplication ();
		// load language fo component media
		JPlugin::loadLanguage( 'com_media' );
		$params =& JComponentHelper::getParams('com_media');
		require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_media'.DS.'helpers'.DS.'media.php' );
		define('COM_KUNENA_MEDIA_BASE', JPATH_ROOT.DS.'components'.DS.'com_kunena'.DS.'template'.DS.$kunena_config->template.DS.'images');
		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );

		$file 			= JRequest::getVar( 'Filedata', '', 'files', 'array' );
		$foldersmiley	= JRequest::getVar( 'foldersmiley', 'emoticons', '', 'path' );
		$format			= JRequest::getVar( 'format', 'html', '', 'cmd');
		$return			= JRequest::getVar( 'return-url', null, 'post', 'base64' );
		$err			= null;

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		// Make the filename safe
		jimport('joomla.filesystem.file');
		$file['name']	= JFile::makeSafe($file['name']);

		if (isset($file['name'])) {
			$filepathsmiley = JPath::clean(COM_KUNENA_MEDIA_BASE.DS.$foldersmiley.DS.strtolower($file['name']));

			if (!MediaHelper::canUpload( $file, $err )) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'Invalid: '.$filepathsmiley.': '.$err));
					header('HTTP/1.0 415 Unsupported Media Type');
					jexit('Error. Unsupported Media Type!');
				} else {
					JError::raiseNotice(100, JText::_($err));
					// REDIRECT
					if ($return) {
						$kunena_app->redirect(base64_decode($return));
					}
					return;
				}
			}

			if (JFile::exists($filepathsmiley)) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'File already exists: '.$filepathsmiley));
					header('HTTP/1.0 409 Conflict');
					jexit('Error. File already exists');
				} else {
					JError::raiseNotice(100, JText::_('COM_KUNENA_A_EMOTICONS_UPLOAD_ERROR_EXIST'));
					// REDIRECT
					if ($return) {
						$kunena_app->redirect(base64_decode($return));
					}
					return;
				}
			}

			if (!JFile::upload($file['tmp_name'], $filepathsmiley)) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'Cannot upload: '.$filepathsmiley));
					header('HTTP/1.0 400 Bad Request');
					jexit('Error. Unable to upload file');
				} else {
					JError::raiseWarning(100, JText::_('COM_KUNENA_A_EMOTICONS_UPLOAD_ERROR_UNABLE'));
					// REDIRECT
					if ($return) {
						$kunena_app->redirect(base64_decode($return));
					}
					return;
				}
			} else {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance();
					$log->addEntry(array('comment' => $foldersmiley));
					jexit('Upload complete');
				} else {
					$kunena_app->enqueueMessage(JText::_('COM_KUNENA_A_EMOTICONS_UPLOAD_SUCCESS'));
					// REDIRECT
					if ($return) {
						$kunena_app->redirect(base64_decode($return));
					}
					return;
				}
			}
		} else {
			$kunena_app->redirect('index.php', 'Invalid Request', 'error');
		}
	}

function editsmiley($option, $id) {
	$kunena_db = &JFactory::getDBO ();
	$kunena_db->setQuery ( "SELECT * FROM #__kunena_smileys WHERE id = $id" );

	$smileytmp = $kunena_db->loadAssocList ();
	if (KunenaError::checkDatabaseError()) return;
	$smileycfg = $smileytmp [0];

	$smileypath = smileypath ();
	$smileypathabs = $smileypath ['abs'];
	$smileypath = $smileypath ['live'];

	$smiley_images = collect_smilies_ranks ($smileypathabs);

	$smiley_edit_img = '';

	$filename_list = "";
	for($i = 0; $i < count ( $smiley_images ); $i ++) {
		if ($smiley_images [$i] == $smileycfg ['location']) {
			$smiley_selected = "selected=\"selected\"";
			$smiley_edit_img = $smileypath . $smiley_images [$i];
		} else {
			$smiley_selected = "";
		}

		$filename_list .= '<option value="' . $smiley_images [$i] . '"' . $smiley_selected . '>' . $smiley_images [$i] . '</option>' . "\n";
	}
	html_Kunena::editsmiley ( $option, $smiley_edit_img, $filename_list, $smileypath, $smileycfg );
}

function newsmiley($option) {
	$kunena_db = &JFactory::getDBO ();

	$smileypath = smileypath ();
	$smileypathabs = $smileypath ['abs'];
	$smileypath = $smileypath ['live'];

	$smiley_images = collect_smilies_ranks ($smileypathabs);

	$filename_list = "";
	for($i = 0; $i < count ( $smiley_images ); $i ++) {
		$filename_list .= '<option value="' . $smiley_images [$i] . '">' . $smiley_images [$i] . '</option>' . "\n";
	}

	html_Kunena::newsmiley ( $option, $filename_list, $smileypath );
}

function savesmiley($option, $id = NULL) {
	$kunena_app = & JFactory::getApplication ();
	$kunena_db = &JFactory::getDBO ();

	$smiley_code = JRequest::getVar ( 'smiley_code' );
	$smiley_location = JRequest::getVar ( 'smiley_url' );
	$smiley_emoticonbar = (JRequest::getVar ( 'smiley_emoticonbar' )) ? JRequest::getVar ( 'smiley_emoticonbar' ) : 0;

	if (empty ( $smiley_code ) || empty ( $smiley_location )) {
		$task = ($id == NULL) ? 'newsmiley' : 'editsmiley&id=' . $id;
		$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=" . $task, JText::_('COM_KUNENA_MISSING_PARAMETER') );
		$kunena_app->close ();
	}

	$kunena_db->setQuery ( "SELECT * FROM #__kunena_smileys" );

	$smilies = $kunena_db->loadAssocList ();
	if (KunenaError::checkDatabaseError()) return;
	foreach ( $smilies as $value ) {
		if (in_array ( $smiley_code, $value ) && ! ($value ['id'] == $id)) {
			$task = ($id == NULL) ? 'newsmiley' : 'editsmiley&id=' . $id;
			$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=" . $task, JText::_('COM_KUNENA_CODE_ALLREADY_EXITS') );
			$kunena_app->close ();
		}

	}

	if ($id == NULL) {
		$kunena_db->setQuery ( "INSERT INTO #__kunena_smileys SET code = '$smiley_code', location = '$smiley_location', emoticonbar = '$smiley_emoticonbar'" );
	} else {
		$kunena_db->setQuery ( "UPDATE #__kunena_smileys SET code = '$smiley_code', location = '$smiley_location', emoticonbar = '$smiley_emoticonbar' WHERE id = $id" );
	}

	$kunena_db->query ();
	if (KunenaError::checkDatabaseError()) return;

	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=showsmilies", JText::_('COM_KUNENA_SMILEY_SAVED') );
}

function deletesmiley($option, $cid) {
	$kunena_app = & JFactory::getApplication ();
	$kunena_db = &JFactory::getDBO ();

	$cids = implode ( ',', $cid );

	if ($cids) {
		$kunena_db->setQuery ( "DELETE FROM #__kunena_smileys WHERE id IN ($cids)" );
		$kunena_db->query ();
		if (KunenaError::checkDatabaseError()) return;
	}

	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=showsmilies", JText::_('COM_KUNENA_SMILEY_DELETED') );
}

function smileypath() {
	$kunena_config = KunenaFactory::getConfig ();
	$smiley_live_path = KUNENA_URLEMOTIONSPATH;
	$smiley_abs_path = KUNENA_ABSEMOTIONSPATH;

	$smileypath ['live'] = $smiley_live_path;
	$smileypath ['abs'] = $smiley_abs_path;

	return $smileypath;
}
//===============================
//  FINISH smiley functions
//===============================


//===============================
// Rank Administration
//===============================
//Dan Syme/IGD - Ranks Management


function showRanks($option) {
	$kunena_app = & JFactory::getApplication ();
	$kunena_db = &JFactory::getDBO ();

	$order = JRequest::getVar ( 'order', '' );
	$limit = $kunena_app->getUserStateFromRequest ( "global.list.limit", 'limit', $kunena_app->getCfg ( 'list_limit' ), 'int' );
	$limitstart = $kunena_app->getUserStateFromRequest ( "{$option}.limitstart", 'limitstart', 0, 'int' );
	$kunena_db->setQuery ( "SELECT COUNT(*) FROM #__kunena_ranks" );
	$total = $kunena_db->loadResult ();
	if (KunenaError::checkDatabaseError()) return;
	if ($limitstart >= $total)
		$limitstart = 0;
	if ($limit == 0 || $limit > 100)
		$limit = 100;

	$kunena_db->setQuery ( "SELECT * FROM #__kunena_ranks", $limitstart, $limit );
	$ranks = $kunena_db->loadObjectList ();
	if (KunenaError::checkDatabaseError()) return;

	$rankpath = rankpath ();

	jimport ( 'joomla.html.pagination' );
	$pageNavSP = new JPagination ( $total, $limitstart, $limit );
	html_Kunena::showRanks ( $option, $ranks, $pageNavSP, $order, $rankpath );

}


	/* *
	 * upload ranks
	 */
	function uploadranks()
	{
		$kunena_config = KunenaFactory::getConfig ();
		$kunena_app = & JFactory::getApplication ();
		// load language fo component media
		JPlugin::loadLanguage( 'com_media' );
		$params =& JComponentHelper::getParams('com_media');
		require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_media'.DS.'helpers'.DS.'media.php' );
		define('COM_KUNENA_MEDIA_BASE', JPATH_ROOT.DS.'components'.DS.'com_kunena'.DS.'template'.DS.$kunena_config->template.DS.'images');
		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );

		$file 			= JRequest::getVar( 'Filedata', '', 'files', 'array' );
		$folderranks	= JRequest::getVar( 'folderranks', 'ranks', '', 'path' );
		$format			= JRequest::getVar( 'format', 'html', '', 'cmd');
		$return			= JRequest::getVar( 'return-url', null, 'post', 'base64' );
		$err			= null;

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		// Make the filename safe
		jimport('joomla.filesystem.file');
		$file['name']	= JFile::makeSafe($file['name']);

		if (isset($file['name'])) {
			$filepathranks = JPath::clean(COM_KUNENA_MEDIA_BASE.DS.$folderranks.DS.strtolower($file['name']));

			if (!MediaHelper::canUpload( $file, $err )) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'Invalid: '.$filepathranks.': '.$err));
					header('HTTP/1.0 415 Unsupported Media Type');
					jexit('Error. Unsupported Media Type!');
				} else {
					JError::raiseNotice(100, JText::_($err));
					// REDIRECT
					if ($return) {
						$kunena_app->redirect(base64_decode($return));
					}
					return;
				}
			}

			if (JFile::exists($filepathranks)) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'File already exists: '.$filepathranks));
					header('HTTP/1.0 409 Conflict');
					jexit('Error. File already exists');
				} else {
					JError::raiseNotice(100, JText::_('COM_KUNENA_A_RANKS_UPLOAD_ERROR_EXIST'));
					// REDIRECT
					if ($return) {
						$kunena_app->redirect(base64_decode($return));
					}
					return;
				}
			}

			if (!JFile::upload($file['tmp_name'], $filepathranks)) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'Cannot upload: '.$filepathranks));
					header('HTTP/1.0 400 Bad Request');
					jexit('Error. Unable to upload file');
				} else {
					JError::raiseWarning(100, JText::_('COM_KUNENA_A_RANKS_UPLOAD_ERROR_UNABLE'));
					// REDIRECT
					if ($return) {
						$kunena_app->redirect(base64_decode($return));
					}
					return;
				}
			} else {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance();
					$log->addEntry(array('comment' => $filepathranks));
					jexit('Upload complete');
				} else {
					$kunena_app->enqueueMessage(JText::_('COM_KUNENA_A_RANKS_UPLOAD_SUCCESS'));
					// REDIRECT
					if ($return) {
						$kunena_app->redirect(base64_decode($return));
					}
					return;
				}
			}
		} else {
			$kunena_app->redirect('index.php', 'Invalid Request', 'error');
		}
	}


function rankpath() {
	$rankpath ['live'] = KUNENA_URLRANKSPATH;
	$rankpath ['abs'] = KUNENA_ABSRANKSPATH;

	return $rankpath;

}

function newRank($option) {
	$kunena_db = &JFactory::getDBO ();

	$rankpath = rankpath ();
	$rankpathabs = $rankpath ['abs'];
	$rankpath = $rankpath ['live'];

	$rank_images = collect_smilies_ranks($rankpathabs);

	$filename_list = "";
	$i = 0;
	foreach ( $rank_images as $id => $row ) {
		$filename_list .= '<option value="' . $rank_images [$id] . '">' . $rank_images [$id] . '</option>' . "\n";
	}

	html_Kunena::newRank ( $option, $filename_list, $rankpath );
}

function deleteRank($option, $cid = null) {
	$kunena_db = &JFactory::getDBO ();
	$kunena_app = & JFactory::getApplication ();

	$cids = implode ( ',', $cid );
	if ($cids) {
		$kunena_db->setQuery ( "DELETE FROM #__kunena_ranks WHERE rank_id IN ($cids)" );
		$kunena_db->query ();
		if (KunenaError::checkDatabaseError()) return;
	}

	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=ranks", JText::_('COM_KUNENA_RANK_DELETED') );
}

function saveRank($option, $id = NULL) {
	$kunena_app = & JFactory::getApplication ();
	$kunena_db = &JFactory::getDBO ();

	$rank_title = JRequest::getVar ( 'rank_title' );
	$rank_image = JRequest::getVar ( 'rank_image' );
	$rank_special = JRequest::getVar ( 'rank_special' );
	$rank_min = JRequest::getVar ( 'rank_min' );

	if (empty ( $rank_title ) || empty ( $rank_image )) {
		$task = ($id == NULL) ? 'newRank' : 'editRank&id=' . $id;
		$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=" . $task, JText::_('COM_KUNENA_MISSING_PARAMETER') );
		$kunena_app->close ();
	}

	$kunena_db->setQuery ( "SELECT * FROM #__kunena_ranks" );
	$ranks = $kunena_db->loadAssocList ();
	if (KunenaError::checkDatabaseError()) return;
	foreach ( $ranks as $value ) {
		if (in_array ( $rank_title, $value ) && ! ($value ['rank_id'] == $id)) {
			$task = ($id == NULL) ? 'newRank' : 'editRank&id=' . $id;
			$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=" . $task, JText::_('COM_KUNENA_RANK_ALLREADY_EXITS') );
			$kunena_app->close ();
		}
	}

	if ($id == NULL) {
		$kunena_db->setQuery ( "INSERT INTO #__kunena_ranks SET rank_title = '$rank_title', rank_image = '$rank_image', rank_special = '$rank_special', rank_min = '$rank_min'" );
	} else {
		$kunena_db->setQuery ( "UPDATE #__kunena_ranks SET rank_title = '$rank_title', rank_image = '$rank_image', rank_special = '$rank_special', rank_min = '$rank_min' WHERE rank_id = $id" );
	}
	$kunena_db->query ();
	if (KunenaError::checkDatabaseError()) return;

	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=ranks", JText::_('COM_KUNENA_RANK_SAVED') );
}

function editRank($option, $id) {
	$kunena_db = &JFactory::getDBO ();

	$kunena_db->setQuery ( "SELECT * FROM #__kunena_ranks WHERE rank_id = '$id'" );
	$ranks = $kunena_db->loadObjectList ();
	if (KunenaError::checkDatabaseError()) return;

	$path = rankpath ();
	$pathabs = $path ['abs'];
	$path = $path ['live'];

	$rank_images = collect_smilies_ranks($pathabs);

	$edit_img = $filename_list = '';

	foreach ( $ranks as $row ) {
		foreach ( $rank_images as $img ) {
			$image = $path . $img;

			if ($img == $row->rank_image) {
				$selected = ' selected="selected"';
				$edit_img = $path . $img;
			} else {
				$selected = '';
			}

			if (JString::strlen ( $img ) > 255) {
				continue;
			}

			$filename_list .= '<option value="' . kunena_htmlspecialchars ( $img ) . '"' . $selected . '>' . $img . '</option>';
		}
	}

	html_Kunena::editRank ( $option, $edit_img, $filename_list, $path, $row );
}

//===============================
//  FINISH rank functions
//===============================
// Dan Syme/IGD - Ranks Management

//===============================
// Trash management
//===============================
function showtrashview($option) {
	$kunena_app = & JFactory::getApplication ();
	$kunena_db = &JFactory::getDBO ();
	$filter_order		= $kunena_app->getUserStateFromRequest( $option.'filter_order',		'filter_order',		'subject', 'cmd' );
	$filter_order_Dir	= $kunena_app->getUserStateFromRequest( $option.'filter_order_Dir',	'filter_order_Dir',	'asc',			'word' );
	$search				= $kunena_app->getUserStateFromRequest( $option.'search',						'search', 			'',			'string' );
	$search				= JString::strtolower( $search );

	$order = JRequest::getVar ( 'order', '' );
	$limit = $kunena_app->getUserStateFromRequest ( "global.list.limit", 'limit', $kunena_app->getCfg ( 'list_limit' ), 'int' );
	$limitstart = $kunena_app->getUserStateFromRequest ( "{$option}.limitstart", 'limitstart', 0, 'int' );
	$kunena_db->setQuery ( "SELECT COUNT(*) FROM #__kunena_messages WHERE hold=2" );
	$total = $kunena_db->loadResult ();
	if (KunenaError::checkDatabaseError()) return;
	if ($limitstart >= $total)
		$limitstart = 0;
	if ($limit == 0 || $limit > 100)
		$limit = 100;

	$where 	= ' WHERE hold=2 ';

	if ($search) {
		$where .= ' AND LOWER( a.subject ) LIKE '.$kunena_db->Quote( '%'.$kunena_db->getEscaped( $search, true ).'%', false ).' OR LOWER( c.username )LIKE '.$kunena_db->Quote( '%'.$kunena_db->getEscaped( $search, true ).'%', false ).' OR  a.thread LIKE '.$kunena_db->Quote( '%'.$kunena_db->getEscaped( $search, true ).'%', false );
	}

	$orderby = ' ORDER BY '. $filter_order .' '. $filter_order_Dir;

	$query = 'SELECT a.*, b.name AS cats_name, c.username FROM #__kunena_messages AS a
	INNER JOIN #__kunena_categories AS b ON a.catid=b.id
	LEFT JOIN #__users AS c ON a.userid=c.id'
	.$where
	.$orderby;
	$kunena_db->setQuery ( $query, $limitstart, $limit );
	$trashitems = $kunena_db->loadObjectList ();
	if (KunenaError::checkDatabaseError()) return;

	// table ordering
	$lists['order_Dir']	= $filter_order_Dir;
	$lists['order']		= $filter_order;

	jimport ( 'joomla.html.pagination' );
	$pageNavSP = new JPagination ( $total, $limitstart, $limit );

	$lists['search']= $search;

	html_Kunena::showtrashview ( $option, $trashitems, $pageNavSP, $lists );
}

function trashpurge($option, $cid) {
	$kunena_db = &JFactory::getDBO ();
	$return = JRequest::getCmd( 'return', 'showtrashview', 'post' );

	$cids = implode ( ',', $cid );
	if ($cids) {
		$kunena_db->setQuery ( "SELECT * FROM #__kunena_messages WHERE hold=2 AND id IN ($cids)");
		$items = $kunena_db->loadObjectList ();
		if (KunenaError::checkDatabaseError()) return;
	}

	html_Kunena::trashpurge ( $option, $return, $cid, $items );
}

function deleteitemsnow ( $option, $cid ) {
	$kunena_app = & JFactory::getApplication ();
	$kunena_db = &JFactory::getDBO ();
	$path = KUNENA_PATH_LIB  .'/kunena.moderation.class.php';
	require_once ($path);
	$kunena_mod = CKunenaModeration::getInstance();

	$cids = implode ( ',', $cid );
	if ($cids) {
		foreach ($cid as $id ) {
			$kunena_db->setQuery ( "SELECT a.parent, a.id, b.threadid FROM #__kunena_messages AS a INNER JOIN #__kunena_polls AS b ON b.threadid=a.id WHERE threadid='{$id}'" );
			$mes = $kunena_db->loadObjectList ();
			if (KunenaError::checkDatabaseError()) return;
			if( !empty($mes[0])) {
				if ($mes[0]->parent == '0' && !empty($mes[0]->threadid) ) {
					//remove of poll
					require_once (KUNENA_PATH_LIB .DS. 'kunena.poll.class.php');
					$poll = new CKunenaPolls();
					$poll->delete_poll($mes[0]->threadid);
				}
			}
		}

		$kunena_db->setQuery ( 'SELECT userid FROM #__kunena_messages WHERE id IN (' . $cids. ')' );
		$userids = $kunena_db->loadObjectList ();
		if (KunenaError::checkDatabaseError()) return;

		$kunena_db->setQuery ( 'DELETE FROM #__kunena_messages WHERE id IN (' .$cids. ')' );
		$kunena_db->query ();
		if (KunenaError::checkDatabaseError()) return;

		$kunena_db->setQuery ( 'DELETE FROM #__kunena_messages_text WHERE mesid IN (' . $cids. ')' );
		$kunena_db->query ();
		if (KunenaError::checkDatabaseError()) return;
		foreach ( $userids as $line ) {
			if ($line->userid > 0) {
				$userid_array [] = $line->userid;
			}
		}

		$userids = implode ( ',', $userid_array );

		if (count ( $userid_array ) > 0) {
			$kunena_db->setQuery ( 'UPDATE #__kunena_users SET posts=posts-1 WHERE userid IN (' . $userids . ')' );
			$kunena_db->query ();
			if (KunenaError::checkDatabaseError()) return;
		}

		foreach ($cid as $MessageID) {
			$kunena_mod->deleteAttachments($MessageID);
		}
	}

	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=showtrashview", JText::_('COM_KUNENA_TRASH_DELETE_DONE') );
}

function trashrestore($option, $cid) {
	$kunena_app = & JFactory::getApplication ();
	$kunena_db = &JFactory::getDBO ();

	if ($cid) {
		foreach ( $cid as $id ) {
			$kunena_db->setQuery ( "SELECT * FROM #__kunena_messages WHERE id=$id AND hold=2" );
			$mes = $kunena_db->loadObject ();
			if (KunenaError::checkDatabaseError()) return;

			$kunena_db->setQuery ( "UPDATE #__kunena_messages SET hold=0 WHERE hold IN (2,3) AND thread=$mes->thread " );
			$kunena_db->query ();
			if (KunenaError::checkDatabaseError()) return;
		}
	}

	$kunena_app->redirect ( JURI::base () . "index.php?option=$option&task=showtrashview", JText::_('COM_KUNENA_TRASH_RESTORE_DONE') );
}
//===============================
// FINISH trash management
//===============================

//===============================
// Report System
//===============================
function showSystemReport ( $option ) {
	$kunena_app = & JFactory::getApplication ();
	$return = JRequest::getCmd( 'return', 'showsystemreport', 'post' );
	$report = generateSystemReport ();
	html_Kunena::showSystemReport ( $option, $report );
}

function generateSystemReport () {
	$kunena_config = KunenaFactory::getConfig ();
	$kunena_app = & JFactory::getApplication ();
	$kunena_db = &JFactory::getDBO ();
	$JVersion = new JVersion();
	$jversion = $JVersion->PRODUCT .' '. $JVersion->RELEASE .'.'. $JVersion->DEV_LEVEL .' '. $JVersion->DEV_STATUS.' [ '.$JVersion->CODENAME .' ] '. $JVersion->RELDATE;
	$JConfig = JFactory::getConfig();
	if($kunena_app->getCfg('legacy' )) {
		$jconfig_legacy = '[color=#FF0000]Enabled[/color]';
	} else {
		$jconfig_legacy = 'Disabled';
	}
	if($kunena_app->getCfg('ftp_enable' )) {
		$jconfig_ftp = 'Enabled';
	} else {
		$jconfig_ftp = 'Disabled';
	}
	if($kunena_app->getCfg('sef' )) {
		$jconfig_sef = 'Enabled';
	} else {
		$jconfig_sef = 'Disabled';
	}
	if($kunena_app->getCfg('sef_rewrite' )) {
		$jconfig_sef_rewrite = 'Enabled';
	} else {
		$jconfig_sef_rewrite = 'Disabled';
	}

	if (file_exists(JPATH_ROOT. DS. '.htaccess')) {
		$htaccess = 'Exists';
	} else {
		$htaccess = 'Missing';
	}

	if(ini_get('register_globals')) {
		$register_globals = '[u]register_globals:[/u] [color=#FF0000]On[/color]';
	} else {
		$register_globals = '[u]register_globals:[/u] Off';
	}
	if(ini_get('safe_mode')) {
		$safe_mode = '[u]safe_mode:[/u] [color=#FF0000]On[/color]';
	} else {
		$safe_mode = '[u]safe_mode:[/u] Off';
	}
	if(extension_loaded('mbstring')) {
		$mbstring = '[u]mbstring:[/u] Enabled';
	} else {
		$mbstring = '[u]mbstring:[/u] [color=#FF0000]Not installed[/color]';
	}
	if(extension_loaded('gd')) {
		$gd_info = gd_info ();
		$gd_support = '[u]GD:[/u] '.$gd_info['GD Version'] ;
	} else {
		$gd_support = '[u]GD:[/u] [color=#FF0000]Not installed[/color]';
	}
	$maxExecTime = ini_get('max_execution_time');
	$maxExecMem = ini_get('memory_limit');
	$fileuploads = ini_get('upload_max_filesize');
	$kunenaVersionInfo = CKunenaVersion::versionArray ();

	//get all the config settings for Kunena
	$kunena_db->setQuery ( "SHOW TABLES LIKE '" . $kunena_db->getPrefix () ."fb_config'" );
	$table_config = $kunena_db->loadResult ();
	if (KunenaError::checkDatabaseError()) return;

	if ($table_config) {
		$kunena_db->setQuery("SELECT * FROM #__kunena_config");
		$kconfig = $kunena_db->loadObjectList ();
    	if (KunenaError::checkDatabaseError()) return;

    	$kconfigsettings = '[table]';
    	foreach ($kconfig[0] as $key => $value ) {
    		if ($key != 'id') {
				$kconfigsettings .= '[tr][td]'.$key.'[/td][td]'.$value.'[/td][/tr]';
    		}
    	}
		$kconfigsettings .= '[/table]';
	} else {
		$kconfigsettings = 'Your configuration settings aren\'t yet recorded in the database';
	}


	//test on each table if the collation is on utf8
	$tableslist = $kunena_db->getTableList();
	$collation = '';
	foreach($tableslist as $table) {
		if (preg_match('`_fb_`',$table)) {
			$kunena_db->setQuery("SHOW FULL FIELDS FROM " .$table. "");
			$fullfields = $kunena_db->loadObjectList ();
            	if (KunenaError::checkDatabaseError()) return;

			foreach ($fullfields as $row) {
				if(!empty($row->Collation) && !preg_match('`utf8_general`',$row->Collation)) {
					$collation .= $table.' [color=#FF0000]have wrong collation of type '.$row->Collation.' [/color] on field '.$row->Field.'  ';
				}
			}
		}
	}
	if(empty($collation)) {
		$collation = 'The collation of your table fields are correct';
	}

    $report = '[confidential][b]Joomla! version:[/b] '.$jversion.' [b]Platform:[/b] '.$_SERVER['SERVER_SOFTWARE'].' ('
	    .$_SERVER['SERVER_NAME'].') [b]PHP version:[/b] '.phpversion().' | '.$safe_mode.' | '.$register_globals.' | '.$mbstring
	    .' | '.$gd_support.' | [b]MySQL version:[/b] '.$kunena_db->getVersion().'[/confidential][quote][b]Database collation check:[/b] '.$collation.'
		[/quote][quote][b]Legacy mode:[/b] '.$jconfig_legacy.' | [b]Joomla! SEF:[/b] '.$jconfig_sef.' | [b]Joomla! SEF rewrite:[/b] '
	    .$jconfig_sef_rewrite.' | [b]FTP layer:[/b] '.$jconfig_ftp.' | [b]htaccess:[/b] '.$htaccess
	    .' | [b]PHP environment:[/b] [u]Max execution time:[/u] '.$maxExecTime.' seconds | [u]Max execution memory:[/u] '
	    .$maxExecMem.' | [u]Max file upload:[/u] '.$fileuploads.' [/quote][quote]
		 [b]Kunena version detailled:[/b] [u]Installed version:[/u] '.$kunenaVersionInfo->version.' | [u]Build:[/u] '
	    .$kunenaVersionInfo->build.' | [u]Version name:[/u] '.$kunenaVersionInfo->versionname.' | [b]Kunena config settings:[/b][spoiler] '.$kconfigsettings.'[/spoiler][/quote]';

    return $report;
}

//===============================
// FINISH report system
//===============================

// Grabs gd version


function KUNENA_gdVersion() {
	// Simplified GD Version check
	if (! extension_loaded ( 'gd' )) {
		return;
	}

	if (function_exists ( 'gd_info' )) {
		$ver_info = gd_info ();
		preg_match ( '/\d/', $ver_info ['GD Version'], $match );
		$gd_ver = $match [0];
		return $match [0];
	} else {
		return;
	}
}

function kescape($string) {
	return htmlspecialchars($string, ENT_COMPAT, 'UTF-8');
}

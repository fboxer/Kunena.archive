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
 **/
//
// Dont allow direct linking
defined( '_JEXEC' ) or die('');

class KunenaProfileAlphaUserPoints extends KunenaProfile
{
	public function __construct() {
		$aup = JPATH_SITE . DS . 'components' . DS . 'com_alphauserpoints' . DS . 'helper.php';
		if (!file_exists ( $aup )) return;
		require_once ($aup);
		$this->priority = 60;
	}

	public function getUserListURL($action='', $xhtml = true)
	{
		if (method_exists('AlphaUserPointsHelper', 'getAupUsersURL'))
			return AlphaUserPointsHelper::getAupUsersURL();
		else {
			// For AUP 1.5.3 etc..
			static $AUP_itemid = false;
			if ($AUP_itemid === false) {
				$db = JFactory::getDBO();
				$query = "SELECT id FROM #__menu WHERE `link`='index.php?option=com_alphauserpoints&view=users' AND `type`='component' AND `published`='1'";
				$db->setQuery( $query );
				$AUP_itemid = intval($db->loadResult());
			}
			return JRoute::_('index.php?option=com_alphauserpoints&view=users&Itemid='.$AUP_itemid, $xhtml);
		}
	}

	public function getProfileURL($user, $task='', $xhtml = true)
	{
		if ($user == 0) return false;
		$user = KunenaFactory::getUser($user);
		$my = JFactory::getUser();
		if ($user === false) return false;
		$userid = $my->id != $user->userid ? '&userid='.AlphaUserPointsHelper::getAnyUserReferreID($user->userid) : '';
		return JRoute::_('index.php?option=com_alphauserpoints&view=account'.$userid, $xhtml);
	}

	public function getProfileView($PopUserCount=0) {
		$_db = &JFactory::getDBO ();
		$_config = KunenaFactory::getConfig ();

		$queryName = $_config->username ? "username" : "name";
		if (!$PopUserCount) $PopUserCount = $_config->popusercount;
		$query = "SELECT a.profileviews AS hits, u.id AS user_id, u.{$queryName} AS user FROM #__alpha_userpoints AS a
					INNER JOIN #__users AS u ON u.id = a.userid
					WHERE a.profileviews>'0' ORDER BY a.profileviews DESC";
		$_db->setQuery ( $query, 0, $PopUserCount );
		$topAUPProfileView = $_db->loadObjectList ();
		KunenaError::checkDatabaseError();

		return $topAUPProfileView;
	}

	public function showProfile($userid, &$msg_params) {}
}

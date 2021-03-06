<?php
/**
 * @version $Id$
 * Kunena Component
 * @package Kunena
 *
 * @Copyright (C) 2008 - 2011 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

kimport ( 'kunena.view' );
kimport ( 'kunena.html.parser' );

/**
 * Topics View
 */
class KunenaViewTopics extends KunenaView {
	function displayDefault($tpl = null) {
		$this->layout = 'default';
		$this->params = $this->state->get('params');
		$this->assignRef ( 'topics', $this->get ( 'Topics' ) );
		$this->assignRef ( 'total', $this->get ( 'Total' ) );
		$this->assignRef ( 'topic_ordering', $this->get ( 'MessageOrdering' ) );
		$this->me = KunenaFactory::getUser();
		$this->config = KunenaFactory::getConfig();

		$this->actionMove = false;
		if ($this->me->isModerator ()) {
			$this->actionMove = true;
			$this->actionDropdown[] = JHTML::_('select.option', 'none', '&nbsp;');
			$this->actionDropdown[] = JHTML::_('select.option', 'move', JText::_('COM_KUNENA_MOVE_SELECTED'));
			$this->actionDropdown[] = JHTML::_('select.option', 'delete', JText::_('COM_KUNENA_DELETE_SELECTED'));
			if($this->config->mod_see_deleted == '1' || $this->me->isAdmin()) {
				$this->actionDropdown[] = JHTML::_('select.option', 'permdelete', JText::_('COM_KUNENA_BUTTON_PERMDELETE_LONG'));
				$this->actionDropdown[] = JHTML::_('select.option', 'restore', JText::_('COM_KUNENA_BUTTON_UNDELETE_LONG'));
			}
		}

		switch ($this->state->get ( 'list.mode' )) {
			case 'topics' :
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_DEFAULT_MODE_TOPICS');
				break;
			case 'sticky' :
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_DEFAULT_MODE_STICKY');
				break;
			case 'locked' :
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_DEFAULT_MODE_LOCKED');
				break;
			case 'noreplies' :
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_DEFAULT_MODE_NOREPLIES');
				break;
			case 'unapproved' :
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_DEFAULT_MODE_UNAPPROVED');
				break;
			case 'deleted' :
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_DEFAULT_MODE_DELETED');
				break;
			case 'replies' :
			default :
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_DEFAULT_MODE_DEFAULT');
		}
		$this->title = $this->headerText;

		//meta description and keywords
		$limit = $this->state->get('list.limit');
		$page = intval($this->state->get('list.start')/$limit)+1;
		$total = intval($this->total/$limit)+1;
		$pagesTxt = "{$page}/{$total}";
		$app = JFactory::getApplication();
		$metaKeys = $this->headerText . $this->escape ( ", {$this->config->board_title}, " ) . $app->getCfg ( 'sitename' );
		$metaDesc = $this->headerText . $this->escape ( " ({$pagesTxt}) - {$this->config->board_title}" );
		$metaDesc = $this->document->get ( 'description' ) . '. ' . $metaDesc;

		$this->document->setMetadata ( 'robots', 'noindex, follow' );
		$this->document->setMetadata ( 'keywords', $metaKeys );
		$this->document->setDescription ( $metaDesc );
		$this->setTitle ( "{$this->title} ({$pagesTxt})" );

		$this->display($tpl);
	}

	function displayUser($tpl = null) {
		$this->layout = 'user';
		$this->params = $this->state->get('params');
		$this->assignRef ( 'topics', $this->get ( 'Topics' ) );
		$this->assignRef ( 'total', $this->get ( 'Total' ) );
		$this->assignRef ( 'topic_ordering', $this->get ( 'MessageOrdering' ) );
		$this->me = KunenaFactory::getUser();
		$this->config = KunenaFactory::getConfig();

		$this->actionMove = false;
		if ($this->me->isModerator ()) {
			$this->actionMove = true;
			$this->actionDropdown[] = JHTML::_('select.option', 'none', '&nbsp;');
			$this->actionDropdown[] = JHTML::_('select.option', 'move', JText::_('COM_KUNENA_MOVE_SELECTED'));
			$this->actionDropdown[] = JHTML::_('select.option', 'delete', JText::_('COM_KUNENA_DELETE_SELECTED'));
			if($this->config->mod_see_deleted == '1' || $this->me->isAdmin()) {
				$this->actionDropdown[] = JHTML::_('select.option', 'permdelete', JText::_('COM_KUNENA_BUTTON_PERMDELETE_LONG'));
				$this->actionDropdown[] = JHTML::_('select.option', 'restore', JText::_('COM_KUNENA_BUTTON_UNDELETE_LONG'));
			}
		}

		switch ($this->state->get ( 'list.mode' )) {
			case 'posted' :
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_USERS_MODE_POSTED');
				break;
			case 'started' :
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_USERS_MODE_STARTED');
				break;
			case 'favorites' :
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_USERS_MODE_FAVORITES');
				break;
			case 'subscriptions' :
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_USERS_MODE_SUBSCRIPTIONS');
				break;
			default :
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_USERS_MODE_DEFAULT');
		}
		$this->title = $this->headerText;

		//meta description and keywords
		$limit = $this->state->get('list.limit');
		$page = intval($this->state->get('list.start')/$limit)+1;
		$total = intval($this->total/$limit)+1;
		$pagesTxt = "{$page}/{$total}";
		$app = JFactory::getApplication();
		$metaKeys = $this->headerText . $this->escape ( ", {$this->config->board_title}, " ) . $app->getCfg ( 'sitename' );
		$metaDesc = $this->headerText . $this->escape ( " ({$pagesTxt}) - {$this->config->board_title}" );
		$metaDesc = $this->document->get ( 'description' ) . '. ' . $metaDesc;

		$this->document->setMetadata ( 'robots', 'noindex, follow' );
		$this->document->setMetadata ( 'keywords', $metaKeys );
		$this->document->setDescription ( $metaDesc );
		$this->setTitle ( "{$this->title} ({$pagesTxt})" );

		$this->display($tpl);
	}

	function displayPosts($tpl = null) {
		$this->layout = 'posts';
		$this->params = $this->state->get('params');
		$this->assignRef ( 'messages', $this->get ( 'Messages' ) );
		$this->assignRef ( 'topics', $this->get ( 'Topics' ) );
		$this->assignRef ( 'total', $this->get ( 'Total' ) );
		$this->assignRef ( 'topic_ordering', $this->get ( 'MessageOrdering' ) );
		$this->me = KunenaFactory::getUser();
		$this->config = KunenaFactory::getConfig();

		$this->actionMove = false;
		if ($this->me->isModerator ()) {
//			$this->actionMove = true;
//			$this->actionDropdown[] = JHTML::_('select.option', 'none', '&nbsp;');
//			$this->actionDropdown[] = JHTML::_('select.option', 'move', JText::_('COM_KUNENA_MOVE_SELECTED'));
//			$this->actionDropdown[] = JHTML::_('select.option', 'delete', JText::_('COM_KUNENA_DELETE_SELECTED'));
//			if($this->config->mod_see_deleted == '1' || $this->me->isAdmin()) {
//				$this->actionDropdown[] = JHTML::_('select.option', 'permdelete', JText::_('COM_KUNENA_BUTTON_PERMDELETE_LONG'));
//				$this->actionDropdown[] = JHTML::_('select.option', 'restore', JText::_('COM_KUNENA_BUTTON_UNDELETE_LONG'));
//			}
		}

		switch ($this->state->get ( 'list.mode' )) {
			case 'unapproved':
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_POSTS_MODE_UNAPPROVED');
				break;
			case 'deleted':
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_POSTS_MODE_DELETED');
				break;
			case 'mythanks':
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_POSTS_MODE_MYTHANKS');
				break;
			case 'thankyou':
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_POSTS_MODE_THANKYOU');
				break;
			case 'recent':
			default:
				$this->headerText =  JText::_('COM_KUNENA_VIEW_TOPICS_POSTS_MODE_DEFAULT');
		}
		$this->title = $this->headerText;

		//meta description and keywords
		$limit = $this->state->get('list.limit');
		$page = intval($this->state->get('list.start')/$limit)+1;
		$total = intval($this->total/$limit)+1;
		$pagesTxt = "{$page}/{$total}";
		$app = JFactory::getApplication();
		$metaKeys = $this->headerText . $this->escape ( ", {$this->config->board_title}, " ) . $app->getCfg ( 'sitename' );
		$metaDesc = $this->headerText . $this->escape ( " ({$pagesTxt}) - {$this->config->board_title}" );
		$metaDesc = $this->document->get ( 'description' ) . '. ' . $metaDesc;

		$this->document->setMetadata ( 'robots', 'noindex, follow' );
		$this->document->setMetadata ( 'keywords', $metaKeys );
		$this->document->setDescription ( $metaDesc );
		$this->setTitle ( "{$this->title} ({$pagesTxt})" );

		$this->display($tpl);
	}

	function displayAnnouncement() {
		if ($this->config->showannouncement > 0) {
			require_once(KUNENA_PATH_LIB .DS. 'kunena.announcement.class.php');
			$ann = new CKunenaAnnouncement();
			$ann->getAnnouncement();
			$ann->displayBox();
		}
	}

	function displaySubCategories() {
		$children = $this->category->getChildren();
		if (!empty($children)) {
			KunenaForum::display('categories', 'default', 'list');
			$this->subcategories = true;
		}
	}

	function displayRows() {
		if ($this->layout == 'posts') {
			$this->displayPostRows();
		} else {
			$this->displayTopicRows();
		}
	}

	function displayTopicRows() {
		$lasttopic = NULL;
		$this->position = 0;
		foreach ( $this->topics as $this->topic ) {
			$this->category = $this->topic->getCategory();
			$this->position++;
			$this->keywords = $this->topic->getKeywords(false, ', ');
			$this->module = $this->getModulePosition('kunena_topic_' . $this->position);
			$this->message_position = $this->topic->posts - ($this->topic->unread ? $this->topic->unread - 1 : 0);
			$this->pages = ceil ( $this->topic->posts / $this->config->messages_per_page );
			if ($this->config->avataroncat) {
				$this->topic->avatar = KunenaFactory::getUser($this->topic->last_post_userid)->getAvatarLink('klist-avatar', 'list');
			}

			if (is_object($lasttopic) && $lasttopic->ordering != $this->topic->ordering) {
				$this->spacing = 1;
			} else {
				$this->spacing = 0;
			}
			echo $this->loadTemplate('row');
			$lasttopic = $this->topic;
		}
	}

	function displayPostRows() {
		$lasttopic = NULL;
		$this->position = 0;
		foreach ( $this->messages as $this->message ) {
			if (!isset($this->topics[$this->message->thread])) {
				// TODO: INTERNAL ERROR
				return;
			}
			$this->topic = $this->topics[$this->message->thread];
			$this->category = $this->topic->getCategory();
			$this->position++;
			$this->module = $this->getModulePosition('kunena_topic_' . $this->position);
			$this->message_position = $this->topic->posts - ($this->topic->unread ? $this->topic->unread - 1 : 0);
			$this->pages = ceil ( $this->topic->posts / $this->config->messages_per_page );
			if ($this->config->avataroncat) {
				$this->topic->avatar = KunenaFactory::getUser($this->message->userid)->getAvatarLink('klist-avatar', 'list');
			}
			$this->spacing = 0;
			echo $this->loadTemplate('row');
		}
	}

	function getTopicClass($prefix='k', $class='topic') {
		$class = $prefix . $class;
		$txt = $class . (($this->position & 1) + 1);
		if ($this->topic->ordering) {
			$txt .= '-stickymsg';
		}
		if ($this->topic->getCategory()->class_sfx) {
			$txt .= ' ' . $class . (($this->position & 1) + 1);
			if ($this->topic->ordering) {
				$txt .= '-stickymsg';
			}
			$txt .= $this->escape($this->topic->getCategory()->class_sfx);
		}
		if ($this->topic->hold == 1) $txt .= ' '.$prefix.'unapproved';
		else if ($this->topic->hold) $txt .= ' '.$prefix.'deleted';
		return $txt;
	}

	function getPagination($func, $maxpages) {
		$limit = $this->state->get ( 'list.limit' );
		$page = floor ( $this->state->get ( 'list.start' ) / $limit ) + 1;
		$totalpages = max(1, floor ( ($this->total-1) / $limit ) + 1);

		if ( $func != 'latest' ) $func = 'latest&do='.$func;

		$startpage = ($page - floor ( $maxpages / 2 ) < 1) ? 1 : $page - floor ( $maxpages / 2 );
		$endpage = $startpage + $maxpages;
		if ($endpage > $totalpages) {
			$startpage = ($totalpages - $maxpages) < 1 ? 1 : $totalpages - $maxpages;
			$endpage = $totalpages;
		}

		$output = '<ul class="kpagination">';
		$output .= '<li class="page">' . JText::_('COM_KUNENA_PAGE') . '</li>';

		if (($startpage) > 1) {
			if ($endpage < $totalpages)
				$endpage --;
			$output .= '<li>' . CKunenaLink::GetLatestPageLink ( $func, 1, 'follow', '' ) . '</li>';
			if (($startpage) > 2) {
				$output .= '<li class="more">...</li>';
			}
		}

		for($i = $startpage; $i <= $endpage && $i <= $totalpages; $i ++) {
			if ($page == $i) {
				$output .= '<li class="active">' . $i . '</li>';
			} else {
				$output .= '<li>' . CKunenaLink::GetLatestPageLink ( $func, $i, 'follow', '' ) . '</li>';
			}
		}

		if ($endpage < $totalpages) {
			if ($endpage < $totalpages - 1) {
				$output .= '<li class="more">...</li>';
			}

			$output .= '<li>' . CKunenaLink::GetLatestPageLink ( $func, $totalpages, 'follow', '' ) . '</li>';
		}

		$output .= '</ul>';
		return $output;
	}
}
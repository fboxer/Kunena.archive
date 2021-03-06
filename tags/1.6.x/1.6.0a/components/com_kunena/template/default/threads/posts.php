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

// Dont allow direct linking
defined( '_JEXEC' ) or die();

global $kunena_icons;

// url of current page that user will be returned to after bulk operation
$kuri = JURI::getInstance ();
$Breturn = $kuri->toString ( array ('path', 'query', 'fragment' ) );
$this->app->setUserState( "com_kunena.ActionBulk", JRoute::_( $Breturn ) );

	?>
<div class="k_bt_cvr1">
<div class="k_bt_cvr2">
<div class="k_bt_cvr3">
<div class="k_bt_cvr4">
<div class="k_bt_cvr5">
<form action="index.php" method="post" name="kBulkActionForm">

<table
	class="<?php
	echo isset ( $this->objCatInfo->class_sfx ) ? ' kblocktable' . $this->objCatInfo->class_sfx : '';
	?>" id="kflattable">
	<thead>
		<tr>
			<th
				colspan="<?php
	echo $this->columns;
	?>">

		<div class="ktitle-cover km"><span class="ktitle kl">

	<?php if (!empty($this->header)) echo $this->header; ?></span></div>
	<?php if (CKunenaTools::isModerator($this->my->id)) { ?>
	<div class="kcheckbox select-toggle">
		<input id="kcbcheckall" type="checkbox" name="toggle" value="" />
	</div>
	<?php } ?>
		</th>
		</tr>
	</thead>
	<tbody>
		<?php
	$k = 0;
	$counter = 0;
	if (!count ( $this->customreply )) {
		echo '<tr class="ksectiontableentry2"><td class="td-0 km center">' . ($this->func=='showcat'?JText::_('COM_KUNENA_VIEW_NO_POSTS'):JText::_('COM_KUNENA_NO_POSTS')) . '</td></tr>';
	} else
	foreach ( $this->customreply as $message ) {
		$lastreply = $this->lastreply[$message->thread];
		$firstpost = $this->threads[$message->thread];

		$message->name = kunena_htmlspecialchars ( $message->name );
		$message->email = kunena_htmlspecialchars ( $message->email );
?>
		<tr
			class="k<?php
		echo $this->tabclass [$k^=1];
		if ($firstpost->ordering != 0) {
			echo '-stickymsg';
		}

		if ($firstpost->class_sfx) {
			echo ' k' . $this->tabclass [$k^1];
			if ($firstpost->ordering != 0) {
				echo '-stickymsg';
			}
			echo $firstpost->class_sfx;
		}
		if ($message->hold == 1) echo ' kunapproved';
		else if ($message->hold) echo ' kdeleted';
		?>">
			<td class="td-0 center">
			<?php echo CKunenaTools::topicIcon($message) ?>
		</td>

			<td class="td-3"><?php
			$curMessageNo = $message->msgcount - ($message->unread ? $message->unread - 1 : 0);
			$threadPages = ceil ( $message->msgcount / $this->config->messages_per_page );
			$unreadPage = ceil ( $curMessageNo / $this->config->messages_per_page );

			if ($message->attachments) {
				echo isset ( $kunena_icons ['topicattach'] ) ? '<img  class="attachicon" src="' . KUNENA_URLICONSPATH . $kunena_icons ['topicattach'] . '" border="0" alt="' . JText::_('COM_KUNENA_ATTACH') . '" />' : '<img class="attachicon" src="' . KUNENA_URLICONSPATH . 'attachment.gif"  alt="' . JText::_('COM_KUNENA_ATTACH') . '" title="' . JText::_('COM_KUNENA_ATTACH') . '" />';
			}
			?>
			<div class="ktopic-title-cover"><?php
			echo CKunenaLink::GetThreadLink ( 'view', $message->catid, $message->id, KunenaParser::parseText ($message->subject), KunenaParser::stripBBCode ($message->message), 'follow', 'ktopic-title km' );
			?>
			</div><div style="display:none"><?php echo KunenaParser::parseBBCode ($message->message);?></div>
			</td>
			<td class="td-3"><?php
			echo CKunenaLink::GetThreadLink ( 'view', $firstpost->catid, $firstpost->id, KunenaParser::parseText ($firstpost->subject), KunenaParser::stripBBCode ($firstpost->message), 'follow', 'ktopic-title km' );
			?>
			<?php
			if ($message->favcount ) {
				if ($message->myfavorite) {
					echo isset ( $kunena_icons ['favoritestar'] ) ? '<img  class="favoritestar" src="' . KUNENA_URLICONSPATH . $kunena_icons ['favoritestar'] . '" border="0" alt="' . JText::_('COM_KUNENA_FAVORITE') . '" />' : '<img class="favoritestar" src="' . KUNENA_URLICONSPATH . 'favoritestar.png"  alt="' . JText::_('COM_KUNENA_FAVORITE') . '" title="' . JText::_('COM_KUNENA_FAVORITE') . '" />';
				} else if (array_key_exists ( 'favoritestar_grey', $kunena_icons )) {
					echo isset ( $kunena_icons ['favoritestar_grey'] ) ? '<img  class="favoritestar" src="' . KUNENA_URLICONSPATH . $kunena_icons ['favoritestar_grey'] . '" border="0" alt="' . JText::_('COM_KUNENA_FAVORITE') . '" />' : '<img class="favoritestar" src="' . KUNENA_URLICONSPATH . 'favoritestar_grey.png"  alt="' . JText::_('COM_KUNENA_FAVORITE') . '" title="' . JText::_('COM_KUNENA_FAVORITE') . '" />';
				}
			}
			?>
			<?php
			if ($message->unread) {
					echo CKunenaLink::GetThreadPageLink ( 'view', $message->catid, $message->id, $unreadPage, $this->config->messages_per_page, '<sup class="knewchar">&nbsp;(' . $message->unread . ' ' . $this->config->newchar . ')</sup>', $message->lastread );
			}

		if ($message->locked != 0) {
			?> <!-- Locked --> <span class="ktopic-locked"> <?php
			echo isset ( $kunena_icons ['topiclocked'] ) ? '<img src="' . KUNENA_URLICONSPATH . $kunena_icons ['topiclocked'] . '" border="0" alt="' . JText::_('COM_KUNENA_GEN_LOCKED_TOPIC') . '" />' : '<img src="' . KUNENA_URLEMOTIONSPATH . 'lock.gif"  alt="' . JText::_('COM_KUNENA_GEN_LOCKED_TOPIC') . '" title="' . JText::_('COM_KUNENA_GEN_LOCKED_TOPIC') . '" />';
			?>
			</span> <!-- /Locked --> <?php
		}
		?>
			<div class="ks">
			<!-- Category --> <span class="ktopic-category"> <?php
			echo JText::_('COM_KUNENA_CATEGORY') . ' ' . CKunenaLink::GetCategoryLink ( 'showcat', $message->catid, kunena_htmlspecialchars ( $message->catname ) );
			?>
			</span> <!-- /Category -->
		</div>
		</td>
			<td class="td-5 ks">
			<div class="klatest-post-info"><!--  Sticky   --> <?php
		if ($this->messages[$message->id]->ordering != 0) {
			?>
			<span class="ktopic-sticky"> <?php
			echo isset ( $kunena_icons ['topicsticky'] ) ? '<img  src="' . KUNENA_URLICONSPATH . $kunena_icons ['topicsticky'] . '" border="0" alt="' . JText::_('COM_KUNENA_GEN_ISSTICKY') . '" />' : '<img class="stickyicon" src="' . KUNENA_URLICONSPATH . 'tsticky.gif"  alt="' . JText::_('COM_KUNENA_GEN_ISSTICKY') . '" title="' . JText::_('COM_KUNENA_GEN_ISSTICKY') . '" />';
			?>
			</span> <?php
		}
		?> <!--  /Sticky   --> <!-- Avatar --> <?php
		if ($this->config->avataroncat > 0) :
			$profile = KunenaFactory::getUser((int)$this->messages[$message->id]->userid);
			$useravatar = $profile->getAvatarLink('klist_avatar', 'lastpost');
			if ($useravatar) :
			?>
			<span class="ktopic-latest-post-avatar"> <?php
			echo CKunenaLink::GetProfileLink ( $this->messages[$message->id]->userid, $useravatar );
			?>
			</span> <?php
			endif;
		endif;
		?> <!-- /Avatar -->

			<!-- By -->
			<span class="ktopic-posted-time" title="<?php echo CKunenaTimeformat::showDate($message->time, 'config_post_dateformat_hover'); ?>"><?php
		echo JText::_('COM_KUNENA_POSTED_AT')?>
			<?php
			echo CKunenaTimeformat::showDate($message->time, 'config_post_dateformat');
		?>&nbsp;</span>

		<?php
		if ($message->name) {
			echo '<br /><span class="ktopic-by">';
			echo JText::_('COM_KUNENA_GEN_BY') . ' ' . CKunenaLink::GetProfileLink ( $message->userid, $message->name );
			echo '</span>';
		}
		?>
			<!-- /By -->
			</div>
			</td>

			<?php
		if (CKunenaTools::isModerator ( $this->my->id, $this->catid )) {
			?>

			<td class="td-7 center">
				<input class ="kDelete_bulkcheckboxes" type="checkbox" name="cb[<?php echo $message->id?>]" value="0"  />
			</td>

			<?php
		}
		?>
		</tr>

		<?php
	}
	if ( CKunenaTools::isModerator ( $this->my->id, $this->catid ) ) {
		?>
		<!-- Moderator Bulk Actions -->
		<tr class="ksectiontableentry1">
			<td colspan="7" align="right" class="td-1 ks">
				<select name="do" id="kBulkChooseActions"
				class="inputbox ks">
				<option value="">&nbsp;</option>
				<option value="bulkDel"><?php
		echo JText::_('COM_KUNENA_DELETE_SELECTED');
		?></option>
				<option value="bulkMove"><?php
		echo JText::_('COM_KUNENA_MOVE_SELECTED');
		?></option>
			</select> <?php
		CKunenaTools::showBulkActionCats ();
		?> <input type="submit" name="kBulkActionsGo" class="kbutton ks"
				value="<?php
		echo JText::_('COM_KUNENA_GO');
		?>" /></td>
		</tr>
		<!-- /Moderator Bulk Actions -->
		<?php
	}
	?>
	</tbody>
</table>

<input type="hidden" name="option" value="com_kunena" /> <input
	type="hidden" name="func" value="bulkactions" /> </form>
</div>
</div>
</div>
</div>
</div>

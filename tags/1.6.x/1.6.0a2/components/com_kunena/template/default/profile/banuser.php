<?php
/**
 * @version $Id: editavatar.php 2406 2010-05-04 06:16:56Z mahagr $
 * Kunena Component
 * @package Kunena
 *
 * @Copyright (C) 2008 - 2010 Kunena Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.com
 *
 **/
defined( '_JEXEC' ) or die();
$i=0;
JHTML::_('behavior.calendar');
?>

<h2><?php echo $this->banInfo->id ? JText::_('COM_KUNENA_BAN_EDIT') : JText::_('COM_KUNENA_BAN_NEW' ); ?></h2>
<form id="kform-ban" name="kformban" action="index.php" method="post">
<table class="<?php echo isset ( $this->objCatInfo->class_sfx ) ? ' kblocktable' . $this->objCatInfo->class_sfx : ''; ?> kblock">
	<tbody>
	<tr class="ksectiontableentry<?php echo ($i^=1)+1;?>">
		<td class="kcol-addban-left"><b><?php echo JText::_('COM_KUNENA_BAN_USERNAME'); ?></b></td>
		<td class="kcol-addban-right"><?php echo $this->escape($this->profile->username); ?> </td>
	</tr>
	<tr class="ksectiontableentry<?php echo ($i^=1)+1;?>">
		<td class="kcol-addban-left"><b><?php echo JText::_('COM_KUNENA_BAN_USERID'); ?></b></td>
		<td class="kcol-addban-right"> <?php echo $this->escape($this->profile->userid); ?> </td>
	</tr>
	<tr class="ksectiontableentry<?php echo ($i^=1)+1;?>">
		<td class="kcol-addban-left" ><b><?php echo JText::_('COM_KUNENA_BAN_BANLEVEL'); ?></b></td>
		<td class="kcol-addban-right"><?php
					// make the select list for the view type
					$block[] = JHTML::_('select.option', 0, JText::_('COM_KUNENA_BAN_BANLEVEL_KUNENA'));
					$block[] = JHTML::_('select.option', 1, JText::_('COM_KUNENA_BAN_BANLEVEL_JOOMLA'));
					// build the html select list
					echo JHTML::_('select.genericlist', $block, 'block', 'class="inputbox" size="1"', 'value', 'text', $this->baninfo->blocked);
					?></td>
	</tr>
	<tr class="ksectiontableentry<?php echo ($i^=1)+1;?>">
		<td class="kcol-addban-left"><b><?php echo JText::_('COM_KUNENA_BAN_EXPIRETIME'); ?></b><br />
			<span class="ks"><?php echo JText::_('COM_KUNENA_BAN_STARTEXPIRETIME_DESC'); ?></span>
		</td>
		<td class="kcol-addban-right">
			<input class="inputbox" type="text" maxlength="15" name="expiration" id="expiration" value="<?php echo $this->baninfo->expiration ?>" />
				<img src="templates/system/images/calendar.png" alt="Calendar" onclick="showCalendar('expiration','%Y-%m-%d');$('expiration').removeProperty('style');" />
		</td>
	</tr>
	<tr class="ksectiontableentry<?php echo ($i^=1)+1;?>">
		<td class="kcol-addban-left"><b><?php echo JText::_('COM_KUNENA_BAN_PUBLICREASON'); ?></b><br />
			<span class="ks"><?php echo JText::_('COM_KUNENA_BAN_PUBLICREASON_DESC'); ?></span>
		</td>
		<td class="kcol-addban-right">
			<textarea class=" required" name="reason_public" id="reason_public" ><?php echo $this->escape($this->baninfo->reason_public) ?></textarea>
		</td>
	</tr>
	<tr class="ksectiontableentry<?php echo ($i^=1)+1;?>">
		<td class="kcol-addban-left"><b><?php echo JText::_('COM_KUNENA_BAN_PRIVATEREASON'); ?></b><br />
			<span class="ks"><?php echo JText::_('COM_KUNENA_BAN_PRIVATEREASON_DESC'); ?></span>
		</td>
		<td class="kcol-addban-right">
			<textarea class="required" name="reason_private" id="reason_private"><?php echo $this->escape($this->baninfo->reason_private) ?></textarea>
		</td>
	</tr>
		<tr class="ksectiontableentry<?php echo ($i^=1)+1;?>">
		<td class="kcol-addban-left"><b><?php echo JText::_('COM_KUNENA_BAN_ADDCOMMENT'); ?></b><br />
			<span class="ks"><?php echo JText::_('COM_KUNENA_BAN_ADDCOMMENT_DESC'); ?></span>
		</td>
		<td class="kcol-addban-right">
			<textarea class="required" name="comment" id="comment"></textarea>
		</td>
	</tr>
	<?php if($this->banInfo->id): ?>
	<tr class="ksectiontableentry<?php echo ($i^=1)+1;?>">
		<td class="kcol-addban-left"><b><?php echo JText::_('COM_KUNENA_MODERATE_REMOVE_BAN'); ?></b></td>
		<td class="kcol-addban-right"><input type="checkbox" id="ban-delban" name="delban" value="delban" class="" /></td>
	</tr>
	<?php endif; ?>
	<tr class="ksectiontableentry<?php echo ($i^=1)+1;?>">
		<td class="kcol-addban-left"><b><?php echo JText::_('COM_KUNENA_MODERATE_DELETE_BAD_SIGNATURE'); ?></b></td>
		<td class="kcol-addban-right"><input type="checkbox" id="ban-delsignature" name="delsignature" value="delsignature" class="" /></td>
	</tr>
	<tr class="ksectiontableentry<?php echo ($i^=1)+1;?>">
		<td class="kcol-addban-left"><b><?php echo JText::_('COM_KUNENA_MODERATE_DELETE_BAD_AVATAR'); ?></b></td>
		<td class="kcol-addban-right"><input type="checkbox" id="ban-delavatar" name="delavatar" value="delavatar" /></td>
	</tr>
	<tr class="ksectiontableentry<?php echo ($i^=1)+1;?>">
		<td class="kcol-addban-left"><b><?php echo JText::_('COM_KUNENA_MODERATE_DELETE_BAD_PROFILEINFO'); ?></b></td>
		<td class="kcol-addban-right"><input type="checkbox" id="ban-delprofileinfo" name="delprofileinfo" value="delprofileinfo" /></td>
	</tr>
	<tr class="ksectiontableentry<?php echo ($i^=1)+1;?>">
		<td class="kcol-addban-left"><b><?php echo JText::_('COM_KUNENA_MODERATE_DELETE_ALL_POSTS'); ?></b></td>
		<td class="kcol-addban-right"><input type="checkbox" id="ban-delposts" name="bandelposts" value="bandelposts" /></td>
	</tr>
	<tr class="ksectiontableentry<?php echo ($i^=1)+1;?>">
		<td class="kcol-addban-center" style="text-align:center;" width="100%" colspan="2">
			<input class="kbutton kbutton ks" type="submit" value="<?php echo $this->banInfo->id ? JText::_('COM_KUNENA_BAN_EDIT') : JText::_('COM_KUNENA_BAN_NEW' ); ?>" name="Submit" />
			<input type="hidden" name="option" value="com_kunena" />
			<input type="hidden" name="func" value="profile" />
			<input type="hidden" name="do" value="ban" />
			<input type="hidden" name="userid" value="<?php echo $this->escape($this->profile->userid); ?>" />
		</td>
	</tr>
</tbody>
</table>
</form>

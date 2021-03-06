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
?>
<!-- Pathway -->
<?php $this->displayPathway () ?>
<!-- / Pathway -->

<!-- Subcategories -->
<?php $this->displaySubCategories () ?>
<!-- / Subcategories -->

<?php if ($this->category->headerdesc) : ?>
<div class="kblock">
	<div class="kheader">
		<span class="ktoggler"><a class="ktoggler close" title="<?php echo JText::_('COM_KUNENA_TOGGLER_COLLAPSE') ?>" rel="frontstats_tbody"></a></span>
		<h2><span><?php echo JText::_('COM_KUNENA_FORUM_HEADER'); ?></span></h2>
	</div>
	<div class="kcontainer" id="frontstats_tbody">
		<div class="kbody">
			<div class="kfheadercontent">
				<?php echo KunenaHtmlParser::parseBBCode ( $this->category->headerdesc ); ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<!-- B: List Actions -->
<table class="klist-actions">
	<tr>
		<td class="klist-actions-goto">
			<a name="forumtop"> </a>
			<?php echo CKunenaLink::GetSamePageAnkerLink ( 'forumbottom', $this->getIcon ( 'kforumbottom', JText::_('COM_KUNENA_GEN_GOTOBOTTOM') ), 'nofollow', 'kbuttongoto') ?>
		</td>
		<?php if (!empty ( $this->newTopicHtml ) || !empty ( $this->markReadHtml ) || !empty ( $this->subscribeCatHtml )) : ?>
		<td class="klist-actions-forum">
			<div class="kmessage-buttons-row"><?php echo $this->newTopicHtml .' ' . $this->markReadHtml . ' ' . $this->subscribeCatHtml; ?></div>
		</td>
		<?php endif; ?>
		<td class="klist-pages-all"><?php echo $this->getPagination (7); // odd number here (# - 2) ?></td>
	</tr>
</table>
<!-- F: List Actions -->

<form action="<?php echo KunenaRoute::_('index.php?option=com_kunena') ?>" method="post" name="ktopicsform">
	<input type="hidden" name="view" value="topics" />
	<?php echo JHTML::_( 'form.token' ); ?>
<div class="kblock kflat">
	<div class="kheader">
		<?php if (!empty($this->actionDropdown)) : ?>
		<span class="kcheckbox select-toggle"><input class="kcheckall" type="checkbox" name="toggle" value="" /></span>
		<?php endif; ?>
		<h2><span><?php echo $this->escape($this->headerText); ?></span></h2>
	</div>
	<div class="kcontainer">
		<div class="kbody">
				<table class="kblocktable<?php echo $this->escape($this->category->class_sfx); ?>" id="kflattable">

					<?php if (empty ( $this->topics ) && empty ( $this->subcategories )) : ?>
					<tr class="krow2"><td class="kcol-first"><?php echo JText::_('COM_KUNENA_VIEW_NO_POSTS') ?></td></tr>

					<?php else : ?>
						<?php $this->displayRows (); ?>

					<?php  if ( !empty($this->actionDropdown) || !empty($this->embedded) ) : ?>
					<!-- Bulk Actions -->
					<tr class="krow1">
						<td colspan="<?php echo empty($this->actionDropdown) ? 5 : 6 ?>" class="kcol-first krowmoderation">
							<?php if (!empty($this->embedded)) echo CKunenaLink::GetShowLatestLink(JText::_('COM_KUNENA_MORE'), $this->func , 'follow'); ?>
							<?php if (!empty($this->actionDropdown)) : ?>
							<?php echo JHTML::_('select.genericlist', $this->actionDropdown, 'task', 'class="inputbox kchecktask" size="1"', 'value', 'text', 0, 'kchecktask'); ?>
							<?php if ($this->actionMove) :
								$options = array (JHTML::_ ( 'select.option', '0', "&nbsp;" ));
								echo JHTML::_('kunenaforum.categorylist', 'target', 0, $options, array(), 'class="inputbox fbs" size="1" disabled="disabled"', 'value', 'text', 0, 'kchecktarget');
								endif;?>
							<input type="submit" name="kcheckgo" class="kbutton" value="<?php echo JText::_('COM_KUNENA_GO') ?>" />
							<?php endif; ?>
						</td>
					</tr>
					<!-- /Bulk Actions -->
					<?php endif; ?>
					<?php endif; ?>
				</table>
		</div>
	</div>
</div>
</form>

<!-- B: List Actions Bottom -->
<table class="klist-actions-bottom" >
	<tr>
		<td class="klist-actions-goto">
			<a name="forumbottom"> </a>
			<?php echo CKunenaLink::GetSamePageAnkerLink ( 'forumtop', $this->getIcon ( 'kforumtop', JText::_('COM_KUNENA_GEN_GOTOBOTTOM') ), 'nofollow', 'kbuttongoto') ?>
		</td>
		<?php if (!empty ( $this->newTopicHtml ) || !empty ( $this->markReadHtml ) || !empty ( $this->subscribeCatHtml )) : ?>
		<td class="klist-actions-forum">
			<div class="kmessage-buttons-row"><?php echo $this->newTopicHtml .' ' . $this->markReadHtml . ' ' . $this->subscribeCatHtml; ?></div>
		</td>
		<?php endif; ?>
		<td class="klist-pages-all"><?php echo $this->getPagination (7); // odd number here (# - 2) ?></td>
	</tr>
</table>

<!-- Pathway -->
<?php $this->displayPathway () ?>
<!-- / Pathway -->

<div class="kcontainer klist-bottom">
	<div class="kbody">
		<div class="kmoderatorslist-jump fltrt"><?php $this->displayForumJump (); ?></div>
		<?php if (!empty ( $this->moderators ) ) : ?>
		<div class="klist-moderators">
			<?php
			echo '' . JText::_('COM_KUNENA_GEN_MODERATORS') . ": ";
			foreach ( $this->moderators as $userid ) {
				echo CKunenaLink::GetProfileLink ( $userid ) . '&nbsp; ';
			}
			?>
		</div>
		<?php endif; ?>
	</div>
</div>
<!-- F: List Actions Bottom -->
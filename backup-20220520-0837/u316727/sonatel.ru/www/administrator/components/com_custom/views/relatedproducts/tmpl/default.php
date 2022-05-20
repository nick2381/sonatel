<?php
//JFilterOutput::objectHTMLSafe($this->item, ENT_QUOTES);
//$editor = JFactory::getEditor();
?>
<style>
	.tDiffDescription {
		width: 96%;
		height: 80px;
	}

	.bSaveProduct,
	.bDelProduct {
		width: 98%;
		padding-top: 0px !important;
		margin-bottom: 6px;
	}
</style>

<form action="#" method="post" name="adminForm" id="adminForm">
	<table class="adminlist">
		<thead>
			<tr>
				<th>
					Похожие товары
				</th>
				<th width="5%">
					Действие
				</th>
				<th width="1%" class="nowrap">
					ID
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->items as $i => $item) { ?>
				<tr class="row<?= $i % 2 ?>">
					<td>
						<b><?= $this->escape($item->title) ?></b><br>
						<textarea class="tDiffDescription"
											id="tDiffDesc<?= $i ?>"
											name="diff_description[<?= (int) $item->product_id ?>]"><?= $item->diff_description ?></textarea>
						<br>
					</td>
					<td>
						<input class="bSaveProduct" type="submit"
									 name="save[<?= (int) $item->product_id ?>]" value="Сохранить">
						<br>
						<input class="bDelProduct" type="submit"
									 name="del[<?= (int) $item->product_id ?>]" value="Удалить">
					</td>
					<td class="center">
						<?= (int) $item->product_id ?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="linked_id" value="<?= $this->linkedId ?>" />
		<input type="hidden" name="task" value="edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<script src="<?= '/media/editors/ckeditor/ckeditor.js' ?>"></script>
<script>
		jQuery(function($) {

			// init editors with basic toolbar
			$('.tDiffDescription').each(function(i, n) {
				CKEDITOR.replace(this.id, {
					height: 100,
					toolbarGroups: [
						{name: 'forms'},
						{name: 'tools'},
						{name: 'document', groups: ['mode', 'document', 'doctools']},
						{name: 'others'},
						{name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
						{name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align']},
						{name: 'styles'},
						{name: 'colors'}
					]
				});
			});
		});
</script>

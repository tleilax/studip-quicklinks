<form action="<?= $controller->url_for('links/edit', @$link['link_id']) ?>" method="post">
	<fieldset>
		<legend><?= @$link ? 'Link bearbeiten' : 'Neuen Link eintagen' ?></legend>

		<div class="type-text">
			<label for="link">URL</label>
			<input type="text" name="link" id="link" value="<?= Request::get('link', @$link['link']) ?>">
		</div>

		<div class="type-text">
			<label for="title">Titel</label>
			<input type="text" name="title" id="title" value="<?= Request::get('title', @$link['title']) ?>">
		</div>

		<div class="type-button">
			<?= makebutton('speichern', 'input', null, 'store') ?>
			<a href="<?= $controller->url_for('links/index') ?>">
				<?= makebutton('abbrechen', 'img') ?>
			</a>
		</div>
	</fieldset>
</form>
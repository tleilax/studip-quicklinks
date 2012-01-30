<? use Studip\Button, Studip\LinkButton; ?>

<form action="<?= $controller->url_for('links/edit', @$link['link_id']) ?>" method="post">
	<fieldset>
		<legend><?= @$link ? 'Link bearbeiten' : 'Neuen Link eintragen' ?></legend>

		<div class="type-text">
			<label for="link"><?= _('URL') ?></label>
			<input type="text" name="link" id="link" value="<?= Request::get('link', @$link['link']) ?>">
		</div>

		<div class="type-text">
			<label for="title"><?= _('Titel') ?></label>
			<input type="text" name="title" id="title" value="<?= Request::get('title', @$link['title']) ?>">
		</div>

		<div class="type-button">
			<?= Button::createAccept('speichern', 'store') ?>
			<?= LinkButton::createCancel('abbrechen', $controller->url_for('links/index')) ?>
		</div>
	</fieldset>
</form>

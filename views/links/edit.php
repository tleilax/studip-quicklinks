<? use Studip\Button, Studip\LinkButton; ?>

<form action="<?= $controller->url_for('links/edit', @$link['link_id']) ?>" method="post" class="studip_form">
    <?= CSRFProtection::tokenTag() ?>
    
    <fieldset>
        <legend><?= @$link ? _('Link bearbeiten') : _('Neuen Link eintragen') ?></legend>

        <fieldset>
            <label>
                <?= _('Titel') ?>
                <input type="text" name="title" value="<?= Request::get('title', @$link['title']) ?>">
            </label>
        </fieldset>

        <fieldset>
            <label>
                <?= _('URL') ?>
                <input type="text" name="link" value="<?= Request::get('link', @$link['link']) ?>">
            </label>
        </fieldset>

        <div class="type-button">
            <?= Button::createAccept(_('Speichern'), 'store') ?>
            <?= LinkButton::createCancel(_('Abbrechen'), $controller->url_for('links')) ?>
        </div>
    </fieldset>
</form>

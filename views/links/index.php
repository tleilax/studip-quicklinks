<form action="<?= $controller->url_for('links/bulk') ?>" method="post">
<table class="default zebra-hover quicklinks">
    <colgroup>
        <col width="20">
        <col>
        <col width="100">
    </colgroup>
    <thead>
        <tr>
            <th>
                <input type="checkbox" name="ids[]" value="all" data-proxyfor="table.quicklinks tbody :checkbox">
            </th>
            <th><?= _('Link') ?></th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
    <? foreach ($links as $index => $link): ?>
        <tr>
            <td>
                <input type="checkbox" name="ids[]" value="<?= $link['link_id'] ?>">
            </td>
            <td>
                <a href="<?= $link['link'] ?>"><?= htmlReady($link['title']) ?></a>
            </td>
            <td style="text-align: right;">
            <? if ($index): ?>
                <a href="<?= $controller->url_for('links/move', $link['link_id'], 'up') ?>">
                    <?= Assets::img('icons/16/yellow/arr_2up.png', array(
                        'title' => _('Nach oben bewegen'),
                        'alt'   => _('höher'),
                    )) ?>
                </a>
            <? else: ?>
                <?= Assets::img('icons/16/grey/arr_2up.png', array(
                    'title' => _('Nach oben bewegen'),
                    'alt'   => _('höher'),
                )) ?>
            <? endif; ?>
            <? if ($index + 1 < count($links)): ?>
                <a href="<?= $controller->url_for('links/move', $link['link_id'], 'down') ?>">
                    <?= Assets::img('icons/16/yellow/arr_2down.png', array(
                        'title' => _('Nach unten bewegen'),
                        'alt'   => _('niedriger'),
                    )) ?>
                </a>
            <? else: ?>
                <?= Assets::img('icons/16/grey/arr_2down.png', array(
                    'title' => _('Nach unten bewegen'),
                    'alt'   => _('niedriger'),
                )) ?>
            <? endif; ?>
                <a data-behaviour="modal" href="<?= $controller->url_for('links/edit', $link['link_id']) ?>">
                    <?= Assets::img('icons/16/blue/edit.png') ?>
                </a>
                <a href="<?= $controller->url_for('links/delete', $link['link_id']) ?>" onclick="return confirm('<?= _('Wirklich?') ?>')">
                    <?= Assets::img('icons/16/blue/trash.png') ?>
                </a>
            </td>
        </tr>
    <? endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="printhead">
                <select name="action">
                    <option value="">- <?= _('Bitte Aktion auswählen') ?></option>
                    <option value="delete"><?= _('Markierte Einträge löschen') ?></option>
                </select>
                <?= Studip\Button::createAccept(_('Ausführen'), 'bulk') ?>
            </td>
        </tr>
    </tfoot>
</table>
</form>

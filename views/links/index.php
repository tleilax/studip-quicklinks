<table class="default zebra-hover quicklinks">
    <thead>
        <tr>
            <th><?= _('Link') ?></th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
    <? foreach ($links as $index => $link): ?>
        <tr>
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
</table>

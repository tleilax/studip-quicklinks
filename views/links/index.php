<table class="quicklinks default">
	<thead>
		<tr>
			<th><?= _('Link') ?></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($links as $index => $link): ?>
		<tr class="<?= TextHelper::cycle('steel1', 'steelgraulight') ?>">
			<td>
				<a href="<?= $link['link'] ?>"><?= $link['title'] ?></a>
			</td>
			<td style="text-align: right;">
			<?php if ($index): ?>
				<a href="<?= $controller->url_for('links/move', $link['link_id'], 'up') ?>">
					<?= Assets::img('icons/16/yellow/arr_2up.png', array(
						'title' => _('Nach oben bewegen'),
						'alt'   => 'höher',
					)) ?>
				</a>
			<?php else: ?>
				<?= Assets::img('icons/16/grey/arr_2up.png', array(
					'title' => _('Nach oben bewegen'),
					'alt'   => 'höher',
				)) ?>
			<?php endif; ?>
			<?php if ($index + 1 < count($links)): ?>
				<a href="<?= $controller->url_for('links/move', $link['link_id'], 'down') ?>">
					<?= Assets::img('icons/16/yellow/arr_2down.png', array(
						'title' => _('Nach unten bewegen'),
						'alt'   => 'niedriger',
					)) ?>
				</a>
			<?php else: ?>
				<?= Assets::img('icons/16/grey/arr_2down.png', array(
					'title' => _('Nach unten bewegen'),
					'alt'   => 'niedriger',
				)) ?>
			<?php endif; ?>
    			<a data-behaviour="modal" href="<?= $controller->url_for('links/edit', $link['link_id']) ?>">
    				<?= Assets::img('icons/16/blue/edit.png') ?>
    			</a>
				<a href="<?= $controller->url_for('links/delete', $link['link_id']) ?>" onclick="return confirm('Wirklich?')">
					<?= Assets::img('icons/16/blue/trash.png') ?>
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

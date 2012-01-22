<?= $this->render_partial('links/edit') ?>

<table class="default">
	<thead>
		<tr>
			<th>Link</th>
			<th>Optionen</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($links as $index => $link): ?>
		<tr class="<?= TextHelper::cycle('steel1', 'steelgraulight') ?>">
			<td>
				<a href="<?= $link['link'] ?>"><?= $link['title'] ?></a>
			</td>
			<td style="text-align: right;">
				<a href="<?= $controller->url_for('links/edit', $link['link_id']) ?>">
					<?= Assets::img('icons/16/blue/edit.png') ?>
				</a>
			<?php if ($index): ?>
				<a href="<?= $controller->url_for('links/move', $link['link_id'], 'up') ?>">
					<?= Assets::img('icons/16/yellow/arr_1up.png', array(
						'title' => _('Nach oben bewegen'),
						'alt'   => 'höher',
					)) ?>
				</a>
			<?php else: ?>
				<?= Assets::img('icons/16/grey/arr_1up.png', array(
					'title' => _('Nach oben bewegen'),
					'alt'   => 'höher',
				)) ?>
			<?php endif; ?>
			<?php if ($index + 1 < count($links)): ?>
				<a href="<?= $controller->url_for('links/move', $link['link_id'], 'down') ?>">
					<?= Assets::img('icons/16/yellow/arr_1down.png', array(
						'title' => _('Nach unten bewegen'),
						'alt'   => 'niedriger',
					)) ?>
				</a>
			<?php else: ?>
				<?= Assets::img('icons/16/grey/arr_1down.png', array(
					'title' => _('Nach unten bewegen'),
					'alt'   => 'niedriger',
				)) ?>
			<?php endif; ?>
				<a href="<?= $controller->url_for('links/delete', $link['link_id']) ?>" onclick="return confirm('Wirklich?')">
					<?= Assets::img('icons/16/blue/trash.png') ?>
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
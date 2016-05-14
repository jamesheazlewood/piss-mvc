<div class="inner_screen home">
	<?php foreach($this->data['Review'] as $review) { ?>
		<div class="review_latest">
			<h2><a href="/reviews/view/<?= $review['slug']; ?>"><?= htmlentities($review['title']); ?></a></h2>
			<p><?= htmlentities($review['description']); ?></p>
			<p><small>Reviewed <?= $review['date']; ?></small></p>
		</div>
	<?php } ?>
	<?php pr($this->data['Review']); ?>
</div>
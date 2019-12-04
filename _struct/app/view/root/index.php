<div class="inner_screen home">
	<?php pr($this->data['Reviews']); ?>
	<?php foreach($this->data['Reviews'] as $review) { ?>
		<div class="review_latest">
			<h2><a href="/reviews/view/<?= $review['slug']; ?>"><?= htmlentities($review['title']); ?></a></h2>
			<p><?= htmlentities($review['description']); ?></p>
			<p><small>Reviewed <?= $review['date']; ?></small></p>
		</div>
	<?php } ?>
</div>
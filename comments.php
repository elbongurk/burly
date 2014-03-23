<?php if ( post_password_required() ) return; ?>
<section class="comments">
	<?php wp_list_comments(); ?>
	<?php if (get_comment_pages_count() > 1): ?>
	<nav class="paging">
		<div class="nav-previous"><?php previous_comments_link('&larr; Older Comments'); ?></div>
		<div class="nav-next"><?php next_comments_link('Newer Comments &rarr;'); ?></div>
	</nav>
	<?php endif; ?>
	<?php comment_form(); ?>
</section>
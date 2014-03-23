<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header>
		<h1><?php the_title(); ?></h1>
	</header>
	<section class="content">
		<?php the_content('Continue reading <span class="meta-nav">&rarr;</span>'); ?>
	</section>
	<footer>
		<section class="pagelinks">
			<?php wp_link_pages(); ?>
		</section>
		<section class="tags">
			<?php the_tags(); ?>
		</section>
		<?php comments_template(); ?>
	</footer>
</article>

<?php endwhile; ?>

<?php get_footer(); ?>
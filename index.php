<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header>
		<h1><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
	</header>
	<section class="content">
		<?php the_content('Continue reading <span class="meta-nav">&rarr;</span>'); ?>
	</section>
</article>

<?php endwhile; ?>

<?php if (!have_posts()): ?>
<article>
	<header>
		<h1>No posts found.</h1>
	</header>
	<section class="content">
		<p>Ready to publish your first post? <a href="<?php echo admin_url( 'post-new.php' ); ?>">Get started here</a>.</p>
	</section>
</article>

<?php endif; ?>

<?php if (get_next_posts_link() || get_previous_posts_link()): ?>
<nav class="paging">
	<?php if (get_next_posts_link()) : ?>
	<div class="nav-previous"><?php next_posts_link('&larr; Older Posts'); ?></div>
	<?php endif; ?>
	<?php if (get_previous_posts_link()) : ?>
	<div class="nav-next"><?php previous_posts_link('Newer Posts &rarr;'); ?></div>
	<?php endif; ?>
</nav>
<?php endif; ?>


<?php get_footer(); ?>
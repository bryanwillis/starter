<?php

/**
 * Single post
 *
 * @package	Pilau_Starter
 * @since	0.1
 */

?>

<?php get_header(); ?>

<div id="content" role="main">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">

			<header>

				<h1><?php the_title(); ?></h1>

				<p class="post-meta"><?php pilau_post_date(); ?></p>

			</header>

			<div class="post-content">
				<?php the_content(); ?>
			</div>

		</article><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; endif; ?>

</div>

<?php get_sidebar( 'primary' ); ?>

<?php get_footer(); ?>
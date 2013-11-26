<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">

					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php the_content(); ?>
						<?php if ( ! lab_is_splash()){ ?>
							<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentythirteen' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
						<?php } //endif ?>
					</div><!-- .entry-content -->

					<footer class="entry-meta">
						<?php if ( ! lab_is_splash()){ ?>
							<?php edit_post_link( __( 'Edit', 'twentythirteen' ), '<span class="edit-link">', '</span>' ); ?>
						<?php } //endif ?>
					</footer><!-- .entry-meta -->
				</article><!-- #post -->

				<?php if ( ! lab_is_splash()){ ?>
					<?php comments_template(); ?>
				<?php } //endif ?>
			<?php endwhile; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php if ( ! lab_is_splash()){ ?>
<?php get_sidebar(); ?>
<?php } //endif ?>
<?php get_footer(); ?>
<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>
		<?php if ( have_posts() ) : ?>
	<h1>category products</h1>
			<header class="page-header">
				<h4 class="archive-title products">
					<?php
						if ( is_category() ) :
							single_cat_title();

						elseif ( is_tag() ) :
							single_tag_title();

						elseif ( is_author() ) :
							printf( __( 'Author Archive: %s', 'test' ), '<span class="vcard">' . get_the_author() . '</span>' );

						elseif ( is_day() ) :
							printf( __( 'Daily Archives: %s', 'test' ), '<span>' . get_the_date() . '</span>' );

						elseif ( is_month() ) :
							printf( __( 'Monthly Archives: %s', 'test' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'test' ) ) . '</span>' );

						elseif ( is_year() ) :
							printf( __( 'Yearly Archives: %s', 'test' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'test' ) ) . '</span>' );

						elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
							_e( 'Asides', 'test' );

						elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
							_e( 'Galleries', 'test');

						elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
							_e( 'Images', 'test');

						elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
							_e( 'Videos', 'test' );

						elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
							_e( 'Quotes', 'test' );

						elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
							_e( 'Links', 'test' );

						elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
							_e( 'Statuses', 'test' );

						elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
							_e( 'Audios', 'test' );

						elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
							_e( 'Chats', 'test' );

						else :
							_e( 'Archives', 'test' );

						endif;
					?>
				</h4>
			</header><!-- .page-header -->

			<?php while (have_posts()) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(''); ?> role="article">

						<header class="article-header">

							<h4 class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>

						</header> <!-- end article header -->

						<section class="entry-content clearfix">

							<?php the_post_thumbnail( 'img img-responsive' ); ?>

							<?php the_excerpt(); ?>

						</section> <!-- end article section -->

						<footer class="article-footer">

						</footer> <!-- end article footer -->

					</article> <!-- end article -->

				<?php endwhile; ?>

				<?php get_template_part('includes/template','pager'); //wordpress template pager/pagination ?>

			<?php else : ?>

			<?php get_template_part('includes/template','error'); //wordpress template error message ?>

			<?php endif; ?>


<?php get_footer();
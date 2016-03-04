<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 */
?>
</div><?php //END #main ?>

<?php if (is_active_sidebar('left-sidebar')) : ?>
    <div id="left-sidebar" class="sidebar col-md-4 col-md-pull-4" role="complementary">
        <?php dynamic_sidebar('left-sidebar'); ?>
    </div>
<?php endif; ?>

<?php if (is_active_sidebar('right-sidebar')) : ?>
    <div id="right-sidebar" class="sidebar col-md-4" role="complementary">
        <?php dynamic_sidebar('right-sidebar'); ?>
    </div>
<?php endif; ?>

</div><?php //END #inner-content ?>

</div><?php //END #content ?>
<!--Add bottom Hero-->
<?php if (is_front_page()) { ?>
    <?php
    $args = array('posts_per_page' => -1, 'category_name' => 'news', 'tag' => 'clients, gallery', 'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'));
    $myposts = get_posts($args);
    foreach ($myposts as $post) {
        setup_postdata($post);
        $id = $post->ID;
        function get_match($regex, $content)
        {
            preg_match($regex, $content, $matches);
            return $matches[1];
        }

        $shortcode_args = shortcode_parse_atts(get_match('/\[gallery\s(.*)\]/isU', $post->post_content));
        $ids = $shortcode_args["ids"];

        $args = array(
            'include' => $ids,
            'numberposts' => -1,
            'orderby' => 'post__in',
            'order' => 'menu_order ID',
            'post_mime_type' => 'image',
            'post_parent' => $id,
            'post_status' => null,
            'post_type' => 'attachment'
        );
        ?>
        <h5 class="uppercase clients-heading relative clients-heading no__margin">
            <span class="hr no__margin block absolute"></span>
            <span class="relative semi-bold bg__white text-uppercase clients-heading-text">
                <?php echo get_the_title($id) ?>
            </span>
        </h5>
        <div class="clients row">
            <?php
            $images = get_children($args);
            if ($images) {
                foreach ($images as $image) {
                    ?>
                    <div class="col-xs-2">
                        <img class="img-responsive block-align__center" src="<?php echo $image->guid; ?>"
                             alt=" Our client <?php echo
                             $image->post_title ?>"
                             title="<?php echo $image->post_title ?>">
                    </div>
                <?php }
            }
            ?>
        </div>
        <hr>
        <?php
    }
    wp_reset_postdata();
    ?>


<?php } ?>
<footer id="colophon" class="footer" role="contentinfo">

    <div id="inner-footer" class="wrap">
        <?php // Interior Header Image ?>
        <ul class="list-inline logo-list logo-list__no-border text-center">
            <li>
                <a class="block banner__bottom banner" href="<?php echo esc_url(home_url('/')); ?>"
                   id="bannerBottom"></a>
            </li>

            <li>
					<span class="h3"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"
                                                  title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></span>
            </li>
        </ul>

        <nav role="navigation" class="text-center hidden-xs">
            <?php test_footer_nav(); ?>
        </nav>

        <p class="source-org text-center copyright">&copy; <?php echo date('Y'); ?> Orlin Research, Inc </p>

    </div>

</footer>

<p id="back-top">
    <a href="#top"><i class="fa fa-angle-up"></i></a>
</p>

</div><?php //END #container ?>

<?php wp_footer(); ?>

</body>
</html>
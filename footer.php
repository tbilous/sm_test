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
<?php if(is_front_page() ) { ?>

    <p>front-page</p>

<?php } ?>


<footer id="colophon" class="footer" role="contentinfo">

    <div id="inner-footer" class="wrap clearfix">

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
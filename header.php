<?php
/**
 * The template for displaying the header.
 *
 * Contains the opening tag for the page structure
 */
?><!DOCTYPE html>
<!--[if lt IE 7]>
<html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]>
<html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]>
<html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php wp_title(''); ?></title>
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/images/apple-icon-touch.png">
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">

    <!--[if IE]>
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico"><![endif]-->
    <meta name="msapplication-TileColor" content="#f01d4f">
    <meta name="msapplication-TileImage"
          content="<?php echo get_template_directory_uri(); ?>/images/win8-tile-icon.png">

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <!--[if lt IE 9]>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.min.js"></script>
    <script type="text/javascript"
            src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv-printshiv.min.js"></script>
    <![endif]-->

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<div id="container" class="container-fluid dummy-container">

    <header id="masthead" class="header" role="banner">
        <div class="row">
            <div class="col-md-3">
                <?php // Interior Header Image ?>
                <ul class="list-inline logo-list">
                    <li>
                        <a href="<?php echo esc_url(home_url('/')); ?>" id="banner" style="display: block">
                            <div class="spacer"></div>
                        </a>
                    </li>

                    <li>
					<span id="logo" class="h1"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"
                                                  title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></span>
                    </li>
                </ul>
            </div>
            <div class="col-md-9">
                <div class="header-block">
                    <?php dynamic_sidebar('header-sidebar'); ?>
                    <p class="sub-header"><?php bloginfo("description"); ?></p>
                </div>
            </div>
        </div>
    </header>

    <a class="skip-link screen-reader-text" href="#content"><?php _e('Skip to content', 'test'); ?></a>

    <nav class="navbar navbar-default">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <?php test_main_nav(); ?>

    </nav>
    <div class="row">
        <div class="col-sm-6">

            <?php
            $count = 0;
            $header_thumbs = get_posts('category_name=products&tag=gallery');
            if ($header_thumbs) :
                ?>
                <div class="carousel-border">
                    <div id="leftCarousel" class="carousel slide carousel-top__left" data-ride="carousel"
                         data-interval="false">
                        <div class="carousel-inner" role="listbox">
                            <?php
                            foreach ($header_thumbs as $header_thumb) {
                                setup_postdata($header_thumb);
                                $id = $header_thumb->ID;
                                $class = '';
                                $count++;
                                if ($count == 1) $class .= 'active'; else $class .= '';
                                $full = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'full'); ?>
                                <div class="embed-responsive embed-responsive-16by9 item <?php echo $class; ?>">
                                    <div class="embed-responsive-item"
                                         style="background-image: url('<?php echo $full[0] ?>'); background-size: cover;
                                             "></div>
                                    <div class="carousel-content" style="position: absolute">
                                        <a class="semi-bold" href="<?php echo get_the_permalink($id) ?>">
                                            <?php echo get_the_title($id) ?>

                                            <?php $posttags = get_the_tags($id);
                                            if ($posttags) {
                                                foreach ($posttags as $tag) {
                                                    echo $tag->name . ' ';
                                                }
                                            } ?>
                                        </a>
                                    </div>
                                </div>
                                <?php
                            } ?>
                        </div>
                        <a class="left carousel-control" href="#leftCarousel" role="button" data-slide="prev"></a>
                        <a class="right carousel-control" href="#leftCarousel" role="button" data-slide="next"></a>
                    </div>
                </div>
                <?php
            endif;
            wp_reset_postdata();
            ?>
        </div>
        <div class="col-sm-6">
            <div id="rightCarousel" class="carousel slide carousel-top__right data-ride=" carousel
            ">
            <div class="carousel-inner" role="listbox">
                <?php
                $count = 0;
                query_posts('category_name=news');
                if (have_posts()) : while (have_posts()) : the_post();
                    $class = '';
                    $count++;
                    if ($count == 1) $class .= 'active'; else $class .= '';
                    $full = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full'); ?>
                    <div class="embed-responsive embed-responsive-16by9 item <?php echo $class; ?>">
                        <div class="embed-responsive-item"
                             style="background-image: url('<?php echo $full[0] ?>'); background-size: cover;
                                 "></div>
                        <!--                                <h1>--><?php //echo get_the_title() ?><!--</h1>-->
                    </div>
                    <?php
                endwhile;endif;
                wp_reset_query();
                ?>
            </div>
            <a class="left carousel-control" href="#rightCarousel" role="button" data-slide="prev">
                <span aria-hidden="true"> < </span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#rightCarousel" role="button" data-slide="next">
                <span aria-hidden="true"> > </span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>

<div id="content">

    <div id="inner-content" class="row">

        <?php // Test for active sidebars to set the main content width
        if (is_active_sidebar('left-sidebar') && is_active_sidebar('right-sidebar')) { //both sidebars
            $main_class = 'col-md-4 col-md-push-4';
        } elseif (is_active_sidebar('left-sidebar') && !is_active_sidebar('right-sidebar')) { //left sidebar
            $main_class = 'col-md-9 col-md-push-3';
        } elseif (!is_active_sidebar('left-sidebar') && is_active_sidebar('right-sidebar')) { //right sidebar
            $main_class = 'col-md-9';
        } else { //no sidebar
            $main_class = 'col-md-12';
        }
        ?>

        <div id="main" class="<?php echo $main_class; ?> " role="main">

<?php
/**
 * Template Name: Home Page
 */

get_header(); ?>

<div class="site-content">
    <!-- Header Section -->
   

    <!-- Gallery Folders Section -->
    <section id="my-art" class="gallery-folders">
        <div class="my-art-image">
            <?php $my_art_image = get_custom_textbox_image_url('my_art');
            if (!empty($my_art_image)) {
                echo '<img src="' . esc_url($my_art_image) . '" alt="My Art">';
            } ?>
        </div>

        <div class="my-art-text" style="margin-top: 50px;font-size: 1.4em;text-align: center;padding-bottom: 80px;">
            <?php 
                $my_art_content = get_custom_textbox_content('my_art');
                if (!empty($my_art_content)) {
                    echo wp_kses_post($my_art_content);
                } else {
                    echo '<p>This text is not set. Please go to the dashboard and set the text in the "My Art" textbox.</p>';
                }
            ?>
        </div>
        <div class="container">
            <div class="folders-container">
                <?php

                /* Finds all first-level folders */
                $args = array(
                    'post_type' => 'folder',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'post_parent' => 0 // Only top-level folders
                );
                
                $folder_query = new WP_Query($args);
                
                if ($folder_query->have_posts()) :
                    while ($folder_query->have_posts()) : $folder_query->the_post();
                        $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
                        if (!$thumbnail) {
                            $thumbnail = get_stylesheet_directory_uri() . '/assets/images/folder.png';
                        }
                ?>
                        <div class="folder-icon">
                            <a href="<?php echo esc_url(get_permalink()); ?>">
                                <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                <?php echo esc_html(get_the_title()); ?>
                            </a>
                        </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>No folders found.</p>';
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="about-content">
                        
                        <div class="about-image">
                            <?php $about_image = get_custom_textbox_image_url('about_me');
                            if (!empty($about_image)) {
                                echo '<img src="' . esc_url($about_image) . '" alt="About me">';
                            } ?>
                        </div>
            
                        <div class="about-text" style="margin-top: 50px;font-size: 1.4em;">
                            <?php 
                            $about_content = get_custom_textbox_content('about_me');
                            if (!empty($about_content)) {
                                echo wp_kses_post($about_content);
                            } else {
                                echo '<p>This text is not set. Please go to the dashboard and set the text in the "About Me" textbox.</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Support Section -->
    <section id="support" class="support-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="support-image">
                        <?php $support_image = get_custom_textbox_image_url('support');
                        if (!empty($support_image)) {
                            echo '<img src="' . esc_url($support_image) . '" alt="Support">';
                        } ?>
                    </div>

                    <div class="support-content" style="padding-bottom: 30px;">
                    <?php 

                        $support_content = get_custom_textbox_content('support');
                        if (!empty($support_content)) {
                            echo wp_kses_post($support_content);
                        } else {
                            echo 'This text is not set. Please go to the dashboard and set the text in the "Support" textbox';
                        }  ?>
                    </div>
                    <div class="donation-links">
                        <a target="_blank" href="https://www.paypal.com/donate/?hosted_button_id=BC7NRW4RNQN2U" class="paypal-link">Support via PayPal</a>
                        <a target="_blank" href="https://buy.stripe.com/test_5kQ8wH1KSeqb8TX3wZ6wE00" class="stripe-link">Support via Stripe</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2>Contact</h2>

                    <div class="contact-content" style="padding-bottom: 30px;"> 
                    <?php 
                        $contact_content = get_custom_textbox_content('contact');
                        if (!empty($contact_content)) {
                            echo  wp_kses_post($contact_content);
                        } else {
                            echo 'This text is not set. Please go to the dashboard and set the text in the "Contact" textbox';
                        } 
                    ?>
                    </div>
                    <div class="contact-form">
                        <?php echo do_shortcode('[contact-form-7 id="90292c4" title="Contact form 1"]'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
jQuery(document).ready(function($) {


    // Scroll highlighting for navigation links
    function highlightNavOnScroll() {
        var scrollPosition = $(window).scrollTop();
        var windowHeight = $(window).height();
        
        // Define sections to check
        var sections = [
            { id: 'my-art', offset: 100 },
            { id: 'about', offset: 100 },
            { id: 'support', offset: 100 },
            { id: 'contact', offset: 100 }
        ];
        
        // Remove active class from all nav links
        $('.navbar-nav a').removeClass('nav-activated');
        $('.navbar-nav a').removeClass('nav-active');
        $('li.menu-item').removeClass('active');
        
        // Check each section
        sections.forEach(function(section) {
            var $section = $('#' + section.id);
            if ($section.length) {
                var sectionTop = $section.offset().top - section.offset;
                var sectionBottom = sectionTop + $section.outerHeight();
                
                if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {

                    // Find and highlight the corresponding nav link
                    var $navLink = $('.navbar-nav a[href="#' + section.id + '"]');
                    
                    if ($navLink.length > 0) {
                        $navLink.addClass('nav-activated');
                    }
                }
            }
        });
        
        // If we're at the top, highlight the first section
        if (scrollPosition < 100) {
            var $firstNavLink = $('.navbar-nav a[href="#my-art"]');
            
            if ($firstNavLink.length > 0) {
                $firstNavLink.addClass('nav-activated');
            }
        }
    }
    
    // Run on scroll
    $(window).scroll(function() {
        highlightNavOnScroll();
    });
    
    // Run on page load
    highlightNavOnScroll();
    
    // Debug: Log all navigation links to console
    console.log('Navigation links found:');
    $('.navbar-nav a, .menu-items a').each(function() {
        console.log('Link text: "' + $(this).text() + '", href: "' + $(this).attr('href') + '"');
    });
});
</script>

<?php get_footer(); ?> 
<?php
/**
 * Template Name: Home Page
 */

get_header(); ?>

<div class="site-content">
    <!-- Header Section -->
   

    <!-- Gallery Folders Section -->
    <section class="gallery-folders">
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
                            <img src="about_me_small.webp" alt="About me">
                        </div>
            
                        <div class="about-text" style="margin-top: 50px;font-size: 1.4em;">
                            <p>Ipsam omnis sapiente rerum non. Possimus illum laborum quisquam voluptates aut dicta officia qui. Doloribus repudiandae fuga culpa ipsa. Voluptatibus reprehenderit omnis autem pariatur aut voluptas. Architecto voluptates iusto nisi nobis voluptas.

Fugiat doloribus aspernatur incidunt provident architecto eum at deleniti. Nam sint laudantium atque maiores ex. Enim ex perferendis vel neque. Ea quas eligendi quasi itaque. Tempore voluptas animi qui impedit dolorem corrupti dolores.</p>
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
                    <h2>Support</h2>
                    <div class="donation-links">
                        <a href="https://www.paypal.com/donate/?hosted_button_id=BC7NRW4RNQN2U" class="paypal-link">Support via PayPal</a>
                        <a href="#" class="stripe-link">Support via Stripe</a>
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
    // Hamburger menu toggle
    $('.hamburger-menu').click(function() {
        $('.menu-overlay').toggleClass('active');
    });

    // Close menu when clicking outside
    $(document).click(function(event) {
        if (!$(event.target).closest('.hamburger-menu, .menu-overlay').length) {
            $('.menu-overlay').removeClass('active');
        }
    });
});
</script>

<?php get_footer(); ?> 
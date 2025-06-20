<?php
get_header();
?>

<div class="container single-folder">
    <div class="row">
        <div class="col-12">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                    $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    if (!$thumbnail) {
                        $thumbnail = get_stylesheet_directory_uri() . '/assets/images/folder.png';
                    }
            ?>
                    <div class="folder-details">
                        <h1><?php the_title(); ?></h1>
                        <div class="folder-content" style="text-align:center;padding-top:140px">
                            <?php the_content(); ?>

                            <?php
                            $children = get_pages(array(
                                'child_of' => get_the_ID(),
                                'sort_column' => 'menu_order',
                                'post_type' => 'folder'
                            ));

                            // print_r($children);
                            if ($children) :
                                //echo '<ul class="folder-children" style="display:flex;flex-direction:column;gap:90px;">';    
                                echo '<ul class="folder-children" style="">';    

                                foreach ($children as $child) :
                                    echo '<li style="list-style:none;">';
                                    //echo '<a href="' . get_permalink($child->ID) . '">' . $child->post_title . '</a>';

                                    // Get featured image ID
                                    $image_id = get_post_thumbnail_id( $child );

                                    $image = wp_get_attachment_image_url($image_id, 'full');

                                    $shift = rand(-20000, 20000);

                                    $shift = $shift / 1000;

                                    // Text under image should be shifted to the same amount as the image
                                    if ($image) :
                                        echo '<div class="gallery-item" style="display:inline-block;">';
                                        echo '<img class="gallery-image" src="' . $image . '" ' 
                                         . ' alt="' . $child->post_title . '" '
                                         . ' style="max-height:500px;">';

                                        echo '<p class="gallery-image-description" style="position:relative;">' 
                                        . get_the_content( null, false, $child->ID) 
                                        . '</p>';
                                        echo '</div>';
                                    endif;  

                                    echo '</li>';
                                endforeach;
                                echo '</ul>';
                            endif;  
                            ?>
                        </div>
                    </div>
            <?php
                endwhile;
            else :
                echo '<p>No folder found.</p>';
            endif;
            ?>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Create modal HTML and append to body if not exists
    if ($('#gallery-modal').length === 0) {
        $('body').append('<div id="gallery-modal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.85);justify-content:center;align-items:center;"><span id="gallery-modal-close" style="position:absolute;top:30px;right:50px;font-size:3em;color:#fff;cursor:pointer;z-index:10001;">&times;</span><img id="gallery-modal-img" src="" style="max-width:90vw;max-height:90vh;box-shadow:0 0 30px #000;z-index:10000;" /></div>');
    }
    // Click handler
    $(document).on('click', 'img.gallery-image', function() {
        var src = $(this).attr('src');
        $('#gallery-modal-img').attr('src', src);
        $('#gallery-modal').fadeIn(200);
    });
    // Close handler
    $(document).on('click', '#gallery-modal-close, #gallery-modal', function(e) {
        if (e.target === this) {
            $('#gallery-modal').fadeOut(200);
        }
    });
    // Prevent modal image click from closing
    $(document).on('click', '#gallery-modal-img', function(e) {
        e.stopPropagation();
    });

    // Ensure description matches image width
    function setDescriptionWidths() {
        $('img.gallery-image').each(function() {
            var $img = $(this);
            var $desc = $img.next('.gallery-image-description');
            if ($desc.length) {
                $desc.css('width', $img.width() + 'px');
                $desc.css('left', ($img.width() / 8) + 'px');
            }
        });
    }

});
</script>

<?php
get_footer();
?> 
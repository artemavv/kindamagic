<?php
/**
 * WP Bootstrap Starter Child Theme functions and definitions
 *
 * @package WP_Bootstrap_Starter_Child
 */

// Enqueue parent theme styles
function wp_bootstrap_starter_child_enqueue_styles() {
    wp_enqueue_style('wp-bootstrap-starter-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('wp-bootstrap-starter-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('wp-bootstrap-starter-style'), filemtime( get_stylesheet_directory() . '/style.css' )
    );
}
add_action('wp_enqueue_scripts', 'wp_bootstrap_starter_child_enqueue_styles');

// Add custom post type named "folder"
function register_folder_post_type() {
    $labels = array(
        'name'               => 'Gallery',
        'singular_name'      => 'Gallery',
        'menu_name'          => 'Galleries',
        'add_new'           => 'Add New',
        'add_new_item'      => 'Add New Gallery',
        'edit_item'         => 'Edit Gallery',
        'new_item'          => 'New Gallery',
        'view_item'         => 'View Gallery',
        'search_items'      => 'Search Galleries',
        'not_found'         => 'No galleries found',
        'not_found_in_trash'=> 'No galleries found in Trash'
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'gallery'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'exclude_from_search'=> true // Makes the post type not searchable
    );

    register_post_type('folder', $args);
}
add_action('init', 'register_folder_post_type');

// Define the textboxes array - easily extendable
function get_custom_textboxes_config() {
    return array(
        'my_art' => array(
            'id' => 'my_art_text',
            'image_id' => 'my_art_image',
            'label' => 'My Art',
            'description' => 'Enter "My Art" text here'
        ),
        'about_me' => array(
            'id' => 'about_me_text',
            'image_id' => 'about_me_image',
            'label' => 'About me',
            'description' => 'Enter "About Me" text here'
        ),
        'support' => array(
            'id' => 'support_text',
            'image_id' => 'support_image',
            'label' => 'Support',
            'description' => 'Enter "Support Me" text here'
        ),
        'contact' => array(
            'id' => 'contact_text',
            'image_id' => 'contact_image',
            'label' => 'Contact',
            'description' => 'Enter "Contact Me" text here'
        )
    );
}

// Add admin menu page
function add_custom_admin_page() {
    add_menu_page(
        'Site Content', // Page title
        'Site Content', // Menu title
        'manage_options', // Capability
        'site-content', // Menu slug
        'render_custom_admin_page', // Function to render the page
        'dashicons-admin-generic', // Icon
        30 // Position
    );
}
add_action('admin_menu', 'add_custom_admin_page');

// Render the admin page
function render_custom_admin_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    $textboxes_config = get_custom_textboxes_config();

    // Save data if form is submitted
    if (isset($_POST['submit'])) {
        
        foreach ($textboxes_config as $key => $config) {
            if (isset($_POST[$config['id']])) {
                update_option($config['id'], wp_kses_post($_POST[$config['id']]));
            }
            if (isset($_POST[$config['image_id']])) {
                update_option($config['image_id'], sanitize_text_field($_POST[$config['image_id']]));
            }
        }
        
        echo '<div class="notice notice-success is-dismissible"><p>Settings saved successfully!</p></div>';
    }

    // Enqueue WordPress media uploader
    wp_enqueue_media();

    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="">
            <?php
            foreach ($textboxes_config as $key => $config) {
                $current_value = get_option($config['id'], '');
                $current_image_id = get_option($config['image_id'], '');
                $image_url = '';
                if ($current_image_id) {
                    $image_url = wp_get_attachment_image_url($current_image_id, 'thumbnail');
                }
                ?>
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="<?php echo esc_attr($config['id']); ?>"><?php echo esc_html($config['label']); ?></label>
                            </th>
                            <td>
                                <textarea 
                                    name="<?php echo esc_attr($config['id']); ?>" 
                                    id="<?php echo esc_attr($config['id']); ?>" 
                                    rows="10" 
                                    cols="50" 
                                    class="large-text"
                                    placeholder="<?php echo esc_attr($config['description']); ?>"
                                ><?php echo esc_textarea($current_value); ?></textarea>
                                <p class="description"><?php echo esc_html($config['description']); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="<?php echo esc_attr($config['image_id']); ?>"><?php echo esc_html($config['label']); ?> Image</label>
                            </th>
                            <td>
                                <input type="hidden" name="<?php echo esc_attr($config['image_id']); ?>" id="<?php echo esc_attr($config['image_id']); ?>" value="<?php echo esc_attr($current_image_id); ?>" />
                                <div class="image-preview-wrapper">
                                    <?php if ($image_url): ?>
                                        <img src="<?php echo esc_url($image_url); ?>" style="max-width: 150px; height: auto; margin-bottom: 10px;" />
                                    <?php endif; ?>
                                </div>
                                <button type="button" class="button button-secondary" onclick="openMediaLibrary('<?php echo esc_js($config['image_id']); ?>')">
                                    <?php echo $current_image_id ? 'Change Image' : 'Select Image'; ?>
                                </button>
                                <?php if ($current_image_id): ?>
                                    <button type="button" class="button button-link-delete" onclick="removeImage('<?php echo esc_js($config['image_id']); ?>')">
                                        Remove Image
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php
            }
            ?>
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>

    <script type="text/javascript">
        function openMediaLibrary(imageFieldId) {
            var imageField = document.getElementById(imageFieldId);
            var imagePreview = imageField.parentNode.querySelector('.image-preview-wrapper');
            var selectButton = imageField.parentNode.querySelector('button');
            var removeButton = imageField.parentNode.querySelector('.button-link-delete');
            
            var frame = wp.media({
                title: 'Select or Upload Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                imageField.value = attachment.id;
                
                // Update preview
                imagePreview.innerHTML = '<img src="' + attachment.sizes.thumbnail.url + '" style="max-width: 150px; height: auto; margin-bottom: 10px;" />';
                
                // Update button text
                selectButton.textContent = 'Change Image';
                
                // Show remove button if not already present
                if (!removeButton) {
                    var newRemoveButton = document.createElement('button');
                    newRemoveButton.type = 'button';
                    newRemoveButton.className = 'button button-link-delete';
                    newRemoveButton.textContent = 'Remove Image';
                    newRemoveButton.onclick = function() {
                        removeImage(imageFieldId);
                    };
                    selectButton.parentNode.appendChild(newRemoveButton);
                }
            });

            frame.open();
        }

        function removeImage(imageFieldId) {
            var imageField = document.getElementById(imageFieldId);
            var imagePreview = imageField.parentNode.querySelector('.image-preview-wrapper');
            var selectButton = imageField.parentNode.querySelector('button');
            var removeButton = imageField.parentNode.querySelector('.button-link-delete');
            
            imageField.value = '';
            imagePreview.innerHTML = '';
            selectButton.textContent = 'Select Image';
            
            if (removeButton) {
                removeButton.remove();
            }
        }
    </script>
    <?php
}

// Function to get textbox content for frontend use
function get_custom_textbox_content($textbox_id) {
    $textboxes_config = get_custom_textboxes_config();
    
    if (isset($textboxes_config[$textbox_id])) {
        return get_option($textboxes_config[$textbox_id]['id'], '');
    }
    
    return '';
}

// Function to get image content for frontend use
function get_custom_textbox_image($textbox_id, $size = 'full') {
    $textboxes_config = get_custom_textboxes_config();
    
    if (isset($textboxes_config[$textbox_id])) {
        $image_id = get_option($textboxes_config[$textbox_id]['image_id'], '');
        if ($image_id) {
            return wp_get_attachment_image($image_id, $size);
        }
    }
    
    return '';
}

// Function to get image URL for frontend use
function get_custom_textbox_image_url($textbox_id, $size = 'full') {
    $textboxes_config = get_custom_textboxes_config();
    
    if (isset($textboxes_config[$textbox_id])) {
        $image_id = get_option($textboxes_config[$textbox_id]['image_id'], '');
        if ($image_id) {
            return wp_get_attachment_image_url($image_id, $size);
        }
    }
    
    return '';
}

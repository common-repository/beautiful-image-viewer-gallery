<?php 

/*
Plugin Name: Beautiful image viewer gallery
Author: Raihan
Description:This is a beautiful responsive image gallery. You can see each image with 10000% zoom and it has  more features. After installing it, a Gallery menu will be created on your WordPress dashboard, form which you can easily set the image. You can use it as a portfolio gallery, photo gallery,  photo album, image gallery, widget image gallery etc. (You have to use this shortcode "[rai-image-viewer] or [rai-image-viewer title="your title" description="Your description"][/rai-image-viewer]" in your page to show the gallery).
Version: 1.0
Text Domain: rai_image_viewer
Domain Path: /languages
*/


class Rai_image_class {
    
    public function __construct(){
        add_action('after_setup_theme', array($this,'rai_theme_support'));
        add_action('init', array($this, 'rai_image_viewer_function'));
        add_action('wp_enqueue_scripts', array($this,'rai_image_viewer_scripts'));
        add_shortcode('rai-image-viewer', array($this, 'rai_image_viewer_scode'));
        
        define('BG_CSS_URI',trailingslashit('assets/css'));
        define('BG_JS_URI',trailingslashit('assets/js'));
    }
    
    public function rai_theme_support(){
        add_theme_support('post-thumbnails');
    }
    
    public function rai_image_viewer_function(){
        load_plugin_textdomain('rai_image_viewer', false, dirname(__FILE__).'/language');
        
        register_post_type('rai-image-post',array(
            'labels'=>array(
                'name'=>'BG Gallery',
                'all_items'=>'All Image',
                'add_new'=>'Add Image',
                'add_new_item' => 'Add new Image',
                'edit_item'  => 'Edit Image',
                'view_items' => 'View Images',
                'not_found' => 'No image found',
                'not_found_in_trash' => 'No image found in trash',
            ),
            'public'=>true,
            'menu_icon'=>'dashicons-format-image',
            'supports'=>array('thumbnail','title')
        ));
    }
    
    public function rai_image_viewer_scripts(){
        //CSS style
        wp_enqueue_style('rai-viewercss', plugins_url(BG_CSS_URI.'viewer.css',__FILE__));
        wp_enqueue_style('rai-imageviewercss', plugins_url(BG_CSS_URI.'imageviewer.css',__FILE__));
        
        //JS script
        wp_enqueue_script('rai-viewerjs', plugins_url(BG_JS_URI.'viewer.js',__FILE__),array('jquery'),null,true);
        wp_enqueue_script('rai-imageviewerjs', plugins_url(BG_JS_URI.'imageviewer.js',__FILE__),array('jquery'),null,true);
    }
    
    
    public function rai_image_viewer_scode($img_attr, $img_content){
        //Shortcode [rai-image-viewer title="your title" description="Description"][/rai-image-viewer]
        $scode_atts = shortcode_atts(array(
                'title'=>'',
                'description'=>''
            ),$img_attr);
            extract($scode_atts);
        //custom post type
        $rai_image_post = new WP_Query(array(
            'post_type'=>'rai-image-post',
            'posts_per_page'=> -1
        ));
        ob_start();
        ?>
        <div class="img-viewer">
            <h2>
                <?php echo esc_html($title); ?>
            </h2>
            <p>
                <?php echo esc_html($description); ?>
            </p>

            <div>
                <ul id="images">
                    <?php if($rai_image_post->have_posts()):
                                while($rai_image_post->have_posts()):
                                $rai_image_post->the_post(); 
                                ?>
                    <li>
                        <?php the_post_thumbnail(); ?>
                    </li>
                    <?php
                    endwhile;
                    endif;
                    ?>
                </ul>
            </div>
        </div>
<?php
    return ob_get_clean();
    }
}
new Rai_image_class();
<?php

add_action( 'widgets_init', array('Kopa_Widget_Advertisement', 'register_widget'));

class Kopa_Widget_Advertisement extends Kopa_Widget {

    public static function register_widget(){
        register_widget('Kopa_Widget_Advertisement');
    }

    public function __construct() {
        $this->widget_cssclass = 'kopa-ads-widget';
        $this->widget_description = __('Show advertisement', 'new-lotus');
        $this->widget_id = 'kopa_ads_widget';
        $this->widget_name = __('(NewLotus) Advertisement', 'new-lotus');

        $this->settings = array(
            'image_url' => array(
                'type' => 'upload',
                'std' => '',
                'label' => __('Upload Image', 'new-lotus')
            ),
            'image_title' => array(
                'type' => 'text',
                'std' => '',
                'label' => __('Image Title (Show when hover on the image)', 'new-lotus')
            ),
            'image_link' => array(
                'type' => 'text',
                'std' => '',
                'label' => __('Image Link', 'new-lotus')
            ),
            'target' => array(
                'type' => 'checkbox',
                'std' => 1,
                'label' => __('Open link in a new tab', 'new-lotus')
            ),
        );
        parent::__construct();
    }

    /**
     * widget function.
     *
     * @see WP_Widget
     * @access public
     * @param array $args
     * @param array $instance
     * @return void
     */
    public function widget($args, $instance) {

        if ($this->get_cached_widget($args))
            return;

        ob_start();
        extract($args);
        echo $before_widget;

        if (!empty($instance['image_url'])) {
            $target = '_self';
            if (1 == $instance['target']) {
                $target = '_blank';
            }
            // crop image
            if (!empty($instance['image_url'])) {
                echo '<div class="widget-content"><a href="' . esc_url($instance['image_link']) . '" target="' . $target . '" title="' . $instance['image_title'] . '" class="img-responsive"><img src="' . $instance['image_url'] . '" alt="' . $instance['image_title'] . '" title="' . $instance['image_title'] . '" /></a></div>';
            }
        }

        echo $after_widget;

        $content = ob_get_clean();

        echo $content;

        $this->cache_widget($args, $content);
    }

}
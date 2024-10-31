<?php

add_action( 'widgets_init', array('Kopa_Widget_Social', 'register_widget'));

class Kopa_Widget_Social extends Kopa_Widget {

    public static function register_widget(){
        register_widget('Kopa_Widget_Social');
    }

    public function __construct() {
        $this->widget_cssclass = 'kopa-social-widget';
        $this->widget_description = __('Show social link', 'new-lotus');
        $this->widget_id = 'kopa-social-widget';
        $this->widget_name = __('(NewLotus) Social link', 'new-lotus');

        $this->settings = array(
            'title' => array(
                'type' => 'text',
                'std' => 'Social Network',
                'label' => __('Social Network', 'new-lotus')
            ),
            'description' => array(
                'type' => 'textarea',
                'std' => '',
                'label' => __('Description', 'new-lotus')
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
        $title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
        echo $before_widget;
        if(!empty($title)){
            echo $before_title . $title . $after_title;
        }

        if(!empty($instance['description'])){
            echo '<span class="t-des">' . $instance['description'] . '</span>';
        }
        echo '<div class="widget-content">' . kopa_social_links() . '</div>';

        echo $after_widget;

        $content = ob_get_clean();

        echo $content;

        $this->cache_widget($args, $content);
    }

}
<?php

add_action( 'widgets_init', array('Kopa_Widget_Flickr', 'register_widget'));

class Kopa_Widget_Flickr extends Kopa_Widget {

    public static function register_widget(){
        register_widget('Kopa_Widget_Flickr');
    }

    public function __construct() {
        $this->widget_cssclass = 'kopa-flickr-widget';
        $this->widget_description = __('Kopa flickr', 'new-lotus');
        $this->widget_id = 'kopa-flickr-widget';
        $this->widget_name = __('(NewLotus) Flickr', 'new-lotus');

        $this->settings = array(
            'title' => array(
                'type' => 'text',
                'std' => __('Photo on flickr', 'new-lotus'),
                'label' => __('Title', 'new-lotus')
            ),
            'flickr_id' => array(
                'type' => 'text',
                'std' => '',
                'label' => __('Flickr id', 'new-lotus')
            ),
            'limit' => array(
                'type' => 'text',
                'std' => __('6', 'new-lotus'),
                'label' => __('Limit', 'new-lotus')
            ),

        );
        parent::__construct();
    }

    function widget($args, $instance){
        if ($this->get_cached_widget($args))
            return;

        ob_start();
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? __('RECENT ', 'new-lotus') : $instance['title'], $instance, $this->id_base);

        $id = $instance['flickr_id'];
        $limit = $instance['limit'];

        $out = '<div class="widget-content">';
        $out .= sprintf('<div class="flickr-wrap clearfix" data-user="%s" data-limit="%s">', $id, $limit);
        $out .= '<ul class="clearfix list-unstyled"></ul>';
        $out .= '</div></div>';
        echo $before_widget;
        if (!empty($title)) {
            echo $before_title.$title.$after_title;
        }
        echo apply_filters('kopa_flickr_widget', $out);
        echo $after_widget;
        $content = ob_get_clean();
        echo $content;
        $this->cache_widget($args, $content);
    }


}
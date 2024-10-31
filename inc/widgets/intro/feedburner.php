<?php

add_action( 'widgets_init', array('Kopa_Widget_Feedburner_Subscribe', 'register_widget'));

class Kopa_Widget_Feedburner_Subscribe extends Kopa_Widget {

    public static function register_widget(){
        register_widget('Kopa_Widget_Feedburner_Subscribe');
    }

    public function __construct() {
        $this->widget_cssclass = 'kopa-newletter-widget';
        $this->widget_description = __('Kopa newletter', 'new-lotus');
        $this->widget_id = 'kopa-newletter-widget';
        $this->widget_name = __('(NewLotus) Newletter', 'new-lotus');

        $this->settings = array(
            'feedburner_id' => array(
                'type' => 'text',
                'std' => '',
                'label' => __('Feedburner id', 'new-lotus')
            ),
            'placeholder' => array(
                'type' => 'text',
                'std' => __('Enter your email address ...', 'new-lotus'),
                'label' => __('Placeholder', 'new-lotus')
            ),
            'submit_btn' => array(
                'type' => 'text',
                'std' => __('Subcribe', 'new-lotus'),
                'label' => __('Text submit', 'new-lotus')
            ),

        );
        parent::__construct();
    }

    function widget( $args, $instance ) {
        if ($this->get_cached_widget($args))
            return;

        ob_start();
        extract($args);

        $feedburner_id = $instance['feedburner_id'];
        $submit_btn_text = ! empty( $instance['submit_btn'] ) ? $instance['submit_btn'] : __( 'Signup', 'new-lotus' );
        $placeholder = ! empty( $instance['placeholder'] ) ? $instance['placeholder'] : __( 'Enter your email address ...', 'new-lotus' );
        echo $before_widget;
        ?>


    <div class="widget-content">
        <form action="http://feedburner.google.com/fb/a/mailverify" method="post" class="form-news-letter" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $feedburner_id; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520'); return true;">
            <input type="hidden" value="<?php echo esc_attr( $feedburner_id ); ?>" name="uri">

            <input type="text" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" name="search-text" value="<?php echo $placeholder;?>" class="form-control" size="40">
            <input type="submit" class="submit" value="<?php echo $submit_btn_text;?>">

            <div id="newsletter-response"></div>
        </form>
    </div>
    <?php
        echo $after_widget;
        $content = ob_get_clean();
        echo $content;
        $this->cache_widget($args, $content);
    }
}
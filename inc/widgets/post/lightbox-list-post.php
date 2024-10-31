<?php

add_action( 'widgets_init', array('Kopa_Widget_Lightbox_Carousel', 'register_widget'));

class Kopa_Widget_Lightbox_Carousel extends Kopa_Widget {

    public static function register_widget(){
        register_widget('Kopa_Widget_Lightbox_Carousel');
    }

    public function __construct() {
        $this->widget_cssclass = 'kopa-small-thumb-lightbox-carousel';
        $this->widget_description = __('Kopa Lightbox Galleries', 'new-lotus');
        $this->widget_id = 'kopa-small-thumb-lightbox-carousel';
        $this->widget_name = __('(NewLotus) Lightbox Galleries', 'new-lotus');

        $all_cats = get_categories();
        $categories = array();
        $categories[''] = __('---Select categories---', 'new-lotus');
        foreach ($all_cats as $cat) {
            $categories[$cat->term_id] = $cat->name;
        }

        $all_tags = get_tags();
        $tags = array();
        $tags[''] = __('---Select tags---', 'new-lotus');
        foreach ($all_tags as $tag) {
            $tags[$tag->term_id] = $tag->name;
        }

        $this->settings = array(
            'title' => array(
                'type' => 'text',
                'std' => __('Gallery posts', 'new-lotus'),
                'label' => __('Gallery posts', 'new-lotus')
            ),
            'categories' => array(
                'type' => 'multiselect',
                'std' => '',
                'label' => __('Categories', 'new-lotus'),
                'options' => $categories,
                'size' => '5',
            ),
            'relation' => array(
                'type' => 'select',
                'label' => __('Relation', 'new-lotus'),
                'std' => 'OR',
                'options' => array(
                    'AND' => __('AND', 'new-lotus'),
                    'OR' => __('OR', 'new-lotus'),
                ),
            ),
            'tags' => array(
                'type' => 'multiselect',
                'std' => '',
                'label' => __('Tags', 'new-lotus'),
                'options' => $tags,
                'size' => '5',
            ),
            'orderby' => array(
                'type' => 'select',
                'std' => 'date',
                'label' => __('Order by', 'new-lotus'),
                'options' => array(
                    'ID' => __('Post id', 'new-lotus'),
                    'title' => __('Title', 'new-lotus'),
                    'date' => __('Date', 'new-lotus'),
                    'rand' => __('Random', 'new-lotus'),
                    'comment_count' => __('Number of comments', 'new-lotus'),
                ),
            ),
            'order' => array(
                'type' => 'select',
                'std' => 'DESC',
                'label' => __('Ordering', 'new-lotus'),
                'options' => array(
                    'ASC' => __('ASC', 'new-lotus'),
                    'DESC' => __('DESC', 'new-lotus'),
                ),
            ),
            'posts_per_page' => array(
                'type' => 'number',
                'std' => '5',
                'label' => __('Number of posts', 'new-lotus'),
                'min' => '1',
            ),
            'timestamp' => array(
                'type' => 'select',
                'std' => '',
                'label' => __('Timestamp (ago)', 'new-lotus'),
                'options' => array(
                    '' => __('-- Select --', 'new-lotus'),
                    '-1 week' => __('1 week', 'new-lotus'),
                    '-2 week' => __('2 weeks', 'new-lotus'),
                    '-3 week' => __('3 weeks', 'new-lotus'),
                    '-1 month' => __('1 months', 'new-lotus'),
                    '-2 month' => __('2 months', 'new-lotus'),
                    '-3 month' => __('3 months', 'new-lotus'),
                    '-4 month' => __('4 months', 'new-lotus'),
                    '-5 month' => __('5 months', 'new-lotus'),
                    '-6 month' => __('6 months', 'new-lotus'),
                    '-7 month' => __('7 months', 'new-lotus'),
                    '-8 month' => __('8 months', 'new-lotus'),
                    '-9 month' => __('9 months', 'new-lotus'),
                    '-10 month' => __('10 months', 'new-lotus'),
                    '-11 month' => __('11 months', 'new-lotus'),
                    '-1 year' => __('1 year', 'new-lotus'),
                    '-2 year' => __('2 years', 'new-lotus'),
                    '-3 year' => __('3 years', 'new-lotus'),
                    '-4 year' => __('4 years', 'new-lotus'),
                    '-5 year' => __('5 years', 'new-lotus'),
                    '-6 year' => __('6 years', 'new-lotus'),
                    '-7 year' => __('7 years', 'new-lotus'),
                    '-8 year' => __('8 years', 'new-lotus'),
                    '-9 year' => __('9 years', 'new-lotus'),
                    '-10 year' => __('10 years', 'new-lotus'),
                ),
            ),
        );
        parent::__construct();
    }

    function widget($args, $instance) {
        if ($this->get_cached_widget($args))
            return;

        ob_start();
        extract($args);

        $title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
        $query_args_new = kopa_build_query($instance);

        $r = new WP_Query($query_args_new);
        echo $before_widget;
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        if ($r->have_posts()) {
            ?>
        <div class="widget-content">
            <ul class="list-unstyled">
                <?php
                while ($r->have_posts()) {
                    $r->the_post();
                    ?>
                    <li>
                        <h2 class="post-cat"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <?php
                        $attachment_ids = kopa_content_get_gallery_attachment_ids(get_the_content());
                        if ($attachment_ids) {
                            ?>
                            <div class="wrap-carousel">
                                <div class="owl-carousel" >
                                    <?php foreach ($attachment_ids as $ids) {
                                    $params = array('width' => apply_filters('newlotus_custom_lightbox_size', 800),'crop' => true);
                                    $img_src = kopa_get_image_by_id($ids);
                                    ?>
                                    <div class="item">
                                        <a href="<?php echo bfi_thumb($img_src,$params); ?>" class="group-colorbox">
                                            <?php echo kopa_the_image($ids, 'gallery-' . $ids, 300, 217, true, true); ?>
                                        </a>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                    </li>
                    <?php }
                ?>
            </ul>
        </div>
        <?php
        }
        wp_reset_postdata();


        echo $after_widget;
        $content = ob_get_clean();
        echo $content;
        $this->cache_widget($args, $content);
    }

}
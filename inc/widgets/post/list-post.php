<?php

add_action( 'widgets_init', array('Kopa_Widget_Article_List', 'register_widget'));

class Kopa_Widget_Article_List extends Kopa_Widget{

    public static function register_widget(){
        register_widget('Kopa_Widget_Article_List');
    }

    public function __construct() {
        $this->widget_cssclass = 'kopa-posts-widget first-big';
        $this->widget_description = __('Kopa Article List', 'new-lotus');
        $this->widget_id = 'kopa-posts-widget';
        $this->widget_name = __('(NewLotus) Article List', 'new-lotus');

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
                'std' => __('Life style', 'new-lotus'),
                'label' => __('Life style', 'new-lotus')
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
            'limit' => array(
                'type' => 'number',
                'std' => '55',
                'label' => __('Exerpt of posts', 'new-lotus'),
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

    function widget($args, $instance){
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

        if($r->have_posts()){
            ?>
        <div class="widget-content">
            <ul class="list-unstyled">
                <?php
                $index=1;
                while($r->have_posts()){
                    $r->the_post();
                    if(1 == $index){
                        ?>
                        <li class="item">
                            <a href="<?php the_permalink(); ?>" class="post-thumb img-responsive">
                                <?php if(has_post_thumbnail()){
                                echo kopa_the_image(get_the_ID(),get_the_title(),400,231,true);
                            } else {
                                echo '<img src="//placehold.it/400x231" alt="no thumbnail">';
                            } ?>
                            </a>
                            <div class="post-caption">
                                    <span class="post-meta">
                                        <span class="post-date"><?php echo get_the_date(get_option('date_format')); ?> </span>
                                        <?php if(comments_open()){
                                        ?>
                                        <span class="post-comments">
                                            <?php
                                            comments_popup_link( __('No comment','new-lotus'), __('1 comment','new-lotus'), __('% comments','new-lotus'));
                                            ?>
                                            </span>
                                        <?php
                                    } ?>
                                    </span>
                                <h4 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                <p><?php echo kopa_get_the_excerpt_for_widget('','',$instance['limit']); ?></p>
                                <a href="<?php the_permalink(); ?>" class="more-link"><?php echo esc_html(__('read more','new-lotus')); ?></a>
                            </div>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li class="item">
                            <a href="<?php the_permalink(); ?>" class="post-thumb img-responsive">
                                <?php if(has_post_thumbnail()){
                                echo kopa_the_image(get_the_ID(),get_the_title(),60,60,true);
                            } else{
                                echo '<img src="//placehold.it/60x60" alt="no thumbnail">';
                            } ?>
                            </a>
                            <div class="post-caption">
                                    <span class="post-meta">
                                        <span class="post-date"><?php echo get_the_date(get_option('date_format')); ?> </span>
                                    </span>
                                <h4 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            </div>
                        </li>
                        <?php
                    }
                    $index++;
                }
                wp_reset_postdata();
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
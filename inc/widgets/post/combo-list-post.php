<?php

add_action( 'widgets_init', array('Kopa_Widget_Accordions', 'register_widget'));

class Kopa_Widget_Accordions extends Kopa_Widget {

    public static function register_widget(){
        register_widget('Kopa_Widget_Accordions');
    }

    public function __construct() {
        $this->widget_cssclass = 'kopa-accordion-widget';
        $this->widget_description = __('Display popular articles, recent articles and most comment articles', 'new-lotus');
        $this->widget_id = 'kopa_accordions_playlist';
        $this->widget_name = __('(NewLotus) Accordions', 'new-lotus');

        $this->settings = array(
            'popular_title' => array(
                'type' => 'text',
                'std' => __('POPULAR', 'new-lotus'),
                'label' => __('Popular title', 'new-lotus')
            ),
            'comment_title' => array(
                'type' => 'text',
                'std' => __('COMMENT', 'new-lotus'),
                'label' => __('Comment title', 'new-lotus')
            ),
            'latest_title' => array(
                'type' => 'text',
                'std' => __('RECENT', 'new-lotus'),
                'label' => __('Latest title', 'new-lotus')
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

    public function widget($args, $instance) {

        if ($this->get_cached_widget($args))
            return;

        ob_start();
        extract($args);

        $number_id = rand();
        echo $before_widget;

        $tab_args = array();


        if ($instance['popular_title']) {
            $tab_args[] = array(
                'label' => $instance['popular_title'],
                'orderby' => 'popular',
            );
        }

        if ($instance['latest_title']) {
            $tab_args[] = array(
                'label' => $instance['latest_title'],
                'orderby' => 'date',
            );
        }

        if ($instance['comment_title']) {
            $tab_args[] = array(
                'label' => $instance['comment_title'],
                'orderby' => 'comment_count',
            );
        }
        if (!empty($tab_args)) {
            ?>
        <div class="widget-content">
            <div class="panel-group kopa-accordion" id="accordion-<?php echo $number_id; ?>">
                <?php
                foreach ($tab_args as $key => $tab) {
                    $instance['orderby'] = $tab['orderby'];
                    $query_args_new = kopa_build_query($instance);
                    $r = new WP_Query($query_args_new);
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion-<?php echo $number_id; ?>" href="#collapse-<?php echo $key; ?>-<?php echo $number_id; ?>"><?php echo $tab['label']; ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse-<?php echo $key; ?>-<?php echo $number_id; ?>" class="panel-collapse collapse">
                            <div class="panel-body">
                                <?php if ($r->have_posts()) { ?>

                                <ul class="list-unstyled">
                                    <?php
                                    while ($r->have_posts()) {
                                        $r->the_post();
                                        ?>
                                        <li class="item">
                                            <a href="<?php the_permalink(); ?>" class="post-thumb img-responsive">
                                                <?php
                                                if (has_post_thumbnail()) {
                                                    echo kopa_the_image(get_the_ID(), get_the_title(), 60, 60, true);
                                                } else {
                                                    echo '<img src="//placehold.it/60x60" alt="no thumbnail">';
                                                }
                                                ?>
                                            </a>
                                            <div class="post-caption">
                                                        <span class="post-meta">
                                                            <span class="post-date"><?php echo get_the_date(get_option('date_format')); ?> </span>
                                                        </span>
                                                <h4 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                            </div>
                                        </li>
                                        <?php } ?>
                                    <?php wp_reset_postdata(); ?>
                                </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <?php
                }
                ?>
            </div>
        </div>
        <?php
        }


        echo $after_widget;
        $content = ob_get_clean();
        echo $content;
        $this->cache_widget($args, $content);
    }

}
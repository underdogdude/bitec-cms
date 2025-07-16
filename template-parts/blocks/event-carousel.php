<?php 
    $args = [
        'post_type'      => 'event',
        'posts_per_page' => 9,
        'post_status'    => 'publish',
    ];
    $query = new WP_Query($args);
    $thai_months = [
        'January' => 'มกราคม',
        'February' => 'กุมภาพันธ์',
        'March' => 'มีนาคม',
        'April' => 'เมษายน',
        'May' => 'พฤษภาคม',
        'June' => 'มิถุนายน',
        'July' => 'กรกฎาคม',
        'August' => 'สิงหาคม',
        'September' => 'กันยายน',
        'October' => 'ตุลาคม',
        'November' => 'พฤศจิกายน',
        'December' => 'ธันวาคม',
    ];
?>
<div id="block-event-carousel"></div>
<?php return; ?>
<div class="wp-block-greenshift-blocks-swiper gs-swiper swiper-event-carousel">
    <div class="gs-swiper-init"
        data-slidesperview="3"
        data-slidesperviewmd="2"
        data-slidesperviewxs="1.1"
        data-spacebetween="30"
        data-spacebetweenmd="16"
        data-spacebetweenxs="16"
        data-slidesoffsetafter="20"
        data-loop="false"
        data-speed="400"
        data-vertical="false"
        data-autoheight="false"
        data-grabcursor="false"
        data-freemode="false"
        data-centered="false"
        data-autoplay="false"
        data-coverflowshadow="false">
         
        <div class="swiper">
            <div class="swiper-wrapper">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <?php
                        $event_startdate = get_field('event_startdate', get_the_ID());
                        $event_enddate = get_field('event_enddate', get_the_ID());
                        $event_hall = get_field('event_hall', get_the_ID());

                        // Slug
                        $post_slug = get_post_field('post_name', get_the_ID());
                        $post_type = get_post_type(get_the_ID());
                        $current_language = apply_filters( 'wpml_current_language', null );
                        if ($current_language == 'th') {
                            $href = '/' . $current_language . '/' . $post_type . '/' . $post_slug;
                            
                        }else { 
                            $href = '/' . $post_type . '/' . $post_slug;
                        }
                      
                    ?>
                    <div class="swiper-slide">
                        <div class="swiper-slide-inner">
                           <article id="post-<?php the_ID(); ?>" <?php post_class('content-item -card'); ?>>
                                <div class="pic">
                                    <a href="<?php echo $href; ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark">
                                        <?php if(has_post_thumbnail()) { the_post_thumbnail('full');} else { echo '<img src="' . esc_url( get_template_directory_uri()) .'/img/thumb.jpg" alt="'. get_the_title() .'" />'; }?>
                                    </a>
                                </div>
                                <div class="info">
                                    <div class="entry-meta">

                                        <?php 
                                            $today = date('Y-m-d');
                                            if ($event_startdate && $event_enddate) {
                                                $start_date = date('Y-m-d', strtotime($event_startdate));
                                                $end_date = date('Y-m-d', strtotime($event_enddate));
                                                if ($today >= $start_date && $today <= $end_date) {
                                                    echo '<div class="happening-now-label">';
                                                    echo '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"> <circle cx="9" cy="9" r="9" fill="#FAE7EA"/> <circle cx="9" cy="9" r="6" fill="#E8909E"/> <circle cx="9" cy="9" r="3" fill="#CE0E2D"/> </svg>';
                                                    echo __('Happening Now', 'wpml_theme');
                                                    echo '</div>';
                                                }
                                            } elseif ($event_startdate && !$event_enddate) {
                                                $start_date = date('Y-m-d', strtotime($event_startdate));
                                                if ($today === $start_date) {
                                                    echo '<div class="happening-now-label">';
                                                    echo '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"> <circle cx="9" cy="9" r="9" fill="#FAE7EA"/> <circle cx="9" cy="9" r="6" fill="#E8909E"/> <circle cx="9" cy="9" r="3" fill="#CE0E2D"/> </svg>';
                                                    echo __('Happening Now', 'wpml_theme');
                                                    echo '</div>';
                                                }
                                            }
                                        ?>
                                        <?php
                                            $terms = get_the_terms(get_the_ID(), 'event-category');
                                            if ($terms && !is_wp_error($terms)) {
                                                echo '<div class="event-categories">';
                                                foreach ($terms as $term) {
                                                    echo '<div class="event-category">' . esc_html($term->name) . '</div> ';
                                                }
                                                echo '</div>';
                                            }
                                        ?>
                                    </div>
                                    <header class="entry-header">
                                         <h2 class="entry-title">
                                            <a href="<?php echo esc_url($href); ?>" rel="bookmark"><?php the_title(); ?></a>
                                        </h2>
                                    </header>
                                    <div class="entry-date">
                                        <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6.25 8.58325H4.41667V10.4166H6.25V8.58325ZM9.91667 8.58325H8.08333V10.4166H9.91667V8.58325ZM13.5833 8.58325H11.75V10.4166H13.5833V8.58325ZM15.4167 2.16659H14.5V0.333252H12.6667V2.16659H5.33333V0.333252H3.5V2.16659H2.58333C1.56583 2.16659 0.759167 2.99159 0.759167 3.99992L0.75 16.8333C0.75 17.8416 1.56583 18.6666 2.58333 18.6666H15.4167C16.425 18.6666 17.25 17.8416 17.25 16.8333V3.99992C17.25 2.99159 16.425 2.16659 15.4167 2.16659ZM15.4167 16.8333H2.58333V6.74992H15.4167V16.8333Z" fill="#CE0E2D"/>
                                        </svg>
                                        <?php 
                                            if ($event_startdate) {
                                                $start_date = DateTime::createFromFormat('Y-m-d H:i:s', $event_startdate);
                                                $start_day = $start_date->format('j');
                                                $start_month = $start_date->format('F');
                                                $start_year = $start_date->format('Y');

                                                $is_thai = defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE === 'th';
                                                $start_month = $is_thai ? $thai_months[$start_month] : $start_month;


                                                if ($event_enddate) {
                                                    $end_date = DateTime::createFromFormat('Y-m-d H:i:s', $event_enddate);
                                                    $end_day = $end_date->format('j');
                                                    $end_month = $end_date->format('F');
                                                    $end_year = $end_date->format('Y');

                                                    $end_month = $is_thai ? $thai_months[$end_month] : $end_month;

                                                    // Same month & year → 22 – 30 March 2025
                                                    if ($start_month === $end_month && $start_year === $end_year) {
                                                        echo '<div class="event-date">' . $start_day . ' – ' . $end_day . ' ' . $start_month . ' ' . $start_year . '</div>';
                                                    }
                                                    // Same year, different month → 28 Feb – 2 Mar 2025
                                                    elseif ($start_year === $end_year) {
                                                        echo '<div class="event-date">' . $start_day . ' ' . $start_month . ' – ' . $end_day . ' ' . $end_month . ' ' . $start_year . '</div>';
                                                    }
                                                    // Different years → 28 Dec 2024 – 2 Jan 2025
                                                    else {
                                                        echo '<div class="event-date">' . $start_day . ' ' . $start_month . ' ' . $start_year . ' – ' . $end_day . ' ' . $end_month . ' ' . $end_year . '</div>';
                                                    }
                                                } else {
                                                    // Only start date available → 22 March 2025
                                                    echo '<div class="event-date">' . $start_day . ' ' . $start_month . ' ' . $start_year . '</div>';
                                                }
                                            }
                                        ?>
                                    </div>

                                    <div class="entry-location">
                                        <?php
                                            $terms = get_the_terms(get_the_ID(), 'event-location');
                                            $color = "";
                                            if ($terms && !is_wp_error($terms)) {
                                                echo '<div class="event-locations">';
                                                foreach ($terms as $term) {
                                                    $color = get_field('event_location_color', 'term_' . $term->term_id)?get_field('event_location_color', 'term_' . $term->term_id): "#CE0E2D" ;
                                                    echo '<div class="event-location" style="background-color: '. $color .' ">' . esc_html($term->name) . '</div> ';
                                                }
                                                echo '</div>';
                                            }
                                        ?>
                                        <?php 
                                            if( $event_hall ) { 
                                                echo '<div class="event-hall" style="color: '. $color .'">';
                                                echo $event_hall;
                                                echo '</div>';
                                            }
                                        ?>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <div class="swiper-button-prev">
            <svg width="14" height="15" viewBox="0 0 14 15"  fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.6666 6.66671H3.52492L8.18325 2.00837L6.99992 0.833374L0.333252 7.50004L6.99992 14.1667L8.17492 12.9917L3.52492 8.33337H13.6666V6.66671Z"/>
            </svg>
        </div>
        <div class="swiper-button-next">
            <svg width="14" height="15" viewBox="0 0 14 15"  fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.99992 0.833374L5.82492 2.00837L10.4749 6.66671H0.333252V8.33337H10.4749L5.82492 12.9917L6.99992 14.1667L13.6666 7.50004L6.99992 0.833374Z"/>
            </svg>
        </div>
    </div>
</div>
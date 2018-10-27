<div class="event-summary">
    <a class="event-summary__date t-center" href="#">
    <!-- 
        the_field is a function provided by the plugin Advanced Custom Fields ( ACF ). event-date is a custom field built within that 
        plugin. 
        However, that's only if we use it independently, and display the entire date format represented within our Custom Field. If we
        only want a portion of the Custom Fields data we can use the php DateTime class, and pass the ACF fucntion 'get_field' to return
        the string of data, and then echo out formatted text.
    -->
    <span class="event-summary__month"><?php 
        $eventDate = new DateTime(get_field('event_date'));
        echo $eventDate->format('M');
    ?></span>
    <!-- Taking our work from above, and formatting the day. D for day name (Fri) d for day number (20th) -->
    <span class="event-summary__day"><?php echo $eventDate->format('d'); ?></span>  
    </a>
    <div class="event-summary__content">
    <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
    <!-- -->
    <p><?php if(has_excerpt()){ 
        echo get_the_excerpt();
        }else {
        echo wp_trim_words(get_the_content(), 18);
        }  ?><a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
    </div>
</div>
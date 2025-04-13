
<?php
/**
 * Determines the difference between two timestamps.
 *
 * The difference is returned in a human readable format such as "1 hour",
 * "5 mins", "2 days".
 *
 * @since 1.5.0
 *
 * @param int $from Unix timestamp from which the difference begins.
 * @param int $to   Optional. Unix timestamp to end the time difference. Default becomes time() if not set.
 * @return string Human readable time difference.
 */
if(!function_exists('human_time_diff')){
    function human_time_diff( $from, $to = '' ) {
            if ( empty( $to ) ) {
                    $to = time();
            }
            $minute_in_seconds = 60;
            $hour_in_seconds = 60 * $minute_in_seconds;
            $day_in_seconds = 24 * $hour_in_seconds;
            $week_in_seconds = 7 * $day_in_seconds;
            $month_in_seconds = 30 * $day_in_seconds ;
            $year_in_seconds = 365 * $day_in_seconds;
            $diff = (int) abs( $to - $from );

            if ( $diff < $hour_in_seconds ) {
                    $mins = round( $diff / $minute_in_seconds );
                    if ( $mins <= 1 ) {
                            $mins = 1;
                    }
                    /* translators: Time difference between two dates, in minutes (min=minute). %s: Number of minutes */
                    $since = sprintf( sw_n( '%s min', '%s mins', $mins ), $mins );
            } elseif ( $diff < $day_in_seconds && $diff >= $hour_in_seconds ) {
                    $hours = round( $diff / $hour_in_seconds );
                    if ( $hours <= 1 ) {
                            $hours = 1;
                    }
                    /* translators: Time difference between two dates, in hours. %s: Number of hours */
                    $since = sprintf( sw_n( '%s hour', '%s hours', $hours ), $hours );
            } elseif ( $diff < $month_in_seconds && $diff >= $day_in_seconds ) {
                    $days = round( $diff / $day_in_seconds );
                    if ( $days <= 1 ) {
                            $days = 1;
                    }
                    /* translators: Time difference between two dates, in days. %s: Number of days */
                    $since = sprintf( sw_n( '%s day', '%s days', $days ), $days );
            } elseif ( $diff < $month_in_seconds && $diff >= $month_in_seconds ) {
                    $weeks = round( $diff / $month_in_seconds );
                    if ( $weeks <= 1 ) {
                            $weeks = 1;
                    }
                    /* translators: Time difference between two dates, in weeks. %s: Number of weeks */
                    $since = sprintf( sw_n( '%s week', '%s weeks', $weeks ), $weeks );
            } elseif ( $diff < $year_in_seconds && $diff >= $month_in_seconds ) {
                    $months = round( $diff / $month_in_seconds );
                    if ( $months <= 1 ) {
                            $months = 1;
                    }
                    /* translators: Time difference between two dates, in months. %s: Number of months */
                    $since = sprintf( sw_n( '%s month', '%s months', $months ), $months );
            } elseif ( $diff >= $year_in_seconds ) {
                    $years = round( $diff / $year_in_seconds );
                    if ( $years <= 1 ) {
                            $years = 1;
                    }
                    /* translators: Time difference between two dates, in years. %s: Number of years */
                    $since = sprintf( sw_n( '%s year', '%s years', $years ), $years );
            }

            return $since;
    }
}

if(!function_exists('sw_n')){
    function sw_n ($single = '', $plural = '', $number = '') {
        $string = '';
        if($number==1) {
            $string = $single;
        } else {
            $string = $plural;
        }
        return $string;
    }
}

$similar_estates = array();

$CI =& get_instance();

$where = array();
$where['language_id']  = $lang_id;
$where['is_activated'] = 1;
if(isset($CI->data['settings_listing_expiry_days']))
{
    if(is_numeric($CI->data['settings_listing_expiry_days']) && $CI->data['settings_listing_expiry_days'] > 0)
    {
         $where['property.date_modified >']  = date("Y-m-d H:i:s" , time()-$CI->data['settings_listing_expiry_days']*86400);
    }
}

//where (similar properties) price, purpose, county
if(!empty($estate_data_option_37))
{
    $where['field_37_int >'] = 0.5*(int)$estate_data_option_37;
    $where['field_37_int <'] = 1.5*(int)$estate_data_option_37;
}
    
if(!empty($estate_data_option_36))
{
    $where['field_36_int >'] = 0.5*(int)$estate_data_option_36;
    $where['field_36_int <'] = 1.5*(int)$estate_data_option_36;
}
    
if(!empty($estate_data_option_4))
{
    $where['field_4'] = $estate_data_option_4;
}

$where['is_activated'] = 1;
$where['property.id !='] = $property_id;

$similar_estates = $CI->estate_m->get_by($where, FALSE, 4, 'RAND()', 0, array(), NULL);

$similar_estates_array = array();
$CI->generate_results_array($similar_estates, $similar_estates_array, $options_name);

if(sw_count($similar_estates_array) > 0): ?>

<div class="similar-listings-posts">
    <h3><?php echo lang_check('Similar properties'); ?></h3>
    <div class="list-products">
        <?php foreach($similar_estates_array as $key=>$item): ?>
        <div class="card">
           <a href="<?php echo $item['url']; ?>" title="<?php echo _ch($item['option_10']); ?>">
               <div class="img-block">
                   <div class="overlay"></div>
                   <img src="<?php echo _simg($item['thumbnail_url'], '851x678', true); ?>" alt="<?php echo _ch($item['option_10']); ?>" class="img-fluid">
                   <div class="rate-info">
                       <?php if(!empty($item['option_36']) || !empty($item['option_37'])): ?>
                       <h5>
                       <?php 
                            if(!empty($item['option_37']))echo ' '.show_price($item['option_37'], $options_prefix_37, $options_suffix_37, $lang_id);
                            if(!empty($item['option_37']) && !empty($item['option_36'])) echo ' / ';
                            if(!empty($item['option_36']))echo show_price($item['option_36'], $options_prefix_36, $options_suffix_36, $lang_id);
                       ?>
                       </h5>
                       <?php endif; ?>
                        <?php if(_ch($item['option_4'], false)):?>
                            <span class="purpose-<?php $a='';$a=strtolower($item['option_4']);echo url_title_cro( str_replace(' ','_',$a)); ?>"><?php echo _ch($item['option_4'], ''); ?></span>
                        <?php endif;?>
                   </div>
               </div>
           </a>
           <div class="card_bod_full">
               <div class="card-body">
                   <a href="<?php echo $item['url']; ?>" title="<?php echo _ch($item['option_10']); ?>">
                       <h3><?php echo _ch($item['option_10']); ?></h3>
                       <p><i class="la la-map-marker"></i><?php _che($item['address']); ?></p>
                   </a>
                   <ul>
                       <?php
                           $custom_elements = _get_custom_items();
                           $i=0;
                           if(sw_count($custom_elements) > 0):
                           foreach($custom_elements as $key=>$elem):

                           if(!empty($item['option_'.$elem->f_id]) && $i++<3)
                           if($elem->type == 'DROPDOWN' || $elem->type == 'INPUTBOX'):
                            ?>
                               <li class=""><i class="fa <?php _che($elem->f_class); ?>"></i><small><?php echo _ch($item['option_'.$elem->f_id], '-'); ?> <?php echo _ch(${"options_suffix_$elem->f_id"}, ''); ?> <span style="<?php _che($elem->f_style); ?>"><?php echo _ch(${"optionssw_name_$elem->f_id"}, '-'); ?></span></li>
                            <?php 
                           elseif($elem->type == 'CHECKBOX'):
                            ?>
                               <li class=""><i class="fa <?php _che($elem->f_class); ?>"></i><span class="<?php echo (!empty($item['option_'.$elem->f_id])) ? 'glyphicon glyphicon-ok':'glyphicon glyphicon-remove';  ?>"></span> <?php echo _ch(${"optionssw_name_$elem->f_id"}, '-'); ?></li>
                            <?php 
                           endif;                    
                           endforeach;  
                           else:
                       ?>
                       <li class=""><?php echo _ch($item['option_19'], '-'); ?> <?php echo _ch($options_name_19, '-'); ?></li>
                       <li class=""><?php echo _ch($item['option_20'], '-'); ?> <?php echo _ch($options_name_20, '-'); ?></li>
                       <li class=""><?php echo _ch($item['option_57'], '-'); ?> <?php echo _ch($options_name_57, '-'); ?></li>
                       <?php endif; ?>
                   </ul>
               </div>
               <div class="card-footer">
                   <div class="crd-links">
                       <span class="favorites-actions pull-left">
                           <a href="#" data-id="<?php echo _ch($item['id']); ?>" class="add-to-favorites" style="<?php echo ($item['is_favorite'])?'display:none;':''; ?>">
                               <i class="la la-heart-o"></i>
                           </a>
                           <a href="#" data-id="<?php echo _ch($item['id']); ?>" class="remove-from-favorites" style="<?php echo (!$item['is_favorite'])?'display:none;':''; ?>">
                               <i class="la la-heart-o"></i>
                           </a>
                           <i class="fa fa-spinner fa-spin fa-custom-ajax-indicator"></i>
                       </span>
                       <a href="#" class="plf" title='<?php echo _ch($item['date']); ?>'>
                           <i class="la la-calendar-check-o"></i> 
                           <?php 
                               $date_modified = $item['date'];
                               $date_modified_str = strtotime($date_modified);
                               echo human_time_diff($date_modified_str);
                           ?>
                       </a>
                   </div><!--crd-links end-->
                   <a href="<?php echo $item['url']; ?>" title="<?php echo _ch($item['option_10']); ?>" class="btn-default"><?php echo lang_check('View Details');?></a>
               </div>
           </div><!--card_bod_full end-->
           <a href="<?php echo $item['url']; ?>" title="<?php echo _ch($item['option_10']); ?>" class="ext-link"></a>
       </div><!--card end-->
        <?php endforeach;?>
    </div><!-- list-products end-->
</div><!--similar-listings-posts end-->
<?php endif;?>
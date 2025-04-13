<?php
/*
Widget-title: Footer menu
Widget-preview-image: /assets/img/widgets_preview/footer_menu.webp
 */
?>

<div class="col-xl-3 col-sm-6 col-md-3 widget_edit_enabled">
    <div class="bottom-list">
        <h3><?php echo lang_check('Helpful Links');?></h3>
        <?php
        if (!function_exists('get_menu_custom_small')) {
            //menu generate function
            function get_menu_custom_small($array, $lang_code) {
                $CI = & get_instance();
                $str = '';
                $is_logged_user = ($CI->user_m->loggedin() == TRUE);
                if (sw_count($array)) {
                    $str .= '<ul class="list-f">' . PHP_EOL;
                    $position = 0;
                    foreach ($array as $key => $item) {
                        $position++;
                        if($position >4) break;
                        $active = '';
                        if ($item['is_visible'] == '1')
                            if (empty($item['is_private']) || $item['is_private'] == '1' && $is_logged_user)
                                $href = slug_url($lang_code . '/' . $item['id'] . '/' . url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
                        if (substr($item['keywords'], 0, 4) == 'http')
                            $href = $item['keywords'];
                        if (substr($item['keywords'], 0, 6) == 'search') {
                            $href = $href . '?' . $item['keywords'];
                            $href = str_replace('"', '%22', $href);
                        }
                        $target = '';
                        if (substr($item['keywords'], 0, 4) == 'http') {
                            $href = $item['keywords'];
                            if (substr($item['keywords'], 0, 10) != substr(site_url(), 0, 10)) {
                                $target = ' target="_blank"';
                            }
                        }
                        if ($item['keywords'] == '#')
                            $href = '#';
                        $str .= '<li>';
                        $str .= '<a href="' . $href . '" ' . $target . '>' . $item['navigation_title'] . '</a>';
                        $str .= '</li>' . PHP_EOL;
                    }
                    $str .= '</ul>' . PHP_EOL;
                }
                return $str;
            }
        }
        ?>
        <?php
        $CI = & get_instance();
        echo get_menu_custom_small($CI->temp_data['menu'], $lang_code);
        ?>
    </div><!--bottom-list end-->
</div>
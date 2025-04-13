<?php
if ( ! function_exists('get_menu_custom'))
{
//menu generate function
function get_menu_custom ($array, $child = FALSE, $lang_code='en')
{
      $CI =& get_instance();
      $str = '';

  $is_logged_user = ($CI->user_m->loggedin() == TRUE);

      if (sw_count($array)) {
          $str .= $child == FALSE ? '<ul class="navbar-nav mr-auto" id="main-menu">' . PHP_EOL : '<div class="dropdown-menu animated">' . PHP_EOL;
                              $position = 0;
              foreach ($array as $key=>$item) {
                      $position++;

          $active = $CI->uri->segment(2) == url_title_cro($item['id'], '-', TRUE) ? TRUE : FALSE;

          if($position == 1 && $child == FALSE){
              //$item['navigation_title'] = '<img src="assets/img/home-icon.png" alt="'.$item['navigation_title'].'" />';

              if($CI->uri->segment(2) == '')
                  $active = TRUE;
          }

          if($item['is_visible'] == '1')
          if(empty($item['is_private']) || $item['is_private'] == '1' && $is_logged_user)
                      if (isset($item['children']) && sw_count($item['children'])) {

              $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
              if(substr($item['keywords'],0,4) == 'http')
                  $href = $item['keywords'];

              if(substr($item['keywords'],0,6) == 'search')
              {
                   $href = $href.'?'.$item['keywords'];
                   $href = str_replace('"', '%22', $href);
              }

               $target = '';
               if(substr($item['keywords'],0,4) == 'http')
               {
                   $href = $item['keywords'];
                   if(substr($item['keywords'],0,10) != substr(site_url(),0,10))
                   {
                       $target=' target="_blank"';
                   }
               }

              if($item['keywords'] == '#')
                  $href = '#';

                              $str .= $active ? '<li class="nav-item dropdown active">' : '<li class="nav-item dropdown">';
                              $str .= '<a href="' . $href . '" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" '.$target.'>' . $item['navigation_title'];
                              $str .= '</a>' . PHP_EOL;

                              $str .= get_menu_custom($item['children'], TRUE, $lang_code);
                      }
                      else {

              $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
              if(substr($item['keywords'],0,4) == 'http')
                  $href = $item['keywords'];

              if(substr($item['keywords'],0,6) == 'search')
              {
                   $href = $href.'?'.$item['keywords'];
                   $href = str_replace('"', '%22', $href);
              }

              if(substr($item['keywords'],0,6) == 'search')
              {
                   $href = $href.'?'.$item['keywords'];
                   $href = str_replace('"', '%22', $href);
              }

               $target = '';
               if(substr($item['keywords'],0,4) == 'http')
               {
                   $href = $item['keywords'];
                   if(substr($item['keywords'],0,10) != substr(site_url(),0,10))
                   {
                       $target=' target="_blank"';
                   }
               }

               if(stripos($item['keywords'], '%site_domain$s') !== FALSE)
               {
                   $item['keywords'] = str_replace('%site_domain$s', '', $item['keywords']);
                   $item['keywords'] = str_replace('/index.php', '', $item['keywords']);
                   $href = site_url($item['keywords']);
               }

              if($item['keywords'] == '#')
                  $href = '#';
                           $class= $active ? 'active' : '';
                           $str .= $child==FALSE ? '<li class="nav-item '.$class.'">' : '';
                           $class= $active ? 'active' : '';
                           $class2 = $child==FALSE ? 'nav-link ' : 'dropdown-item ';
                           $str .= '<a href="' . $href . '" class="'.$class2.' '.$class.'" '.$target.'>' . $item['navigation_title'] . '</a>';
                           $str .= $child==FALSE ? '</li>'. PHP_EOL : ''; 
                      }
              }
               $str .= $child == FALSE ? '</ul>' . PHP_EOL : '</div>' . PHP_EOL;
      }

      return $str;
}
}
?>
<?php
  $CI =& get_instance();
  echo get_menu_custom($CI->temp_data['menu'], FALSE, $lang_code);
?>
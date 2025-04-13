<?php
 $lang_array = $this->language_m->get_array_by(array('is_frontend'=>1));
 if(sw_count($lang_array) > 1):
?> 

<div class="language-selector">
    <div class="drop-menu links">
        <?php   
        $flag_icon = '';
        if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/img/flags/'.$this->data['lang_code'].'.webp'))
        {
            if(sw_is_safari()) {
                $flag_icon = '&nbsp; <img class="flag-icon" src="'.'assets/img/flags/'.$this->data['lang_code'].'.png" alt="'.$this->data['lang_code'].'" />';
            } else {
                $flag_icon = '&nbsp; <img class="flag-icon" src="'.'assets/img/flags/'.$this->data['lang_code'].'.webp" alt="'.$this->data['lang_code'].'" />';
            }
        }

        if ( ! function_exists('get_lang_menu_custom'))
        {
        function get_lang_menu_custom($array, $lang_code, $extra_ul_attributes = '')
            {
                $CI =& get_instance();
                $property_data = NULL;

                if(sw_count($array) == 1)
                    return '';

                if(empty($CI->data['listing_uri']))
                {
                    $listing_uri = 'property';
                }
                else
                {
                    $listing_uri = $CI->data['listing_uri'];
                }

                $default_base_url = config_item('base_url');
                $default_lang_code = $CI->language_m ->get_default();
                $first_page = $CI->page_m->get_first();

                $str = '<ul '.$extra_ul_attributes.'>';
                foreach ($array as $item) {
                    $active = $lang_code == $item['code'] ? TRUE : FALSE;

                    $custom_domain_enabled=false;
                    if(config_db_item('multi_domains_enabled') === TRUE)
                    {
                        if(!empty($item['domain']) && substr_count($item['domain'], 'http') > 0)
                        {
                            $custom_domain_enabled=true;
                            $CI->config->set_item('base_url', $item['domain']);
                        }
                        else
                        {
                            $CI->config->set_item('base_url', $default_base_url);
                        }
                    }

                    $flag_icon = '';

                    if(isset($CI->data['settings_template']))
                    {
                        $template_name = $CI->data['settings_template'];
                        if(file_exists(FCPATH.'templates/'.$template_name.'/assets/img/flags/'.$item['code'].'.webp'))
                        {
                            if(sw_is_safari()) {
                                $flag_icon = '&nbsp; <img src="'.'assets/img/flags/'.$item['code'].'.png" alt="'.$item['code'].'" />';
                            } else {
                                $flag_icon = '&nbsp; <img src="'.'assets/img/flags/'.$item['code'].'.webp" alt="'.$item['code'].'" />';
                            }
                        }
                    }

                    if($CI->uri->segment(1) == $listing_uri)
                    {
                        if($active)
                        {
                            $str.='<li class="'.$item['code'].'">'.anchor(slug_url($listing_uri.'/'.$CI->uri->segment(2).'/'.$item['code'].'/'.$CI->uri->segment(4)), $flag_icon.$item['language'], 'class="dropdown-item"').'</li>';
                        }
                        else
                        {
                            $property_title = '';
                            if($property_data === NULL)
                                $property_data = $CI->estate_m->get_dynamic($CI->uri->segment(2));

                            if(isset($property_data->{'option10_'.$item['id']}))
                                $property_title = $property_data->{'option10_'.$item['id']};

                            $str.='<li class="'.$item['code'].'" >'.anchor(slug_url($listing_uri.'/'.$CI->uri->segment(2).'/'.$item['code'].'/'.url_title_cro($property_title)), $flag_icon.$item['language'], 'class="dropdown-item"').'</li>';
                        }
                    }
                    else if($CI->uri->segment(1) == 'showroom')
                    {
                        if($active)
                        {
                            $str.='<li class="'.$item['code'].'">'.anchor('showroom/'.$CI->uri->segment(2).'/'.$item['code'], $flag_icon.$item['language'], 'class="dropdown-item"').'</li>';
                        }
                        else
                        {
                            $str.='<li class="'.$item['code'].'">'.anchor('showroom/'.$CI->uri->segment(2).'/'.$item['code'], $flag_icon.$item['language'], 'class="dropdown-item"').'</li>';
                        }
                    }
                    else if($CI->uri->segment(1) == 'profile')
                    {
                        if($active)
                        {
                            $str.='<li class="'.$item['code'].'">'.anchor(slug_url('profile/'.$CI->uri->segment(2).'/'.$item['code']), $flag_icon.$item['language'], 'class="dropdown-item"').'</li>';
                        }
                        else
                        {
                            $str.='<li class="'.$item['code'].'">'.anchor(slug_url('profile/'.$CI->uri->segment(2).'/'.$item['code']), $flag_icon.$item['language'], 'class="dropdown-item"').'</li>';
                        }
                    }
                    else if($CI->uri->segment(1) == 'propertycompare')
                    {
                        if($active)
                        {
                            $str.='<li class="'.$item['code'].'">'.anchor(slug_url('propertycompare/'.$CI->uri->segment(2).'/'.$item['code']), $flag_icon.$item['language'], 'class="dropdown-item"').'</li>';
                        }
                        else
                        {
                            $str.='<li class="'.$item['code'].'">'.anchor(slug_url('propertycompare/'.$CI->uri->segment(2).'/'.$item['code']), $flag_icon.$item['language'], 'class="dropdown-item"').'</li>';
                        }
                    }
                    else if($CI->uri->segment(1) == 'treefield')
                    {
                        if($active)
                        {
                            $str.='<li class="'.$item['code'].'">'.anchor(slug_url('treefield/'.$item['code'].'/'.$CI->uri->segment(3).'/'.$CI->uri->segment(4)), $flag_icon.$item['language'], 'class="dropdown-item"').'</li>';
                        }
                        else
                        {
                            $str.='<li class="'.$item['code'].'">'.anchor(slug_url('treefield/'.$item['code'].'/'.$CI->uri->segment(3).'/'.$CI->uri->segment(4)), $flag_icon.$item['language'], 'class="dropdown-item"').'</li>';
                        }
                    }
                    else if(is_numeric($CI->uri->segment(2)))
                    {
                        if($active)
                        {
                            $str.='<li class="'.$item['code'].'">'.anchor(slug_url($item['code'].'/'.$CI->uri->segment(2), 'page_m'), $flag_icon.$item['language'], 'class="dropdown-item"').'</li>';
                        }
                        else
                        {
                            $str.='<li class="'.$item['code'].'">'.anchor(slug_url($item['code'].'/'.$CI->uri->segment(2), 'page_m'), $flag_icon.$item['language'], 'class="dropdown-item"').'</li>';
                        }
                    }
                    else if($CI->uri->segment(2) != '')
                    {
                        if($active)
                        {
                            $str.='<li class="'.$item['code'].'">'.anchor($CI->uri->segment(1).'/'.$CI->uri->segment(2).'/'.$item['code'].'/'.$CI->uri->segment(4), $flag_icon.$item['language'], 'class="dropdown-item"').'</li>';
                        }
                        else
                        {
                            $str.='<li class="'.$item['code'].'">'.anchor($CI->uri->segment(1).'/'.$CI->uri->segment(2).'/'.$item['code'].'/'.$CI->uri->segment(4), $flag_icon.$item['language'], 'class="dropdown-item"').'</li>';
                        }
                    }
                    else
                    {
                        $url_lang_code = $item['code'];
                        if($custom_domain_enabled)
                        {
                            $url_lang_code='';
                        }
                        else if($default_lang_code == $item['code'])
                        {
                            $url_lang_code = base_url();
                        }

                        if($active)
                        {
                            $str.='<li class="'.$item['code'].'">'.anchor($url_lang_code, $flag_icon.$item['language'], 'class="dropdown-item"').'</li>';
                        }
                        else
                        {
                            $str.='<li class="'.$item['code'].'">'.anchor($url_lang_code, $flag_icon.$item['language'], 'class="dropdown-item"').'</li>';
                        }
                    }
                }
                $str.='</ul>';

                $CI->config->set_item('base_url', $default_base_url);

                return $str;
            }
        }
        ?>
        <div class="select">
            <span>
                <?php
                    if(sw_is_safari()) {
                        $flag_icon = 'assets/img/flags/'.$this->data['lang_code'].'.png';
                    } else {
                        $flag_icon = 'assets/img/flags/'.$this->data['lang_code'].'.webp';
                    }
                ?>
                <img src="<?php echo $flag_icon; ?>" alt="<?php echo $this->data['lang_code']; ?>"> 
                
                <?php 
                    $CI =& get_instance();
                    echo $CI->language_m->get_name($this->data['lang_code']);
                ?>
            </span>
            <i class="la la-caret-down"></i>
        </div>
        <?php 
          echo get_lang_menu_custom($this->language_m->get_array_by(array('is_frontend'=>1)), $this->data['lang_code'], 'class="dropeddown" style="display: none;"');
        ?>
    </div>
</div><!-- /.lang menu -->
<?php endif;?>

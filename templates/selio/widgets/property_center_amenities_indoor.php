 <?php if(isset($category_options_21) && $category_options_count_21>0): ?>
<div class="features-dv">
    <h3><?php echo ${'options_name_21'};?></h3>
    <form class="form_field">
        <ul>
            <?php
            $CI = &get_instance();
            $CI->load->model('option_m');
            $options_category = $CI->option_m->get_by(array('parent_id'=>21));
            ?>
            <?php if(isset($category_options_21))foreach($category_options_21 as $key=>$val): ?>
                <?php
                $checked ='';
                if(isset($val['option_value']))
                    $checked ='checked';
                ?>

                <?php if(isset($val['option_value'])): ?>
                <li class="input-field">
                    <input type="checkbox" name="cc" id="c21<?php echo $key;?>" <?php echo $checked;?>>
                    <label for="c21<?php echo $key;?>">
                        <span></span>
                        <small><?php echo $val['option_name'];?> <?php _che($val['icon']);?></small>
                    </label>
                </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </form>
</div><!--features-dv end-->
<?php endif;?>
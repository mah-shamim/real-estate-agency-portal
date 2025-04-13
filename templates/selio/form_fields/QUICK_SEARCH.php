<?php

if(isset($_GET['search']))$search_json = json_decode($_GET['search']);
$search_query = '';
if(isset($search_json->v_search_option_quick))
{
    $search_query=$search_json->v_search_option_quick;
}

if(function_exists('sw_filter_search_slidetoggle')) 
sw_filter_search_slidetoggle();
?>

<div class="form_field">
    <div class="form-group">
        <input id="search_option_quick" type="text" class="form-control" value="<?php echo $search_query;?>" placeholder="<?php echo lang_check('Quick search');?>" autocomplete="off" />
    </div>
</div>
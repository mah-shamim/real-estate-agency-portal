{has_no_results}
<div class="list_products">
    <div class="alert alert-info" role="alert"><?php echo lang_check('Results not found'); ?></div>
</div>
{/has_no_results}
<?php if(!empty($results)):?>
    {has_view_grid}      
    <div class="list_products">
        <div class="row">
        <?php foreach($results as $key=>$item): ?>
            <?php _generate_results_item(array('key'=>$key, 'item'=>$item, 'custom_class'=>'')); ?>
        <?php endforeach;?>
        </div>
    </div>
    {/has_view_grid}
    {has_view_list}
        <?php _widget('results_list');?>
    {/has_view_list}
    <nav aria-label="Page navigation example" class="pagination properties">
        {pagination_links}
    </nav><!--pagination end-->

<?php endif;?>
<div class="result_preload_indic"></div>

<script>
$('.result_preload_indic').hide();
$('#results_conteiner').closest('.widget-recentproperties').removeClass('hidden');
</script>
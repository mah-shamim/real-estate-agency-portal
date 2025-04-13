<?php if(isset($page_documents) && !empty($page_documents)):?>
<div class="descp-text">
        <h3><?php echo lang_check('Documents files'); ?></h3>
        <ul class="documents_list">  
            <?php foreach ($page_documents as $val):?>
            <li>
                <a href="<?php _che($val->url);?>">
                    <?php if(file_exists(FCPATH.'/templates/'.$settings_template.'/assets/img/icons/filetype/'.get_file_extension($val->filename).'.png')):?>
                    <img src="assets/img/icons/filetype/<?php echo get_file_extension($val->filename);?>.png"/>
                    <?php endif;?>
                    <?php _che($val->filename);?>
                </a>
            </li>
            <?php endforeach;?>
        </ul>
</div><!-- /. widget-gallery -->  
<?php endif; ?>
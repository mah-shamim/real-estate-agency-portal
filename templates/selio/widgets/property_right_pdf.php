<?php if(file_exists(APPPATH.'libraries/Pdf.php')) : ?>
<div class="widget widget-posts text-left">
    <a class='text-center block' href='<?php echo site_url('api/pdf_export/'.$property_id.'/'.$lang_code) ;?>'>
        <img src='assets/img/icons/filetype/pdf.png' alt="<?php echo lang_check('Pdf Export');?>"/>
    </a>
</div><!--widget-posts end-->
<?php endif;?>
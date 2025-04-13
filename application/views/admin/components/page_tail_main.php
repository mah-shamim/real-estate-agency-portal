<!-- Notification box ends -->   

<!-- Scroll to top -->
<span class="totop"><a href="#"><i class="icon-chevron-up"></i></a></span>

<?php if(true):?>
<div id="modal-gallery" class="modal modal-gallery fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="modal-image"></div>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary modal-next"><?php echo lang('next')?> <i class="icon-arrow-right icon-white"></i></a>
        <a class="btn btn-info modal-prev"><i class="icon-arrow-left icon-white"></i> <?php echo lang('previous')?></a>
        <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000"><i class="icon-play icon-white"></i> <?php echo lang('slideshow')?></a>
        <a class="btn modal-download" target="_blank"><i class="icon-download"></i> <?php echo lang('download')?></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif;?>

<!-- The Gallery as lightbox dialog, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">&lsaquo;</a>
    <a class="next">&rsaquo;</a>
    <a class="close">&times;</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

<?php
session_start();
$user_prefix = '1';
if($this->session->userdata('id'))
    $user_prefix = $this->session->userdata('id');

$module_prefix = 'other';
if(strpos(current_url(), 'page') !== FALSE) {
    $module_prefix = 'page';
    if(isset($page) && !empty($page->id)) {
        $module_prefix.='/'.$page->id;
    } else {
        $module_prefix.='/other';
    }
} elseif(strpos(current_url(), 'estate') !== FALSE) {
    $module_prefix = 'listing';
    if(isset($estate) && !empty($estate->id)) {
        $module_prefix.='/'.$estate->id;
    } else {
        $module_prefix.='/other';
    }
}

$path = 'files/wysiwyg/'.$module_prefix.'/';
if (!file_exists(FCPATH.$path)){
    mkdir(FCPATH.$path, 0777, true);
}

$_SESSION['KCFINDER'] = array();     
$_SESSION['KCFINDER']['uploadURL'] = base_url($path);
$_SESSION['KCFINDER']['uploadDir'] = FCPATH.$path;

?>

<script src="<?php echo base_url('admin-assets/js/ckeditor_4.6.2_standard/ckeditor/ckeditor.js')?>"></script>
<script>
CKEDITOR.config.contentsCss =[ "<?php echo base_url('templates/'.$settings['template'].'/assets/css/custom.css')?>" <?php if(isset($template_css) && !empty($template_css)):?>, "<?php echo $template_css?>"<?php endif;?> ];  
CKEDITOR.config.baseHref =  "<?php echo base_url('templates/'.$settings['template'])?>/"
CKEDITOR.addCss(
            '.row {' +
                'margin-left: 0;' +
                'margin-right: 0' +
            '}' +
            '.row {' +    
                'display: table;' +
                'content: "";' +
                'clear: both;' +
            '}'
        );
CKEDITOR.config.forcePasteAsPlainText = false; // default so content won't be manipulated on load
CKEDITOR.config.basicEntities = true;
CKEDITOR.config.entities = true;
CKEDITOR.config.entities_latin = false;
CKEDITOR.config.entities_greek = false;
CKEDITOR.config.entities_processNumerical = false;
CKEDITOR.config.fillEmptyBlocks = function (element) {
        return true; // DON'T DO ANYTHING!!!!!
}; 
CKEDITOR.config.allowedContent = true; // don't filter my data 
CKEDITOR.dtd.a.div = 1;
CKEDITOR.dtd.a.p = 1;
CKEDITOR.config.protectedSource.push(/<i[^>]*><\/i>/g);
CKEDITOR.config.extraAllowedContent = 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}';
CKEDITOR.dtd.$removeEmpty['i'] = false;

   
CKEDITOR.config.filebrowserBrowseUrl = '<?php echo base_url("admin-assets/js/ckeditor_4.6.2_standard/kcfinder/browse.php?type=files");?>';
CKEDITOR.config.filebrowserImageBrowseUrl = '<?php echo base_url("admin-assets/js/ckeditor_4.6.2_standard/kcfinder/browse.php?type=images");?>';
CKEDITOR.config.filebrowserFlashBrowseUrl = '<?php echo base_url("admin-assets/js/ckeditor_4.6.2_standard/kcfinder/browse.php?type=flash");?>';
CKEDITOR.config.filebrowserUploadUrl = '<?php echo base_url("admin-assets/js/ckeditor_4.6.2_standard/kcfinder/filesupload.php?type=files");?>';
CKEDITOR.config.filebrowserImageUploadUrl = '<?php echo base_url("admin-assets/js/ckeditor_4.6.2_standard/kcfinder/upload.php?type=images");?>';
CKEDITOR.config.filebrowserFlashUploadUrl = '<?php echo base_url("admin-assets/js/ckeditor_4.6.2_standard/kcfinder/upload.php?type=flash");?>';

$(document).ready(function(){
    $("form").submit( function(e) {
        if($(this).find('.ckeditor').length){
            var flag_text_error = false;
            $(this).find('.ckeditor').each(function(){
                if($(this).attr('data-required')== 'true') {
                    var id = $(this).attr('id');
                    var messageLength = CKEDITOR.instances[id].getData().replace(/<[^>]*>/gi, '').length;
                    if( !messageLength ) {
                        $('#cke_'+id).addClass('fiedt_error');
                        if( !$('#cke_'+id).closest('div.tab-pane').hasClass('active'))
                        {
                            var attr_id = $('#cke_'+id).closest('div.tab-pane').attr('id');
                            
                            //simulate click on change langauge
                            $('.tabbable .nav.nav-tabs li a[href=#'+attr_id+']').trigger("click");
                        }
                       
                        if(!flag_text_error && $('.tab-pane.active #cke_'+id).length){
                            $('html, body').animate({
                                scrollTop: ($('#cke_'+id).offset().top-200) + 'px'
                            }, 1000, 'swing');
                            flag_text_error = true;
                            console.log( $('#cke_'+id))

                        }
                        e.preventDefault();
                    }
                }
            })
            flag_text_error = false;
        }
    })
    
    if($('.ckeditor').length){
       setTimeout( function() {
            $('.ckeditor').each(function(){
                if($(this).attr('data-required')== 'true') {
                    var id = $(this).attr('id');
                    CKEDITOR.instances[id].on('change', function() {
                        var messageLength = CKEDITOR.instances[id].getData().replace(/<[^>]*>/gi, '').length;
                        if( !messageLength ) {
                            $('#cke_'+id).addClass('fiedt_error');
                        } else {
                            $('#cke_'+id).removeClass('fiedt_error');
                        }
                    });
                }
            })
        }, 1000);
        
    }
})



// *only* if we have anchor on the url
if(typeof location_hash !== 'undefined' && location_hash != '' && $(location_hash).length ) {
    setTimeout( function() { 
        $('html, body').animate({
            scrollTop: ($(location_hash).offset().top-100) + 'px'
        }, 1000, 'swing');
    }, 1000);
    // smooth scroll to the anchor id
}
</script>

</body>
</html>
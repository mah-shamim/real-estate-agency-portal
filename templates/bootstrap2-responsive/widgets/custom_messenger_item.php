<?php if(file_exists(APPPATH.'libraries/Messenger.php') && config_item('private_messages_enabled') !== FALSE):?>
<?php
$CI = & get_instance();
$CI->load->model('enquire_m');
$CI->load->library('messenger');
$all_dialogs = array();

$_all_dialogs = $CI->messenger->get_dialogs(3);
$all_dialogs = $CI->messenger->_generate_dialogs($_all_dialogs, $lang_code);
$dialogs_latest_id = '';
if(!empty($all_dialogs))
    $dialogs_latest_id = $all_dialogs[current(array_keys($all_dialogs))]['id'];

$unreaded_message = '';
if(!empty($all_dialogs))foreach($all_dialogs as $key => $dialog) {
   $unreaded_message += $dialog['unreaded'];
}

?>
<li class="dropdown dropdown-messages">
    <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="fa fa-bell"></i>  <?php echo _l('Messages');?> <span class="new_message <?php echo empty($unreaded_message) ? 'hidden': ''; ?>">(<?php echo _che($unreaded_message);?>)</span></a>
    <div class="dropdown-menu">
        <div class="header color-primary"> <a href='#' class='close-btn' id='close-messenger'><i class="fa fa-close"></i></a></div>
        <div class="header-item">
            <span class='title'><?php echo lang_check('Messages');?></span>
            <span class="link-all">
                <?php if($this->session->userdata('type') == 'USER'):?>
                <a href="<?php echo site_url('fmessages/messenger/');?>"><?php echo lang_check('View All');?></a>
                <?php else:?>
                <a href="<?php echo site_url('/admin/enquire/messenger/');?>"><?php echo lang_check('View All');?></a>
                <?php endif;?>
            </span>
        </div>
        <ul class="dialog-box-list" data-latest_id='<?php _che($dialogs_latest_id);?>'>
            <?php if(!empty($all_dialogs)): foreach($all_dialogs as $key => $dialog):?>
                <li class="dialog-box-item" data-sel_id="<?php _che($dialog['interlocutor_id']);?>"  data-property_id="<?php echo $dialog['property_id'];?>">
                    <div class="image-box">
                        <a href="<?php _che($dialog['interlocutor_url']);?>" class="interlocutor img-circle-cover">
                            <img src="<?php _che($dialog['interlocutor_image_url']);?>" alt="">
                        </a>
                    </div>
                    <div class="body">
                        <div class="name title"><a href="<?php echo site_url($listing_uri.'/'.$dialog['property_id'].'/'.$lang_code);?>"><?php echo $dialog['property_id'].' '.$dialog['p_address']; ?></a></div>
                        <div class="message"><?php echo $dialog['message_chlimit'];?></div>
                        <div class="name"><span class="date"><?php echo $dialog['date_interval'];?></span> · <a href="<?php echo $dialog['interlocutor_url'];?>" class="defaul-hover-primary"><?php echo $dialog['interlocutor_name_surname'];?></a></div>
                    </div>
                    <span class="count focus-color auto-count-dialog <?php echo empty($dialog['unreaded']) ? 'hidden': ''; ?>" data-dialog_key="<?php echo $key;?>">
                        <?php echo $dialog['unreaded'];?>
                    </span>
                </li>
            <?php endforeach;?>
            <?php else:?>  
                <div class="result">
                    <div class="alert">
                        <?php echo lang_check('Messages not find');?>
                    </div>
                </div>
            <?php endif;?>    
        </ul>
    </div>
</li>
                
<script>
    
$('document').ready(function () {
    $('.dropdown-messages .dialog-box-item').click(function(){
        <?php if($this->session->userdata('type') == 'USER'):?>
        location.href="<?php echo site_url('fmessages/messenger/');?>?sel="+$(this).attr('data-sel_id')+"&property_id="+$(this).attr('data-property_id');
        <?php else:?>
        location.href="<?php echo site_url('/admin/enquire/messenger/');?>?sel="+$(this).attr('data-sel_id')+"&property_id="+$(this).attr('data-property_id');
        <?php endif;?>
    })
    
   setInterval(function(){ check_refresh_dialogs_preview (); }, 20000);
    
    $('#close-messenger').on('click', function(e){
        e.preventDefault();
        $('.dropdown.dropdown-messages').removeClass('show');
        
    })
})

function check_refresh_dialogs_preview () {
    var data = new Array();
    var latest_id = $('.dropdown-messages .dialog-box-list').attr('data-latest_id');
    data.push({name: 'latest_id', value: latest_id});
    $.post("{api_private_url}/get_dialogs/{lang_code}/3", data, function(data){
        if(data.success){
            var html='';
            $.each(data.all_dialogs, function(index, value){
                var unreaded = 'hidden';
                if(value.unreaded > 0)
                    unreaded = '';
                
                html += '<li class="dialog-box-item" data-sel_id="'+value.interlocutor_id+'"  data-property_id="'+value.interlocutor_id+'">\n\
                            <div class="image-box">\n\
                                <a href="'+value.interlocutor_url+'" class="interlocutor img-circle-cover">\n\
                                    <img src="'+value.interlocutor_image_url+'" alt="">\n\
                                </a>\n\
                            </div>\n\
                            <div class="body">\n\
                                <div class="name title"><a href="<?php echo site_url($listing_uri);?>/'+value.property_id+'/<?php echo $lang_code;?>">'+value.property_id+' '+value.p_address+'</a></div> \n\
                                <div class="message">'+value.message_chlimit+'</div>\n\
                                <div class="name"><span class="date" title="'+value.date+'">'+value.date_interval+'</span> · <a href="'+value.interlocutor_url+'" class="defaul-hover-primary">'+value.interlocutor_name_surname+'</a></div>\n\
                            </div>\n\
                            <span class="count focus-color auto-count-dialog '+ unreaded +'" data-dialog_key="'+index+'">\n\
                                '+value.unreaded+'\n\
                            </span>\n\
                        </li>';
            })
            
            $('.dropdown-messages .dialog-box-list').html(html);
            $('.dropdown-messages .dialog-box-list').attr('data-latest_id',data.latest_id);
            
            if(data.unreaded_message > 0) {
                $('.dropdown-messages .dropdown-toggle .new_message').removeClass('hidden');
                $('.dropdown-messages .dropdown-toggle .new_message').html('('+data.unreaded_message+')');
            } else {
                $('.dropdown-messages .dropdown-toggle .new_message').addClass('hidden');
            }
            
        }
    }).success(function(){
        $('.dropdown-messages .dialog-box-item').click(function(){
            <?php if($this->session->userdata('type') == 'USER'):?>
            location.href="<?php echo site_url('fmessages/messenger/');?>?sel="+$(this).attr('data-sel_id')+"&property_id="+$(this).attr('data-property_id');
            <?php else:?>
            location.href="<?php echo site_url('/admin/enquire/messenger/');?>?sel="+$(this).attr('data-sel_id')+"&property_id="+$(this).attr('data-property_id');
            <?php endif;?>
       }) 
    });
}
    
</script>

<?php endif;?>
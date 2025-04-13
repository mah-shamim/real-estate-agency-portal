<?php
/*
Widget-title: Right agents
Widget-preview-image: /assets/img/widgets_preview/right_agents.webp
 */
?>

<div class="widget widget-posts widget_edit_enabled widget-right-agents">
    <h3 class="widget-title"><?php echo lang_check('Agents');?></h3>
    <ul>
    <?php if(!empty($paginated_agents)):foreach($paginated_agents as $item): ?>
        <li>
            <div class="contct-info">
                <img src="<?php echo _simg($item['image_url'], '112x89', true); ?>" alt="<?php  _che($item['name_surname']);?>">
                <div class="contct-nf">
                    <h3><a href="<?php echo $item['agent_url']; ?>" title="<?php  _che($item['name_surname']);?>"><?php  _che($item['name_surname']);?></a></h3>
                    <h4><?php  _che($item['address']);?></h4>
                    <?php 
                        $justNums = preg_replace("/[^0-9]/", '',  _ch($item['phone'],'#'));
                    ?>
                    <span><i class="la la-phone"></i><a href="tel://<?php echo $justNums;?>"><?php  _che($item['phone']);?></a></span>
                    <span><i class="la la-envelope-o"></i><a href="mailto:<?php  _che($item['mail']);?>?subject=<?php echo urlencode(lang_check('Estateinqueryfor'));?>:<?php echo urlencode($page_title);?>" title="<?php  _che($item['mail']);?>" class=""><?php  _che($item['mail']);?></a></span>
                </div>
            </div>
        </li>
   <?php endforeach;?>
   <?php else:?>
        <li>
        <div class="alert alert-success">
        <?php echo lang_check('Not found');?>
        </div>
        </li>
   <?php endif;?>
    </ul>
</div><!--widget-posts end-->


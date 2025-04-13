<h2>{lang_Agents}</h2>
   <form class="form-search agents" action="<?php echo current_url().'#content'; ?>" method="get">
   <input name="search-agent" type="text" placeholder="{lang_CityorName}" value="<?php echo $this->input->get('search-agent'); ?>" class="input-medium" />
   <button type="submit" class="btn">{lang_Search}</button>
   </form>
   <?php if(!empty($paginated_agents)):foreach($paginated_agents as $item): ?>
        <div class="agent">
            <div class="image"><img src="<?php echo _simg($item['image_url'], '90x90'); ?>" alt="<?php  _che($item['name_surname']);?>" /></div>
            <div class="name"><a href="<?php  _che($item['agent_url']);?>"><?php  _che($item['name_surname']);?> (<?php  _che($item['total_listings_num']);?>)</a></div>
            <div class="phone"><?php  _che($item['phone']);?></div>
            <div class="mail"><a href="mailto:<?php  _che($item['mail']);?>?subject=<?php echo urlencode(lang_check('Estateinqueryfor'));?>:<?php echo urlencode($page_title);?>"><?php  _che($item['mail']);?></a></div>
        </div>
   <?php endforeach;?>
   <?php else:?>
        <div class="alert alert-success">
        <?php echo lang_check('Not found');?>
        </div>
   <?php endif;?>

    <div class="pagination" style="margin-top: 10px;">
    <?php if(!empty($agents_pagination)):?>
        <?php echo $agents_pagination; ?>
    <?php endif;?>
    </div>
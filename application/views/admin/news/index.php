<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('News')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all news')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/news')?>"><?php echo lang_check('News')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
        
        <div class="row">
            <div class="col-md-12"> 
                <?php echo anchor('admin/news/edit', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang_check('Add news'), 'class="btn btn-primary"')?>
            </div>
        </div>

          <div class="row">

            <div class="col-md-12">

                <div class="widget wblue">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('News')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                        	<th>#</th>
                            <th><?php echo lang('Title');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Date');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Category');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<?php if(check_acl('news/delete')):?><th class="control"><?php echo lang('Delete');?></th><?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($news)): foreach($news as $news_post):?>
                                    <tr>
                                    	<td><?php echo $news_post->id?></td>
                                        <td>
                                        <?php echo anchor('admin/news/edit/'.$news_post->id, $news_post->title)?>
                                        </td>
                                        <td>
                                        <?php echo $news_post->date_publish?>
                                        </td>
                                        <td>
                                        <a href="<?php echo site_url('admin/news/index/'.$news_post->parent_id); ?>" class="label label-danger"><?php echo $categories[$news_post->parent_id]?></a>
                                        </td>
                                    	<td><?php echo btn_edit('admin/news/edit/'.$news_post->id)?></td>
                                    	<?php if(check_acl('news/delete')):?><td><?php echo btn_delete('admin/news/delete/'.$news_post->id)?></td><?php endif;?>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="20"><?php echo lang('We could not find any');?></td>
                                    </tr>
                        <?php endif;?>           
                      </tbody>
                    </table>
                    
                    <div style="text-align: center;"><?php echo $pagination; ?></div>

                  </div>
                </div>
            </div>
          </div>
        </div>
</div>
    
    
    
    
    
</section>
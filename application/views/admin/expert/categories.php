<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Categories')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all categories')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span>
      <a href="<?php echo site_url('admin/expert')?>"><?php echo lang_check('Q&A')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/expert/categories')?>"><?php echo lang_check('Categories')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
        
        <div class="row">
            <div class="col-md-12"> 
                <?php echo anchor('admin/expert/edit_category', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang_check('Add a category'), 'class="btn btn-primary"')?>
            </div>
        </div>

          <div class="row">

            <div class="col-md-12">

                <div class="widget wgreen">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Categories')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                  
                    <!-- Nested Sortable -->
                    <div id="orderResult">
                    <?php echo get_ol_expert_tree($expert_nested)?>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
</div>
    
    
    
    
    
</section>
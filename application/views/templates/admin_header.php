

<nav class="navbar navbar-default" >
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
<!--    <a class="navbar-brand" href="<?php echo site_url('admin/dashboard');?>">Dashboard</a>-->
	<?php echo anchor('admin/dashboard','甜甜圈后台管理系统',array('class'=>'navbar-brand'))?>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">   
 	   <li ><?php echo anchor('admin/users','用户');?></li>
       <li ><?php echo anchor('admin/halls','展馆');?></li>
       <!--
       <li ><?php echo anchor('admin/shops','商户');?></li>
	   <li ><?php echo anchor('admin/districts','地区');?></li>
	   <li ><?php echo anchor('admin/coupontypes','类型');?></li>

    
    --></ul>

    <ul class="nav navbar-nav navbar-right">
  	<li><?php echo anchor('admin/dashboard/logout','登出');?></li>
    </ul>
  </div><!-- /.navbar-collapse -->
</nav>

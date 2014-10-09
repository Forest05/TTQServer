
<h1><?php echo $title;?></h1>
<div class="row"><div class="col-md-10">
<?php 	
		if($this->session->flashdata('msg'))
			echo "<div class='message'>".$this->session->flashdata('msg')."</div>";
		
?>

<!--	 <a href="<?php echo site_url('admin/shops/create');?>" class="btn btn-primary">新建商户</a>-->

	<br/><br/>
	

	<table class='table table-striped table-bordered table-hover table-responsive'>
	<tr><th>编号</th><th>图片</th><th>名称</th><th>地址</th><th>展览</th><th>管理</th></tr>	
	<?php 
	
//	var_dump($models);
	
	foreach ($models as $key => $list) {
		
	?>
		
	<tr>
		<td><?php echo $list['id'];?></td>
		<td><img height='100' src="<?php echo $list['imgUrl'];?>"/></td>
		<td><?php echo $list['name'];?></td>
		<td><?php echo $list['address'];?></td>
		<td><?php echo '展览';?></td>
		<td align="center"><?php echo anchor('admin/halls/edit/'.$list['id'],'编辑');?></td>
	</tr>
		
	<?php }?>	
	</table>
	
	<?php echo $this->pagination->create_links()?>
		
	
	</div>
</div>
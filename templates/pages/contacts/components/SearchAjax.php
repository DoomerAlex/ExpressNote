<?foreach ($result as $value):?>
	<a class="contact-item" href="/user/<?=$value['id']?>">
		<div class="contact-item-img-box">
			<?if ($value['avatar'] != ''):?>
				<img src="/templates/images/avatar-user/<?=$value['avatar']?>" width="100" height="100">
			<?else:?>
				<img src="/templates/images/avatar-user/none.jpg" width="100" height="100">
			<?endif;?>
		</div>
		<div class="contact-item-name">
			<?=$value['first_name']." ".$value['last_name'];?>
		</div>
	</a>
<?endforeach;?>
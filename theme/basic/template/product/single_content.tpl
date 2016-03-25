<?php	$contenteditable = ''; if(isset($data['can']['edit']) AND ($data['can']['edit'])) {$contenteditable=' contenteditable ';} ?>
<!--Указывается схема Product.-->
<div itemscope itemtype="http://schema.org/Product">
	<div id = 'img_box'>
		<div id='primary_image'>
			<div id='show_image'>
				<img src ='<?=$data['title_img'];?>' alt="<?=$data['title_on_page'];?> " itemprop="image"/>
			</div>
			<div class='img_wrapper addimg' id='imgUpload' data-id='<?=$data['product_id'];?>' data-fld='datas'><span> + </span> <br/>добавить фото</div>
		</div>
	
		<div id='side_images'>
			<div>
				<?php if($images):foreach($images as $image): ?>
				<span><img src ='<?=DOMEN.GOOD_IMG_PATH.'/'.$data['product_id'].'/min/'.$image;?>' alt="<?=$data['title_rus'];?> " itemprop="image"/></span>
				<?php endforeach;endif; ?>
			</div>
		</div>
		
		<div class='buttons'> </div>
	</div>
	
	<div id = 'info_box'>
		<div class = 'edited title' itemprop="name" data-id="<?=$data['product_id'];?>" data-colum="data_title_rus" <?=$contenteditable; ?> > 
			   <h1><?=$data['title_h1'];?></h1>
		</div>
		

		<!--Указывается схема Offer.-->
		<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			<div class='articul'>
				артикул :<span  itemprop="mpn" ><?=$data['marking'];?></span>
			</div>
			<div class = 'edited price'		itemprop="price" data-id="<?=$data['product_id'];?>" data-colum="data_price" <?=$contenteditable; ?>> 
				<?=$data['price_active'];?> 
			</div>
			
			<div class = 'edited currency'	itemprop="priceCurrency" data-id="<?=$data['product_id'];?>" data-colum="data_currency" <?=$contenteditable; ?>> 
				<?php echo($data['currency']);?> 
			</div>
			
			<br class='clear'/>
			
			<div class = 'old_price_box'> 
				<?=$data['price_prev'];?> 
			</div>
			<link itemprop="availability" href="http://schema.org/InStock">
		</div>
		
		<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="vkontakte,facebook,odnoklassniki,gplus"></div>
		<div class='rating'> <?=$rating;?> </div>
	</div>



	<div id = 'tech_info_box'>
		<div id='tabs'>
			<ul id='items'>
			<li class='active'><a href='#tabs-1'> Описание </a></li>
			<li><a href='#tabs-2'>  Характеристики </a></li>
			<li><a href='#tabs-3'>  Отзывы <?php if(isset($comment_count) AND $comment_count>0) echo '('. $comment_count .')';?></a></li>
			</ul>
		</div>
		
		<div class='tab_content'>
			<div id='tabs-1' class='tabs_text'>  
				<span itemprop="description">
				<?=$data['content'];?>
				
				
				
				
				</span> 
			</div>
			<div id='tabs-2' class='tabs_text'> <?php look($data);?> </div>
			<div id='tabs-3' class='tabs_text'> <?=$comments;?></div>
		</div>
		
	</div>
</div>
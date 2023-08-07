<div class="product__top__right__checkline">
	<div class="product__top__right__checkline__title"><span><?php echo $feature->name; ?>:</span></div>
	<div class="product__top__right__checks">
		<?php
        $i = 0;
        foreach( $values as $option ){ ?>
			<div class="product__top__right__checks__it">
				<label>
					<input type="radio" <?= ($i==0 ? 'checked="checked" ':''); ?>name="<?php echo $feature->slug; ?>" class="take_to_ajax hidden-input" value="<?php echo $option->id; ?>"/><span class="_over"></span><span class="_txt"><?php echo $option->value_label; ?></span>
				</label>
			</div>
		<?php $i++;
        } ?>
	</div>
</div>
<div class="pager">
    <div class="pagination">
        <?php if ( isset($arrows['prev']) ) { ?>
        <a href="<?php echo $arrows['prev']['href']; ?>" class="<?php echo $arrows['prev']['class']; ?>">
            <svg class="svg-icon arrleft-icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#arrleft-icon"></use></svg>
        </a>
        <?php } ?>
        <div class="pagination__nums">
	        <?php
            foreach ( $items as $key => $item ) {
                echo $this->render( $item['view'], array_merge( [ 'type' => $key ], $item ) );
            }
            ?>
        </div>

	    <?php if ( isset($arrows['next']) ) { ?>
        <a href="<?php echo $arrows['next']['href']; ?>" class="<?php echo $arrows['next']['class']; ?>">
            <svg class="svg-icon arrright-icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#arrright-icon"></use></svg>
        </a>
	    <?php } ?>
    </div>

</div>
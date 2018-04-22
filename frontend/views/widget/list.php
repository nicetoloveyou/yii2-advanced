<?php 
frontend\assets\ListAsset::register($this);
?>

<?php foreach( $items as $val) : ?>

<p>id: <?=$val['id']?>, name: <?=$val['name']?></p>

<?php endforeach?>
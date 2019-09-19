<?php

use yii\helpers\Html;
use app\modules\translation\widgets\LanguageTranslate;

function tr($c, $m, $p = []) {
    echo LanguageTranslate::widget(['category' => $c, 'message' => $m, 'params' => $p]);
}

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $messages['title']; 
?>
<div class="item-index">

<ul>
<?php
foreach($dataProvider->getModels() as $data) {
  echo '<li><ul>';	
  $name = $data->formName();
  if (in_array('name', $columns) && in_array('description', $columns)) {
	echo '<li>';
	tr($name.':name', $data['name']); 
	echo '</li><li>';
	tr('hazard:description', $data['description']); 
    echo '</li>';
  } elseif (in_array('name', $columns))	{
	echo '<li>';
    tr($name.':name', $data['name']); 
	echo '</li><li>';	
	 tr($name.':description', $data['name']); 
    echo '</li>';
  } elseif (in_array('description', $columns)) {
	echo '<li>';	  
	      tr($name.':name', $data['description']); 
	echo '</li><li>';		  
	 tr($name.':description', $data['description']);
    echo '</li>';
  }	elseif (in_array('label', $columns)) {
	echo '<li>';	  
	tr($name.':name', $data['label']); 
	echo '</li><li>';		   
	tr($name.':description', $data['label']); 
    echo '</li>';
  }	  
  echo '</ul></li>';	
}
</ul>
?>

</div>


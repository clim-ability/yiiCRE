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
  $name = $data->formName();
  echo '<li><ul>';	 
  if (in_array('name', $columns) && in_array('description', $columns)) {
	echo $data['name'].'<li>Name: ';
	tr($name.':name', $data['name']); 
	echo '</li><li>Description: ';
	tr($name.':description', $data['description']); 
    echo '</li>';
  } elseif (in_array('name', $columns))	{
	echo $data['name'].'<li>Name: ';
    tr($name.':name', $data['name']); 
	echo '</li><li>Description: ';	
	 tr($name.':description', $data['name']); 
	echo '</li><li>Abbreviation: ';	
	 tr($name.':abbreviation', $data['name']); 
    echo '</li>';
  } elseif (in_array('description', $columns)) {
	echo $data['description'].'<li>Name: ';	  
	      tr($name.':name', $data['description']); 
	echo '</li><li>Description: ';		  
	 tr($name.':description', $data['description']);
    echo '</li>';
  }	elseif (in_array('label', $columns)) {
	echo $data['label'].'<li>Name: ';	  
	tr($name.':name', $data['label']); 
	echo '</li><li>Description: ';		   
	tr($name.':description', $data['label']); 
    echo '</li>';
  }	  
  echo '<br/></ul></li>';	
}
?>
</ul>
</div>


<?php

namespace app\utils;
use Yii;
use yii\helpers\Url;

class FlyerItems
{

    public static function getItems()
    {
		$language = Yii::t('p:flyer', Yii::$app->language);
        $items = [

            ['title' => Yii::t('hazards', 'Klimawandel und Überschwemmungen'),
                'description' => Yii::t('hazards', 'Klima-Zukunft am Oberrhein: Überschwemmungen, Hochwasser, Starkregen'),
                'img' => Url::to('@web/media/flyer/flood.png'),
                'url' => Url::to('@web/media/flyer/Flood-ClimAbility.de.pdf', ['class' => 'no-wait', 'target'=>'_flyer']),
            ],
            ['title' => Yii::t('p:showroom', 'Earth'),
                'description' => Yii::t('p:showroom', 'Explore Events in Space & Time - projected on a globe'),
                'img' => Url::to('@web/images/showroom/globe.png'),
                'url' => Url::toRoute(['/site/page', 'view' => 'showroom/three', 'mode' => 'earth']),
            ],
            ['title' => Yii::t('p:showroom', 'Cubic'),
                'description' => Yii::t('p:showroom', 'Explore Events in Space & Time - in euclidian space'),
                'img' => Url::to('@web/images/showroom/cubic.png'),
                'url' => Url::toRoute(['/site/page', 'view' => 'showroom/three', 'mode' => 'cubic']),
            ],
        ];

        return $items;
    }
    
	Html::a(\Yii::t('hazards', 'Hochwasser'), '/media/flyer/Flood-ClimAbility.de.pdf', ['class' => 'no-wait', 'target'=>'_flyer'])
	
     public static function getRandom() {
         $items = ShowroomItems::getItems();
         $any = rand(0, sizeof($items)-1);
         return $items[$any];
     }
    
}

?>

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
		
            ['title' => Yii::t('p:flyer', 'Climability Page'),
                'description' => Yii::t('p:showroom', 'Die Homepage des Climability-Projektes'),
                'img' => Url::to('@web/media/flyer/climability.png'),
                'url' => 'http://www.clim-ability.eu',
				'target' => '_climability', 
            ],
            ['title' => Yii::t('hazards', 'Überschwemmungen'),
                'description' => Yii::t('hazards', 'Klima-Zukunft am Oberrhein: Überschwemmungen, Hochwasser, Starkregen'),
                'img' => Url::to('@web/media/flyer/flood.png'),
                'url' => Url::to('@web/media/flyer/Flood-ClimAbility.'.$language.'.pdf'),
				'target' => '_flyer', 
            ],
            ['title' => Yii::t('hazards', 'Forstsektor'),
                'description' => Yii::t('hazards', 'Der Klimawandel - der Forst- und Holzsektor unter Spannung'),
                'img' => Url::to('@web/media/flyer/forest.png'),
                'url' => Url::to('@web/media/flyer/Forest-ClimAbility.'.$language.'.pdf'),
				'target' => '_flyer', 
            ],
            ['title' => Yii::t('hazards', 'Wintertourismus'),
                'description' => Yii::t('hazards', 'Was bringt die Zukunft für die Skiorte im Schwarzwald und den Vogesen?'),
                'img' => Url::to('@web/media/flyer/ski.png'),
                'url' => Url::to('@web/media/flyer/Ski-ClimAbility.'.$language.'.pdf'),
				'target' => '_flyer', 
            ],			
        ];

        return $items;
    }
    

     public static function getRandom() {
         $items = ShowroomItems::getItems();
         $any = rand(0, sizeof($items)-1);
         return $items[$any];
     }
    
}

?>

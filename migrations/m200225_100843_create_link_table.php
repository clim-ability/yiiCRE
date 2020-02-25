<?php

use yii\db\Schema;
use yii\db\Migration;

class m200225_100843_create_link_table extends Migration {

    public function safeUp() {
        $this->createTable('link', [
            'id' => Schema::TYPE_PK,
            'original' => Schema::TYPE_STRING . '(4096) NOT NULL',
			'archive' => Schema::TYPE_STRING . '(4096) NOT NULL',
			'provider' => Schema::TYPE_STRING . '(256) NOT NULL',
			'date' => Schema::TYPE_STRING . '(256) NOT NULL',
            'language_id' => 'integer REFERENCES language(id) ON DELETE CASCADE',
			'translation_id' => 'integer REFERENCES language(id) ON DELETE CASCADE',
            'status' => Schema::TYPE_STRING . '(256) NOT NULL',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
		///----------------------------------------------------------------------------------------------------------------------------
        $this->addOriginal('http://gis.clim-ability.eu/media/flyer/flood.png', 
	                  	   'https://web.archive.org/web/20200225if_/https://gis.clim-ability.eu/media/flyer/flood.png', 
						   '20200225', null);
		$this->addOriginal('https://gis.clim-ability.eu/media/flyer/Flood-ClimAbility.fr.pdf', 
	                  	   'https://web.archive.org/web/20200225if_/https://gis.clim-ability.eu/media/flyer/Flood-ClimAbility.fr.pdf', 
						   '20200225', 'fr');
		$this->addOriginal('https://gis.clim-ability.eu/media/flyer/Flood-ClimAbility.de.pdf', 
	                  	   'https://web.archive.org/web/20200225if_/https://gis.clim-ability.eu/media/flyer/Flood-ClimAbility.de.pdf', 
						   '20200225', 'de');
						   
        $this->addOriginal('http://gis.clim-ability.eu/media/flyer/forest.png', 
	                  	   'https://web.archive.org/web/20200225if_/https://gis.clim-ability.eu/media/flyer/forest.png', 
						   '20200225', null);
		$this->addOriginal('https://gis.clim-ability.eu/media/flyer/Forest-ClimAbility.fr.pdf', 
	                  	   'https://web.archive.org/web/20200225if_/https://gis.clim-ability.eu/media/flyer/Forest-ClimAbility.fr.pdf', 
						   '20200225', 'fr');
		$this->addOriginal('https://gis.clim-ability.eu/media/flyer/Forest-ClimAbility.de.pdf', 
	                  	   'https://web.archive.org/web/20200225if_/https://gis.clim-ability.eu/media/flyer/Forest-ClimAbility.de.pdf', 
						   '20200225', 'de');

        $this->addOriginal('http://gis.clim-ability.eu/media/flyer/ski.png', 
	                  	   'https://web.archive.org/web/20200225if_/https://gis.clim-ability.eu/media/flyer/ski.png', 
						   '20200225', null);
		$this->addOriginal('https://gis.clim-ability.eu/media/flyer/Ski-ClimAbility.fr.pdf', 
	                  	   'https://web.archive.org/web/20200225if_/https://gis.clim-ability.eu/media/flyer/Ski-ClimAbility.fr.pdf', 
						   '20200225', 'fr');
		$this->addOriginal('https://gis.clim-ability.eu/media/flyer/Ski-ClimAbility.de.pdf', 
	                  	   'https://web.archive.org/web/20200225if_/https://gis.clim-ability.eu/media/flyer/Ski-ClimAbility.de.pdf', 
						   '20200225', 'de');	
		/// -------------------------------------------------------------------------------------------------------------------------------------
		$this->addImage('https://cdn.businessinsider.de/wp-content/uploads/2020/02/745de3c196ffabd5242d078faad99a5148c7ec37-400x300.jpg', 
		'20200225');
		$this->addArticle('https://www.businessinsider.de/international/ski-resorts-face-no-snow-empty-mountains-lost-customers-photos-2020-2', 
		  'en', '20200224');
        /// -------------------------------------------------------------------------------------------------------------------------------------
		$this->addOriginal('https://media-cdn.sueddeutsche.de/image/sz.1.4805074/920x613', 
		  'http://archive.ph/aL5vd/dfc81204d5cb5afdebcdfa699c549e84c9c23980.jpg', '20200225');
		$this->addArticle('https://www.sueddeutsche.de/bayern/bayern-skifahren-pisten-tipps-1.4804545', 
		  'de', '20200224');
        /// -------------------------------------------------------------------------------------------------------------------------------------
		$this->addImage('https://static01.heute.at/diashow/revisions/4071601/1581975190/06c36e0105ffc5dd708fb2cbbb539a4a.jpg', 
		'20200220');
		$this->addArticle('https://www.heute.at/s/helikopter-rettet-schneeloses-skigebiet-50438919', 
		  'de', '20200224');
        /// -------------------------------------------------------------------------------------------------------------------------------------
		$this->addImage('https://api.time.com/wp-content/uploads/2020/02/norway-climate-change-indoor-ski-resorts.jpg', 
		'20200221');
		$this->addArticle('https://time.com/5785362/norway-indoor-skiing-climate-change/', 
		  'en', '20200224');
        /// -------------------------------------------------------------------------------------------------------------------------------------
		$this->addOriginal('https://www.thelocal.fr/userdata/images/article/d71ca16433471a31e65f8e9eb5b59b8af590b74fe89e72b5ecf11322c6e2e220.jpg', 
		  'http://archive.is/kHXNY/861d59aff571366d020db0cec43d20e7921826f2.jpg', '20200225');		
		$this->addOriginal('https://www.thelocal.fr/20200220/crisis-meeting-at-government-over-french-ski-resorts-with-no-snow', 
		  'http://archive.md/20200224170905/https://www.thelocal.fr/20200220/crisis-meeting-at-government-over-french-ski-resorts-with-no-snow', '20200224', 'fr', false);			
		$this->addArticle('https://www.thelocal.fr/20200220/crisis-meeting-at-government-over-french-ski-resorts-with-no-snow', 
		  'fr', '20200224');
        /// -------------------------------------------------------------------------------------------------------------------------------------
		$this->addImage('https://media0.faz.net/ppmedia/580142909/1.6641194/format_top1_breit/wetterderivaten-gegen-gruene.jpg', 
		'20200225');
		$this->addArticle('https://www.faz.net/aktuell/finanzen/so-koennten-verlustwetten-im-wintertourismus-funktionieren-16640709.html', 
		  'de', '20200224');
        /// -------------------------------------------------------------------------------------------------------------------------------------
		$this->addImage('https://ichef.bbci.co.uk/news/660/cpsprodpb/4242/production/_110926961_059990096-1.jpg', 
		'20200219');
		$this->addArticle('https://www.bbc.com/news/world-europe-51524278', 
		  'en', '20200224');
        /// -------------------------------------------------------------------------------------------------------------------------------------
		$this->addImage('https://i.guim.co.uk/img/media/cbb91a5b03fd05186c4dd378dea97fb8e8aeeffe/0_0_3960_2376/master/3960.jpg?width=620&quality=45&auto=format&fit=max&dpr=2&s=6f92b670cdbadcfcdcb01ba8b25a1867', 
		'20200225');
		$this->addArticle('https://www.theguardian.com/world/2020/feb/16/french-ski-resort-moves-snow-with-helicopter-in-order-to-stay-open', 
		  'en', '20200224');
        /// -------------------------------------------------------------------------------------------------------------------------------------
		$this->addImage('https://a57.foxnews.com/static.foxnews.com/foxnews.com/content/uploads/2020/02/1862/1048/Station-du-Mourtis.png?ve=1&tl=1', 
		'20200225');
		$this->addArticle('https://www.foxnews.com/world/french-ski-resort-closes-again', 
		  'en', '20200224');
        /// -------------------------------------------------------------------------------------------------------------------------------------
		$this->addImage('https://e3.365dm.com/20/02/2048x1152/skynews-ski-pyrenees-le-mourtis_4918802.jpg?bypass-service-worker&20200213104442', 
		'20200225');
		$this->addArticle('https://news.sky.com/story/french-ski-resort-closes-slopes-over-no-snow-for-second-year-in-a-row-11932955', 
		  'en', '20200224');
        /// -------------------------------------------------------------------------------------------------------------------------------------
		$this->addImage('https://s4.reutersmedia.net/resources/r/?d=20200213&t=2&i=OVC0GB9EZ&r=OVC0GB9EZ', 
		'20200225');
		$this->addArticle('https://www.reuters.com/article/us-climate-change-france-skiing/the-ski-resort-with-no-snow-contemplates-a-warmer-future-idUSKBN2061H4', 
		  'en', '20200224');
        /// -------------------------------------------------------------------------------------------------------------------------------------
		$this->addImage('https://resize-europe1.lanmedia.fr/r/622,311,forcex,center-middle/img/var/europe1/storage/images/europe1/economie/pourquoi-les-stations-de-ski-sont-elles-en-souffrance-3948303/54091211-1-fre-FR/Pourquoi-les-stations-de-ski-sont-elles-en-souffrance.jpg', 
		'20200225');
		$this->addArticle('https://www.europe1.fr/economie/pourquoi-les-stations-de-ski-sont-elles-en-souffrance-3948303', 
		  'fr', '20200224');
        /// -------------------------------------------------------------------------------------------------------------------------------------
			   
    }

    public function safeDown() {
        $this->dropTable('link');
    }
	
	private function findLanguageId($name)
	{
		$result = null;
		if(is_string($name)) {
		  $connection = \Yii::$app->db;
          $sql = "SELECT * FROM language WHERE language = '".$name."' ORDER BY id DESC";
          $command = $connection->createCommand($sql);
          $lang = $command->queryOne();
		  if(!is_null($lang)) {
		    $languageId=$lang['id'];
		  }		
		}
        return $result;
	} 
	
    private function addOriginal($original, $archive, $date, $language=null) {
        $languageId = $this->findLanguageId($language);
		return $this->insert('link', [
                    'original' => $original,
                    'archive' => $archive,
					'provider' => 'ia',
                    'date' => $date,
					'status' => 'archived',
					'language_id' => $languageId,
					
        ]);
    }

    private function addTranslation($original, $archive, $date, $language=null, $translation=null) {
        $languageId = $this->findLanguageId($language);
		$translationId = $this->findLanguageId($translation);
		return $this->insert('link', [
                    'original' => $original,
                    'archive' => $archive,
					'provider' => 'archive.today',
                    'date' => $date,
					'status' => 'archived',
					'language_id' => $languageId,
					'translation' => $translationId
					
        ]);
    }
	
	private function addArticle($original, $language, $date='20200224', $inclOriginal=true) {
		if ($inclOriginal) {
		  $this->addOriginal($original,
		   'https://web.archive.org/web/'.$date.'/'.$original,
		   $date, $language);
		}
		if('fr' != $language) {
		  $this->addTranslation($original,
		   'http://archive.md/'.$date.'/'.'https://translate.google.de/translate?sl='.$language.'&tl=fr&u='.$original,
		   $date, $language, 'fr');
		}
		if('fr' != $language) {
		  $this->addTranslation($original,
		   'http://archive.md/'.$date.'/'.'https://translate.google.de/translate?sl='.$language.'&tl=de&u='.$original,
		   $date, $language, 'de');
		}
		if('fr' != $language) {
		  $this->addTranslation($original,
		   'http://archive.md/'.$date.'/'.'https://translate.google.de/translate?sl='.$language.'&tl=en&u='.$original,
		   $date, $language, 'en');		
		}
	}
	
	private function addImage($original, $date='20200224') {
		$this->addOriginal($original, 'https://web.archive.org/web/'.$date.'if_/'.$original, null);
	}

}

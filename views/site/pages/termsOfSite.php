<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\translation\widgets\LanguageTranslate;
use app\widgets\EmailWidget;

function tr($c, $m, $p = []) {
    echo LanguageTranslate::widget(['category' => $c, 'message' => $m, 'params' => $p]);
}
$this->title = yii::t('p:siteterms', 'Terms of Site');
?>

<div class="container-fluid">
        <br/><br/>
  <div class="row">
    <div class="col-md-8 col-md-offset-2 medium"> 


    <h2><b> <?php tr('sugg:siteterms', 'Nutzungsbedingungen'); ?> </b></h2>  
    <br/>
    <p>
      <?php
        tr('sugg:siteterms', '{tambora.org} ist eine kollaborative Forschungsumgebung, die selbstverständlich den geltenden rechtlichen Regelungen des Persönlichkeits- und des Urheberrechtes unterliegt.' , 
                        ['tambora.org' => Html::a('<strong>tambora.org</strong>', 'https://www.freidok.uni-freiburg.de/proj/2')]);
        echo ' ';
        tr('sugg:siteterms', 'Des weiteren gelten die allgemein gültigen Regeln guter wissenschaftlicher Praxis beispielsweise bzgl. Zitaten.');   
        ?>
    </p>
    <p>
      <?php
        tr('sugg:siteterms', 'Alle Beitragenden und Nutzer verpflichten sich ausdrücklich, die geltenden rechtlichen Bestimmungen einzuhalten.');
        echo ' ';
        tr('sugg:siteterms', 'Sie sind und bleiben Urheber im Sinne der geltenden Bestimmungen und definieren den Grad der Nutzung, insbesondere den Austausch und den Abgleich von Daten mit Dritten selbst.');
        echo ' ';
        tr('sugg:siteterms', 'Tambora.org begründet keine Rechtsverhältnisse, bietet aber für den Fall der Freigabe der Daten eine zitierfähige DOI Vergabe an.'); 
        echo ' ';
        tr('sugg:siteterms', 'Nachfolgend sind einige der Bestimmungen näher spezifiziert.');
        ?>
    </p>
    <br/>
    <h3> <?php tr('sugg:siteterms', 'Personenbezogene Daten und Nutzerkonto'); ?> </h3> 
    <p>
      <?php  
        $login = Yii::$app->user->isGuest 
            ? Html::a(yii::t('sugg:siteterms', 'Anmeldung'), ['/user/login']) 
            : yii::t('sugg:siteterms', 'Anmeldung');
        tr('sugg:siteterms', 'Ohne {Anmeldung} ist Tambora.org nur eingeschränkt nutzbar.', 
            ['Anmeldung' => $login]);
        echo ' ';
        tr('sugg:siteterms', 'Nach dem Anlegen eines Nutzerkontos können insbesondere klima- und umweltgeschichtliche Datensätze eingegeben und veröffentlicht werden.');
        echo ' ';         
        tr('sugg:siteterms', 'Mit einer solchen Offenlegung ist in der Regel auch eine Nennung des Benutzernamens der veröffentlichenden Person verbunden.');
        echo ' ';         
        tr('sugg:siteterms', 'Andere personenbezogene Daten werden ohne Ihre ausdrückliche Zustimmung nicht an Dritte weitergegeben.');
        ?>
    </p>
    <p>
      <?php  
      
        $register = Yii::$app->user->isGuest 
            ? Html::a(yii::t('sugg:siteterms', 'Anlegen des Nutzerkontos'), ['/user/register']) 
            : yii::t('sugg:siteterms', 'Anlegen des Nutzerkontos');
        tr('sugg:siteterms', 'Beim {Anlegen des Nutzerkontos} sind wahrheitsgemäße Angaben zu machen.', 
            ['Anlegen des Nutzerkontos' => $register]);
        echo ' ';         
        tr('sugg:siteterms', 'Die Weitergabe von Benutzername und Passwort an Dritte ist ausdrücklich untersagt.');
        echo ' ';
        $profile = Yii::$app->user->isGuest 
            ? yii::t('sugg:siteterms', 'Kontaktdaten') 
            : Html::a(yii::t('sugg:siteterms', 'Kontaktdaten'), ['/user/admin/update-user', 'id'=>Yii::$app->user->id]);
        tr('sugg:siteterms', 'Um eine Korrespondenz zu ermöglichen, verpflichten sich registrierte Nutzer ihre {Kontaktdaten} (insbesondere ihre Email-Adresse) aktuell zu halten.', 
            ['Kontaktdaten' => $profile]);
        echo ' ';         
        ?>
    </p>
    <br/>
    <h3> <?php tr('sugg:siteterms', 'Nutzung und Verwaltung von Daten'); ?> </h3> 
    <p>
      <?php  
        tr('sugg:siteterms', 'Die Nutzer verpflichten sich bei der Eingabe von Daten in tambora.org keine Rechte Dritter zu verletzen.');
        echo ' ';
        tr('sugg:siteterms', 'Vor einer Veröffentlichung sollten sie die Qualität ihrer Daten sorgfältig prüfen und die Daten mit vollständigen Quellenangaben versehen.');
        echo ' ';         
        tr('sugg:siteterms', 'Bei der Veröffentlichung von Scans und anderem graphischen Material in tambora.org müssen die Bildrechte gewahrt sein.');
        ?>
    </p>
 
    <p>
      <?php        
        tr('sugg:siteterms', 'Tambora.org bietet drei Optionen der Projektarbeit, mit der verschiedene Sichtbarkeitsgrade verbunden sind, an:');
        echo '<table style="border-collapse: separate; border-spacing: 10px 10px;"><tr><td></td><td>';
        echo Html::img("@web/images/icons/scope/private.png").' </td> <td>';
        tr('sugg:siteterms', 'Persönliche Datennutzung als einzelner Nutzer');
        echo '</td></tr><tr><td></td><td>';
        echo Html::img("@web/images/icons/scope/team.png").' </td> <td>';
        tr('sugg:siteterms', 'Gemeinschaftliche Datennutzung in einer Arbeitsgruppe');
        echo '</td></tr><tr><td></td><td>';
        echo Html::img("@web/images/icons/scope/public.png").' </td> <td>';
        tr('sugg:siteterms', 'Publikation der Daten für die Öffentlichkeit');
        echo '</td></tr></table>';
        ?>
    </p>    
    <p>
      <?php  
        tr('sugg:siteterms', 'Bei der Publikation der Daten für die Öffentlichkeit hat der Nutzer die Möglichkeit eine geeignete {Creative Common Lizenz} zu wählen.',
            ['Creative Common Lizenz' => Html::a(yii::t('sugg:siteterms','Creative Common Lizenz'), 'https://creativecommons.org/licenses/')]);
      ?>
    </p>    

    <br/>
    <h3> <?php tr('sugg:siteterms', 'Zitierpflicht'); ?> </h3> 
    <p>
      <?php  
        tr('sugg:siteterms', 'Die Publikation von Daten aus tambora.org muss immer mit einer entsprechenden Quellenangabe versehen sein '
                            .'und es gelten die allgemeinen Urheberrechte, d.h. jedwede Publikation einzelner Zitate muss unter Angabe '
                            .'von {tambora.org} und der {DOI} erfolgen.', 
            ['tambora.org' => Html::a('tambora.org', 'https://dx.doi.org/10.6094/tambora.org'),
             'DOI' => Html::a('DOI', 'http://www.doi.org')]);
        echo ' ';
        tr('sugg:siteterms', 'Zur Honorierung der Arbeiten des Nutzers, der die Daten beigesteuert hat, sollte deren Name genannt werden.');
        ?>
    </p>
    <br/>
    <h3> <?php tr('sugg:siteterms', 'Haftungsausschluss'); ?> </h3> 
    <p>
      <?php  
        tr('sugg:siteterms', 'Tambora.org ist bemüht einen Missbrauch zu verhindern, kann aber für die Richtigkeit, Vollständigkeit und Aktualität der Inhalte keine Gewähr übernehmen.');
        echo ' ';
        tr('sugg:siteterms', 'Alle Inhalte haben ausschließlich informativen Charakter.');
        ?>
    </p>
    <p>
      <?php 
        tr('sugg:siteterms', 'Es wird keine Haftung für Schäden, die durch die Nutzung von tambora.org oder der darin enthaltenen Inhalte entstehen, übernommen.');
        echo ' ';         
        tr('sugg:siteterms', 'Tambora.org enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben.');
        echo ' ';
        tr('sugg:siteterms', 'Deshalb kann  für fremde Inhalte keine Verantwortung übernommen werden.');
        echo ' ';         
        tr('sugg:siteterms', 'Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich.');
        ?>
    </p>
    <br/>
    <h3> <?php tr('sugg:siteterms', 'Cookies'); ?> </h3> 
    <p>
      <?php  
        tr('sugg:siteterms', 'Tambora.org verwendet teilweise so genannte Cookies.');
        echo ' ';
        tr('sugg:siteterms', 'Sie dienen dazu, unser Angebot nutzerfreundlicher zu machen.');
        echo ' ';         
        tr('sugg:siteterms', 'Sie können Ihren Browser so einstellen, dass Sie über das Setzen von Cookies informiert werden und Cookies nur im Einzelfall erlauben, die Annahme von Cookies für bestimmte Fälle oder generell ausschließen sowie das automatische Löschen der Cookies beim Schließen des Browsers aktivieren.');
        echo ' ';         
        tr('sugg:siteterms', 'Bei der Deaktivierung von Cookies kann die Funktionalität dieser Website eingeschränkt sein.');
        ?>
    </p>    
    <br/>
    <h3> <?php tr('sugg:siteterms', 'Änderungen vorbehalten'); ?> </h3> 
    <p>
      <?php  
        tr('sugg:siteterms', 'Tambora.org befindet sich in kontinuierlicher Entwicklung.');
        echo ' ';
        tr('sugg:siteterms', 'Neue Funktionen erfordern unter Umständen Ergänzungen und Änderungen der Nutzungsbedingungen.');
        echo ' ';         
        tr('sugg:siteterms', 'Deswegen behält sich tambora.org das Recht vor, Aktualisierungen der Nutzungsbedingungen zu jeder Zeit vorzunehmen.');
        ?>
    </p>    
    <br/>
    </div>
  </div>  
</div>

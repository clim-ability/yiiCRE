<?php

use yii\db\Schema;
use yii\db\Migration;

class m211130_130843_refill_risk_table extends Migration {

    public function safeUp() {
		$this->delete('risk');
		//
		$this->delete('danger_risk');
		$this->delete('sector_risk');
		$this->delete('zone_risk');
		$this->delete('landscape_risk');
	    $this->delete('country_risk');

        // DELETE FROM public.migration	WHERE version = 'm211130_130843_refill_risk_table';

		$risks = [];
		$negImpacts = $this->readCsv('impacts_positive.csv', 13);
		foreach($negImpacts as $row) {
			$risk = $this->extractRisk($row, 'Impact');
			if($risk['name'] != '') {
			    $risk['negative'] = False;
			    $risk['dangers'] = $this->extractStressors($row, 'klimatischer Stressor');
			    $risk['zones'] = $this->extractBereiche($row, 'Unternehmensbereich');
  			    $risk['sectors'] = $this->extractBranches($row, 'Branche(n)');
				$risk['countries'] = $this->extractCountries($row, 'Land');
				$risk['landscapes'] = $this->extractLandscapes($row, 'Naturraum');

			    $risks[] = $risk;
			}
		}
		
		$posImpacts = $this->readCsv('impacts_negative.csv', 20);
		foreach($posImpacts as $row) {
			$risk = $this->extractRisk($row, 'Impact');
			if($risk['name'] != '') {
			    $risk['negative'] = True;
			    $risk['dangers'] = $this->extractStressors($row, 'klimatischer Stressor');
			    $risk['zones'] = $this->extractBereiche($row, 'Unternehmensbereich');
  			    $risk['sectors'] = $this->extractBranches($row, 'Branche(n)');
				$risk['countries'] = $this->extractCountries($row, 'Land');
				$risk['landscapes'] = $this->extractLandscapes($row, 'Naturraum');

			    $risks[] = $risk;
			}
		}
		
        // var_dump($risks);
		foreach($risks as $risk) {
			var_dump($risk);
		    $this->insert('risk', [ 'name' => $risk['name'], 'description' => $risk['description'], 'details' => $risk['detail'], 'negative' => $risk['negative'], 'name' => $risk['name'],'visible' => true]);
            $newRisk = $this->findRisk($risk['name']); 
			foreach($risk['dangers'] as $danger) {
				$newDanger = $this->findDanger($danger);
				$this->insert('danger_risk', [ 'danger_id' => $newDanger['id'], 'risk_id' => $newRisk['id'] ]);
			}
			foreach($risk['zones'] as $zone) {
				$newZone = $this->findZone($zone);
				$this->insert('zone_risk', [ 'zone_id' => $newZone['id'], 'risk_id' => $newRisk['id'] ]);
			}
			foreach($risk['sectors'] as $sector) {
				$newSector = $this->findSector($sector);
				$this->insert('sector_risk', [ 'sector_id' => $newSector['id'], 'risk_id' => $newRisk['id'] ]);
			}
			foreach($risk['countries'] as $country) {
				$newCountry = $this->findCountry($country);
				$this->insert('country_risk', [ 'country_id' => $newCountry['id'], 'risk_id' => $newRisk['id'] ]);
			}
			foreach($risk['landscapes'] as $landscape) {
				$newLandscape = $this->findLandscape($landscape);
				$this->insert('landscape_risk', [ 'landscape_id' => $newLandscape['id'], 'risk_id' => $newRisk['id'] ]);
			}

		}
    }

    public function safeDown() {
        $this->delete('risk');

		$this->delete('danger_risk');
		$this->delete('sector_risk');
		$this->delete('zone_risk');
		$this->delete('landscape_risk');
	    $this->delete('country_risk');
    }


    private function extractRisk($row, $header='Impact', $detail='Beschreibung'){

		$detail = $row[$detail];
		$description = $row[$header];
		$name = $row[$header];

		#positiv
		$name = str_replace('positiv - steigender Absatz, höhere Nachfrage nach "Schön-Wetter-Produkten" und Angeboten, z. B. nach Getränken, Swimming Pools, Bewässerungssystemen, Gebäudedämmung, Lüftungssystemen','steigender Produktabsatz',$name);
		$name = str_replace('positiv: mehr Absatz bei gutem Wetter in Außengastronomie, längere Draußensaison','längere Saison',$name);
		$name = str_replace('positiv: kürzere kältebedingte Unterbrechungen von Arbeiten im Freien','kürzere Unterbrechungen',$name);
		$name = str_replace('Kühlenergiebedarf steig - Energieabsatz steigt','steigender Energieabsatz',$name);
		$name = str_replace('kurzfristig: erhöhter Abfluss dank Gletscherschmelze führt zu erhöhtem Wasserangebot für Energieproduktion','Schmelzwasser steigert Wasserangebot',$name);
		$name = str_replace('positiv - mehr Reparatur- und Sanierungsaufträge durch starkregenbedingte Schäden an Häusern, in Gärten, etc.','Reparatur starkregenbedingter Schäden',$name);
		$name = str_replace('positiv - mehr Reparatur- und Sanierungsaufträge durch unwetterbedingte Schäden an Häusern, in Gärten, etc.','Reparatur unwetterbedingter Schäden',$name);
		$name = str_replace('positiv - mehr Reparatur- und Sanierungsaufträge durch sturmbedingte Schäden an Häusern, in Gärten, etc.','Reparatur sturmbedingter Schäden',$name);
		$name = str_replace('Veränderung von Kundenwünschen: mehr Nachfrage nach umweltschonenden, klimafreundlichen Produkten - positiv, wenn nachhaltige Produkte im Portfolio sind bzw. integriert werden können','Nachfrage klimafreundliche Produkte',$name);
		$name = str_replace('mehr Aufträge durch zunehmenden Sanierungsbedarf (energetische Sanierungen)','energetischer Sanierungsbedarf steigt',$name);
        #negative
		//$name = str_replace('xxx','xxx',$name);

		$name = str_replace('Hitzebelastung bei Arbeiten im Innenbereich bzw. im Schatten: Schwitzen, Flüssigkeitsverlust und Kreislaufprobleme  --> sinkende Arbeitproduktivität','Hitzebelastung',$name);
		$name = str_replace('Hitzebelastung bei Arbeiten im Freien: zusätzlich hohe UV-Strahlung --> Sonnenbrand-, Sonnenstich-, Hautkrebsrisiko','UV-Belastung',$name); 
		$name = str_replace('Verstärkung von gesundheitlichen Beschwerden und Vorerkrankungen --> mehr Fehlzeiten, geringere Arbeitsproduktivität','gesundheitlichen Beschwerden',$name);
		$name = str_replace('Probleme mit Arbeitsschutz-Bestimmungen wg. Hitzebelastung der Mitarbeiter','Arbeitsschutz-Bestimmungen',$name); 
		$name = str_replace('sinkender Konsum von alkoholischen Getränken','sinkender Konsum',$name); 
		$name = str_replace('zunehmende Probleme mit der Verderblichkeit gelagerter Produkte & Rohstoffe','Verderblichkeit',$name);
		$name = str_replace('Überhitzung von Maschinen und Geräten: Black-Outs, Stromausfälle, Schäden an IT-Infrastruktur, Gefahr von Datenverlusten; Brandgefahr','Überhitzung & Black-Outs',$name);
		$name = str_replace('sinkende Produktionskapazität wg. Nicht-Volllast der Maschinen','Produktionskapazität sinkt',$name);
		$name = str_replace('Blow-Ups auf Straßen und versiegelten Betriebsflächen --> Tempolimits, Streckensperrungen, Verspätungen, Unfallgefahr; Reparaturbedarf auf versiegelten Flächen','Blow-Ups',$name);
		$name = str_replace('Reduzierung der Arbeitszeiten wg. Hitzebelastung des Personals und zu heißen Betriebsmitteln / Materialien','Arbeitszeit sinkt',$name);
		$name = str_replace('Beinträchtigung von hitzeempfindlichen Betriebsmitteln --> Verzögerungen im Arbeitsablauf','Beinträchtigung Betriebsmitteln ',$name);
		$name = str_replace('Verschieben von Schichten in Nacht- und Morgenstunden --> planerischer Aufwand, Zusatzkosten durch Nachtaufschlag, Mitarbeiterunzufriedenheit','Verschieben von Schichten',$name);
		$name = str_replace('geringere Erträge aus tierischen Erzeugnissen wg. geringerer Milchleistung, erhöhter Mortalität und reduzierter Fruchtbarkeit','tierischen Erzeugnisse stagnieren',$name);
		$name = str_replace('zunehmender Hitzestress bei Touristen, auch in Höhenlagen --> Minderung der Attraktivität der Urlaubsregion','Hitzestress bei Touristen',$name);
		$name = str_replace('temperaturbedingte Verschiebung von Abläufen & Terminen --> organisatorischer Mehraufwand, Kapazitätsengpässe, Lieferverzögerungen, höhere Arbeitsbelastung und Überstunden','Verschiebung von Abläufen',$name);
		$name = str_replace('erhöhter Investitionsbedarf in Klimatisierungsmaßnahmen','erhöhte Klimatisierungskosten',$name);
		$name = str_replace('erhöhter Kühlenergiebedarf --> Energiekosten steigen','erhöhter Kühlenergiebedarf',$name);
		$name = str_replace('Rückgang von nicht-hitzetoleranten Anbaupflanzen --> Verlust von Sorten und regionaltypischen Nahrungs- und Genussmitteln','Verlust von Anbaupflanzen',$name);
		$name = str_replace('Nachwuchsmangel wg. Zunahme an Extremereignissen','Nachwuchsmangel',$name);
		$name = str_replace('Kunden-Unzufriedenheit wg. eingeschränktem Pflanzenwachstum bei Dach- und Fassadenbegrünungen','Kunden-Unzufriedenheit',$name);
		$name = str_replace('Grundwasserspiegel sinkt --> zunehmende Wasserknappheit --> zunehmende Konkurrenz und Nutzungskonflikte um sauberes Trinkwasser','Nutzungskonflikte um Trinkwasser',$name);
		$name = str_replace('Abnahme und Versiegen von Quellschüttungen','Versiegen von Quellschüttungen',$name);
		$name = str_replace('schlechtere Wasserqualität in Dürreperioden','Wasserqualität sinkt',$name);
		$name = str_replace('Restriktionen bei der Erschließung neuer Quellen --> Behinderung des Firmenwachstums','Restriktionen Erschließung Quellen',$name);
		$name = str_replace('Engpässe bei der Wasserversorgung --> Einschränkung  von wasserintensiven Prozessen --> Reduzierung der Wasserentnahme aus  als Gegenmaßnahme','Reduzierung der Wasserentnahme',$name);
		$name = str_replace('Niedrigwasser am Rhein --> reduzierte Transportkapazität von Schiffen (auch längere Zeiträume) --> Verzögerungen in der Lieferkette, Erhöhung der Transportkosten, sinkende Rentabilität','sinkende Rentabilität',$name);
		$name = str_replace('Niedrigwasser am Rhein --> reduzierte Transportkapazität von Schiffen --> Umplanungen, Ausweichen auf Straße und Schiene, Mehraufwand','Mehraufwand Umplanungen',$name);
		$name = str_replace('Niedrigwasser --> reduzierte Transportkapazität, Überlastung --> Nicht-Erfüllung von Aufträgen, Kundenunzufriedenheit, Auftragsverlust','Nicht-Erfüllung von Aufträgen',$name);
		$name = str_replace('Niedrigwasser --> Einnahmeverluste --> Teilzeit-Anstellungen als Verlustausgleich','Teilzeit-Anstellungen',$name);
		$name = str_replace('Niedrigwasser --> reduzierte Transportkapazität des Rheins --> verstärkte Nachfrage nach LKW-Transport --> Mangel an LKWs und Fahrern --> Verzögerungen, höhere Transportkosten','Mangel an LKWs und Fahrern',$name);
		$name = str_replace('reduzierte Transportkapazität wg. Niedrigwasser des Rheins --> steigende Preise von Massengütern wie Heizöl, Diesel, Kohle','Energieträgerpreise steigen',$name);
		$name = str_replace('aufwändigere Förderung und Aufbereitung von Quellwasser','aufwändigere Quellwasser-Förderung',$name); 
		$name = str_replace('erhöhte Belastung für Wasserinfrastruktur (Förderanlagen, Leitungen) wg. Abgabespitzen in Dürresituation','Belastung für Wasserinfrastruktur',$name);
		$name = str_replace('steigende Trinkwasserpreise für Haushalte (Leitungswasser)','steigende Trinkwasserpreise',$name);
		$name = str_replace('erhöhter Wasserverbrauch und Bewässerungsbedarf in Hitzewellen und Dürreperioden','erhöhter Wasserverbrauch',$name); 
		$name = str_replace('erhöhter Bedarf für betriebliches Wassermanagement und Wassersparmaßnahmen','Wassersparmaßnahmen',$name);
		$name = str_replace('Waldbrandrisiko steigt, v.a. bei Nadelholzbeständen','Waldbrandrisiko',$name);
		$name = str_replace('Ernteeinbußen wg. Hitze- und Trockenschäden an Anbaupflanzen','Ernteeinbußen',$name);
		$name = str_replace('erhöhter Investitionsbedarf in Bewässerungsanlagen','Bewässerungsanlagen',$name);
		$name = str_replace('geringere Heuerträge --> Zukauf von Tierfutter (Heu) --> zusätzliche Ausgaben','geringere Heuerträge',$name);
		$name = str_replace('erhöhter relativer Arbeitsaufwand zur Heuernte --> sinkende Arbeitsproduktivität','Arbeitsaufwand Heuernte',$name);
		$name = str_replace('Preisverfall von Fleisch wg. Überangebot','Preisverfall von Fleisch',$name);
		$name = str_replace('steigende Preise für land- und forstwirtschaftliche Rohstoffe','land- und forstwirtschaftliche Rohstoffpreise',$name);
		$name = str_replace('Nicht-Verfügbarkeit natürlicher Rohstoffe wg. Ernteeinbußen, Unwetterschäden, etc. --> Lieferschwierigkeiten, Unterbrechungen der Produktion, Verzögerung von Aufträgen','natürlicher Rohstoffe fehlen',$name);
		$name = str_replace('schlechtere Qualität der natürlichen Rohstoffe (z. B. Gerste, Malz, Fichtenholz)','Qualität natürliche Rohstoffe sinkt',$name); 
		$name = str_replace('organisatorischer Mehraufwand bei Lieferengpässen','Mehraufwand bei Lieferengpässen',$name);
		$name = str_replace('Kundenunzufriedenheit wg. Nicht-Verfügbarkeit von Produkten oder Qualitätsschwankungen bei Naturprodukten','Nicht-Verfügbarkeit von Produkten',$name);
		$name = str_replace('zunehmende Planungsunsicherheit wg. Häufung von Lieferengpässen, geänderten Erntezeiten, etc.','Planungsunsicherheit',$name);
		$name = str_replace('Geschmacksveränderungen bei Naturprodukten auf Grund von klimabedingten Qualitätsschwankungen der natürlichen Rohstoffe','Geschmacksveränderungen',$name);
		$name = str_replace('klimabedingte Veränderung von Kundenwünschen','Veränderung von Kundenwünschen',$name);
		$name = str_replace('Verschärfung der Wasserknappheit zu Spitzenverbrauchszeiten wg. unterdimensionierter Infrastruktur','Verschärfung der Wasserknappheit',$name);
		$name = str_replace('Zunahme der Bodenerosion','Bodenerosion',$name); 
		$name = str_replace('Verschlechterung der Brunnenwasserqualität wg. vieler Trübstoffe','Trübstoffe in Brunnenwasser',$name);
		$name = str_replace('Reduzierung bzw. Einstellung der Wasserförderung nach Starkregenereignissen','Wasserförderung reduziert',$name);
		$name = str_replace('Verlust einer Marke wg. Schließens der Oberflächenwasserquelle','Verlust einer Marke',$name);
		$name = str_replace('steigende Ausgaben für Bodenerosionsschutz','Ausgaben für Bodenerosionsschutz',$name);
		$name = str_replace('verstärkte Bakterienbildung in wärmerem Wasser','Bakterienbildung',$name);
		$name = str_replace('Verschwinden energieintensiver Produkte vom Markt --> Umstellung auf neue Arbeitsmaterialien','Verschwinden energieintensiver Produkte',$name);
		$name = str_replace('Veränderung von Kundenwünschen --> sinkender Absatz klimaschädlicher Produkte','sinkender Absatz klimaschädlicher Produkte',$name);
		$name = str_replace('Pflicht zur Anschaffung neuer umwelt- und klimafreundlicher Geräte, Maschinen, Fahrzeuge','Anschaffung neuer Geräte',$name);
		$name = str_replace('Erschwerung des Straßengüterverkehrs durch Klimaschutzmaßnahmen wie Umweltzonen, Maut, etc.','Umweltzonen, Maut',$name);
		$name = str_replace('Veränderung von Flora und Fauna sowie Bodenwasserhaushalt --> Trinkwasseraufbereitung wird aufwändiger','Veränderung von Flora und Fauna',$name);
		$name = str_replace('Ausbreitung von Schädlingen, neuen Krankheitserregern und Pilzen --> hohe Verluste einzelner Arten','Schädlinge, Krankheitserreger, Pilze',$name);
		$name = str_replace('Verstärkter Einsatz von Pestiziden und Medikamenten --> höhere Ausgaben und negative Folgewirkungen auf Ökosysteme und Tiere','vermehrt Pestizide und Medikamente',$name);
		$name = str_replace('zeitliche Verschiebung gewohnter naturnaher Unternehmensabläufe','zeitliche Verschiebung Unternehmensabläufe',$name);
		$name = str_replace('mehr Laubholz in holzverarbeitenden Betrieben --> anderer Holzaufbau des Laubholzes verursacht technische Probleme','technische Probleme durch Laubholz',$name);
		$name = str_replace('Veränderung der Baumartenzusammensetzung in Nutzwäldern --> Verdrängung weniger thermophiler Arten, z. B. Fichte','Verdrängung weniger thermophiler Arten',$name);
		$name = str_replace('Zurückhaltung bei Investitionen wg. Ungewissheit von Klimaprognosen','Ungewissheit von Klimaprognosen',$name);
		$name = str_replace('erschwerte Planbarkeit von Arbeiten im Außenbereich','erschwerte Planbarkeit',$name);
		$name = str_replace('Zunahme der Bodenfeuchte im Winterhalbjahr --> Zunahme von Bodenverschlämmung, Staunässe und Bodenverdichtung bei unsachgemäßer Bearbeitung --> schlechtere Durchwurzelbarkeit','schlechtere Durchwurzelbarkeit',$name);
		$name = str_replace('zunehmend milde, feuchte Winter --> schlammiger Boden --> eingeschränkte Befahrbarkeit mit schweren Geräten','eingeschränkte Befahrbarkeit',$name);
		$name = str_replace('zunehmende Waldschäden und geringeres Holzwachstum - v.a. bei wenig trockenresistenten Arten (Fichte, Tanne, auch Kiefer, Buche) --> langfristig Verknappung von Nutzholz, steigende Preise','Waldschäden und geringeres Holzwachstum',$name);
		$name = str_replace('Waldschäden nehmen zu --> große Menge von Holz auf dem Weltmarkt --> kurzfristig sinkende bzw. schwankende Holzpreise','schwankende Holzpreise',$name);
		$name = str_replace('mehr Käfer- und Schadholz --> Absatzschwierigkeiten wg. schlechtem Image','Käfer- und Schadholz',$name);
		$name = str_replace('mehr Käfer-, Sturm- und Schadholz --> Arbeitsspitzen nehmen zu','Arbeitsspitzen durch Schadholz',$name);
		$name = str_replace('bei Waldschäden Gefahr der Borkenkäferausbreitung in geschwächten Bäumen oder trocken gelagertem Holz','Borkenkäferausbreitung',$name);
		$name = str_replace('erhöhter Wasserbedarf bei Nasslagerung von Sturm- oder Schadholz','Wasserbedarf bei Nasslagerung',$name);
		$name = str_replace('Kahlschläge zur Verhinderung von Borkenkäferplagen --> Lärmbelästigung durch (Motorrad-)Verkehr nimmt zu, da schallschluckende Wirkung des Waldes fehlt','schallschluckende Wirkung sinkt',$name);
		$name = str_replace('steigende Ausgaben und Geschäftsrisiken durch verschiedene Klimaanpassungsmaßnahmen','Ausgaben und Geschäftsrisiken',$name);
		$name = str_replace('zunehmende Waldschäden --> Landschaftsbild verändert sich negativ --> Imageschaden als Tourismusregion','Imageschaden als Tourismusregion',$name);
		$name = str_replace('Überflutung des Betriebsgeländes durch ausufernde Flüsse --> mögliche Schäden an Gebäuden, Betriebsmitteln und gelagerter Ware durch eindringendes Wasser','Schäden durch eindringendes Wasser',$name);
		$name = str_replace('Stromausfälle, Schäden an Servern und IT-Infrastruktur','Schäden an IT-Infrastruktur',$name);
		$name = str_replace('Austritt wassergefährdender Stoffe, z. B. Öl, Chemikalien, Baustoffe, etc. --> Umweltverschmutzung, Risiko der Haftbarmachung','Austritt wassergefährdender Stoffe',$name);
		$name = str_replace('Restriktionen bei Unternehmenserweiterung wg. Standort im Hochwassergebiet','Unternehmenserweiterung im Hochwassergebiet',$name);
		$name = str_replace('zu hohe Versicherungsbeträge bei Lage in HQ-Gebiet (EU-Richtlinie) --> fehlender Versicherungsschutz','hohe Versicherungsbeträge',$name);
		$name = str_replace('Einschränkung der Schifffahrt bei Rhein-Hochwasser: erst reduzierte Tonnage, dann Sperrung des Rheins, meist kurze Zeiträume --> Verzögerungen in der Lieferkette','reduzierte Tonnage, Sperrung',$name);
		$name = str_replace('Sperrung des Rheins für Schifffahrt bei Hochwasser --> Umplanungen, Ausweichen auf Straße und Schiene, Mehraufwand','Ausweichen auf Straße und Schiene',$name);
		$name = str_replace('Sperrung des Rheins für Schifffahrt bei Hochwasser --> Nicht-Erfüllung von Aufträgen, Kundenunzufriedenheit','Aufträge nicht erfüllbar',$name);
		$name = str_replace('Überflutung des Betriebsgeländes durch ausufernde kleine Fließgewässer und nicht abfließendes Regenwasser --> mögliche Schäden an Gebäuden und Betriebsmitteln durch eindringendes Wasser und Schlamm','Gebäudeschäden durch Schlamm',$name);
		$name = str_replace('Überflutung des Betriebsgeländes --> Betriebsunterbrechung wg. Eindämmungsmaßnahmen und Unzugänglichkeit','Betriebsunterbrechung durch Überflutung',$name);
		$name = str_replace('Schäden durch eindringendes Regenwasser und Druckwasser, besonders bei zu klein dimensionierten oder verstopften Abwasserrohren, Kanälen oder Dachrinnen','Schäden durch Druckwasser',$name);
		$name = str_replace('Behinderung und Unterbrechung von Arbeiten im Freien, auf Baustellen, etc --> Zeitverlust, geringere Arbeitsproduktivität','Arbeiten im Freien behindert',$name);
		$name = str_replace('Starkregen als Auslöser von Rutschungen --> Schäden an Gebäuden und Betriebsmitteln, etc.','Rutschungen',$name);
		$name = str_replace('Verkehrsbehinderungen durch Überflutungen und Rutschungen --> Verspätungen in der Lieferkette, Unfallgefahr','Verkehrsbehinderung durch Rutschungen',$name);
		$name = str_replace('steigende Unfallgefahr während Starkregen auf Grund schlechter Sicht und Aquaplaning --> mögliche Schäden an Fahrer, Lkw und Ware','Unfallgefahr',$name);
		$name = str_replace('Gefahr für Leib und Leben durch reißende Fluten an Fließgewässern, in Tiefgaragen, etc.','Gefahr für Leib und Leben',$name); 
		$name = str_replace('Ausfall von Mitarbeitern auf Grund von Unwetterschäden im privaten Bereich und Aufräumeinsätzen','Ausfall von Mitarbeitern',$name);
		$name = str_replace('extremwetterbedingte Schäden an nicht-versichterten Materialien an Baustellen --> Gefahr finanzieller Verluste, Kunden-Unzufriedenheit','Schäden an nicht-versichterten Materialien',$name);
		$name = str_replace('Schäden & Ertragseinbußen an Anbaupflanzen (Getreide, Gemüse, Sonderkulturen, Jungbäume)','Ertragseinbußen an Anbaupflanzen',$name);
		$name = str_replace('Auftragsschwankungen durch unregelmäßiges Auftreten von Extremwetterereignissen und folgenden Reparaturarbeiten','Auftragsschwankungen',$name);
		$name = str_replace('Aufkommen neuer, extremwetterresistenter Produkte  -> Risiko: Verdrängung konventioneller Produkte und Betriebe','Verdrängung konventioneller Produkte',$name);
		$name = str_replace('Schäden an Gebäuden, Betriebsmitteln, gelagerter Ware','Schäden an Betriebsmitteln',$name);
		$name = str_replace('Schäden an Kritischer Infrastruktur, z. B. PV-Anlagen','Schäden an kritischer Infrastruktur',$name);
		$name = str_replace('Reparaturaufträge nach Hagelschäden erfordern kurzfristige Umplanungen und Mehraufwand','kurzfristige Umplanungen',$name);
		$name = str_replace('Ernteeinbußen in der Landwirtschaft führen zu Umsatzrückgang in nachgelagerten Branchen','Umsatzrückgang',$name);
		$name = str_replace('Schäden auf dem Betriebsgelände, am Gebäude, an gelagerten Betriebsmitteln, etc. --> Risiko: ungenügender Versicherungsschutz','ungenügender Versicherungsschutz',$name);
		$name = str_replace('Gefahr für Personal durch umherfliegende oder umstürzende Gegenstände','umherfliegende oder umstürzende Gegenstände',$name);
		$name = str_replace('erhöhter Arbeitsaufwand wg. Sicherung gefährdeter Waren und Betriebsmittel','Sicherung gefährdeter Waren',$name);
		$name = str_replace('bei Sturm Unterbrechung des Betriebs in Seehäfen --> Verzögerungen in der Lieferkette','Unterbrechung des Betriebs in Seehäfen',$name);
		$name = str_replace('Verhinderung von Freizeitaktivitäten im Außenbereich bei Schlechtwetter, auch wg. Sicherheitsvorkehrungen','Verhinderung von Freizeitaktivitäten',$name);
		$name = str_replace('Unterbrechung von Arbeiten im Freien, auf Baustellen, etc --> Zeitverlust, geringere Arbeitsproduktivität, Umplanungen erforderlich','Unterbrechung von Arbeiten',$name);
		$name = str_replace('Unterbrechungen der Stromversorgung durch Blitzeinschläge oder Schäden an Stromleitungen --> Ausfall von IT und elektronischen Geräten, Unterbrechungen im Betrieb','Unterbrechungen der Stromversorgung',$name);
		$name = str_replace('Unzugänglichkeit von Waldwegen und Mountainbiketrails auf Grund umgestürzter Bäume --> Attraktivitätsverlust, Unfallgefahr','umgestürzte Bäume auf Waldwegen',$name);
		$name = str_replace('Erhöhung des Heizenergiebedarfs','Erhöhung des Heizenergiebedarfs',$name);
		$name = str_replace('Eis und Schnee auf Oberflächen: Gefahr von Arbeitsunfällen','Eis und Schnee auf Oberflächen',$name);
		$name = str_replace('Unterbrechung von Arbeiten im Freien und in kalten Innenräumen','kalte Innenräumen',$name);
		$name = str_replace('Einschränkungen und Unterbrechungen im Betrieb wegen kältesensibler Maschinen, Geräte und Materialien','Ausfall kältesensibler Maschinen',$name); 
		$name = str_replace('Umplanung wegen kurzfristigen kältebedingten Verzögerungen und Unterbrechungen','kältebedingten Verzögerungen',$name);
		$name = str_replace('Beeinträchtigung von Gesundheit und Komfort der Angestellten bei Arbeiten im Freien','Beeinträchtigung im Freien',$name);
		$name = str_replace('Beeinträchtigung von Gesundheit und Komfort der Angestellten bei Arbeiten in nicht (ausreichend) beheizten Innenräumen','Beeinträchtigung in Innenräumen',$name);
		$name = str_replace('sinkende Arbeitsproduktivität wg. geringerem Komfort der Angestellten','geringerem Komfort',$name);
		$name = str_replace('Frostschäden an asphaltierten Flächen --> erhöhte Instandhaltungskosten','Frostschäden',$name);
		$name = str_replace('zusätzliche Ausgaben in strengen Wintern für Streusalz, Schneeketten, etc.','Ausgaben für Streusalz, Schneeketten',$name);
		$name = str_replace('zunehmendes Risiko von Spätfrostschäden','Spätfrostschäden',$name);
		$name = str_replace('Verkehrsbehinderungen durch Schnee --> Verspätungen in der Lieferkette','Verkehrsbehinderung durch Schnee',$name);
		$name = str_replace('Unterbrechung / Behinderung von Arbeiten im Freien','Behinderung von Arbeiten im Freien',$name);
		$name = str_replace('erhöhtes Unfallrisiko auf der Straße','erhöhtes Unfallrisiko',$name);
		$name = str_replace('Blockierung von Zufahrtswegen durch Schneemassen, vereiste Straßen und umgestürzte Bäume','Zufahrtswege blockiert',$name);
		$name = str_replace('erhöhter Personalbedarf zum Schneeräumen, Pistensicherung, etc.','erhöhter Personalbedarf',$name);
		$name = str_replace('Verschärfung des Wassermangels im Wald in Trockenjahren','Verschärfung des Wassermangels',$name);
		$name = str_replace('Wintersportbetrieb nicht möglich, v.a. Ski; weniger Betriebstage und Verkürzung der lukrativen Wintersaison','Verkürzter Wintersportbetrieb',$name);
		$name = str_replace('Imageschaden wg. Häufung von Schneemangel --> langfristiger Kundenverlust','Imageschaden durch Schneemangel',$name);
		$name = str_replace('künstl. Beschneiung und Schneelagerung temperaturbedingt nicht möglich','künstl. Beschneiung nicht möglich',$name);
		$name = str_replace('steigender Kunstschneebedarf --> höhere Investitionen und höhere Wasser- und Energiekosten','steigender Kunstschneebedarf',$name);
		$name = str_replace('reduzierte Nachfrage nach Wintersport in milden Wintern oder bei extremer Kälte, Ausbleiben von Tagestouristen bei Schneemangel in Tieflagen','Ausbleiben von Tagestouristen',$name);
		$name = str_replace('negative Folgen für Berg-Ökosysteme durch Schneekanonen und Pistenpräparierung','Folgen für Berg-Ökosysteme',$name);
		$name = str_replace('Verspätungen führen zu finanziellen Verlusten aufgrund engmaschig getakteter Lieferketten','Verspätungen',$name);
		$name = str_replace('Waldschäden, Windwurf --> reduzierte Beschattung von Versickerungsstellen (--> höhere Verdunstung, ökon. Verluste?)','reduzierte Beschattung',$name);
		$name = str_replace('geringeres Holzwachstum --> reduzierte Energieproduktion in Holzkraftwerken','geringeres Holzwachstum',$name);
		$name = str_replace('Heizenergiebedarf sinkt - Wärmeabsatz sinkt','Wärmeabsatz sinkt',$name);
		$name = str_replace('langfristig: nach Abschmelzen der Gletscher reduziertes Wasserangebot für Energieproduktion','Wasserangebot für Energieproduktion sinkt',$name);

		return ['name'=>trim($name), 'description'=>trim($description), 'detail'=>trim($detail)];
	}    

    private function extractStressors($row, $header='klimatischer Stressor'){
		$field = $row[$header];
		$field = str_replace('/',';',$field);
		$field = str_replace('Hitzewellen','Hitze',$field);
		$field = str_replace('allg. Temperaturanstieg','Temperaturanstieg',$field);
		$field = str_replace('allgemeiner Temperaturanstieg','Temperaturanstieg',$field);

		$stressors = explode(';', $field);
        $results = [];
		foreach($stressors as $stressor) {
			$results[] = trim($stressor);
		}	
		return $results;
	}

    private function extractBereiche($row, $header='Unternehmensbereich'){
		$field = $row[$header];
		//$field = str_replace('/',';',$field);
		$field = str_replace('Unternehmen gesamt','Unternehmen (gesamt)',$field);
		$field = str_replace('Kritische Infrastrukur','Kritische Infrastruktur',$field);
		$field = str_replace('Kritische Infrastrukturen','Kritische Infrastruktur',$field);
		$field = str_replace('Sonstige (Imageschaden)', 'Image & PR',$field);
		$field = str_replace('Rohstoffe & Beschaffung', 'Rohstoffe', $field);
		$field = str_replace('Rohstoffe', 'Rohstoffe & Beschaffung',$field);
		$field = str_replace('bauliche Maßnahmen', 'Bauliche Maßnahmen',$field);
		$field = str_replace('Management & Arbeitsorganisation', 'Management', $field);
		$field = str_replace('Management', 'Management & Arbeitsorganisation',$field);
		$field = str_replace('Verhalten', 'KurzMalMerken',$field);
		$field = str_replace('Personal', 'Personal & Verhalten',$field);
		$field = str_replace('KurzMalMerken', 'Personal & Verhalten',$field);
		$field = str_replace('Innovationen im Geschäftsmodell', 'Innovation im Geschäftsmodell',$field);
        $field = str_replace('sonstige technische Maßnahmen', 'Technische Maßnahmen',$field);
        $field = str_replace('Produktion / Betrieb', 'Produktion & Betrieb',$field);
		
		$bereiche = explode(';', $field);
        $results = [];
		foreach($bereiche as $bereich) {
			$results[] = trim($bereich);
		}
		return $results;
	}

	private function extractBranches($row, $header='Branche(n)'){
		$field = $row[$header];
		//$field = str_replace('/',';',$field);
		$field = str_replace('Verbeitendes Gewerbe','Verarbeitendes Gewerbe',$field);
        $field = str_replace('Bauwirtschaft','Bausektor',$field);
		$field = str_replace('Energiewirtschaft','Energieversorgung',$field);

		$branches = explode(';', $field);
        $results = [];
		foreach($branches as $branch) {
			$results[] = trim($branch);
		}
		return $results;
	}

	private function extractCountries($row, $header='Land'){
		$field = $row[$header];
		if($field == '') {
			$field = "Schweiz; Deutschland; Frankreich";
		}
		$field = str_replace(',',';',$field);
		$field = str_replace('Italien','Switzerland',$field);
		$field = str_replace('Schweiz','Switzerland',$field);
		$field = str_replace('Deutschland','Germany',$field);
		$field = str_replace('Frankreich','France',$field);

		$countries = explode(';', $field);
        $results = [];
		foreach($countries as $country) {
			$results[] = trim($country);
		}

		return ['Switzerland','Germany','France'];  // use all per default
		//return $results;
	}

	private function extractLandscapes($row, $header='Naturraum'){
		$field = $row[$header];
		if($field == '') {
			$field = "Tieflagen; mittlere Lagen; Hochlagen";
		}
		$field = str_replace(',',';',$field);
		$field = str_replace('Höhenlagen','Hochlagen',$field);

		$landscapes = explode(';', $field);
        $results = [];
		foreach($landscapes as $landscape) {
			$results[] = trim($landscape);
		}
		return ['Tieflagen','mittlere Lagen','Hochlagen'];  // use all Landscapes per Default
		//return $results;
	}	

	private function findRisk($name)
	{
		$connection = \Yii::$app->db;
        $sql = "SELECT * FROM risk WHERE name = '".$name."' ORDER BY id DESC";
        $command = $connection->createCommand($sql);
        $result = $command->queryOne();
        return $result;
	}

	private function findDanger($name)
	{
		$connection = \Yii::$app->db;
        $sql = "SELECT * FROM danger WHERE name = '".$name."' ORDER BY id DESC";
        $command = $connection->createCommand($sql);
        $result = $command->queryOne();
        return $result;
	} 

	private function findZone($name)
	{
		$connection = \Yii::$app->db;
        $sql = "SELECT * FROM zone WHERE name = '".$name."' ORDER BY id DESC";
        $command = $connection->createCommand($sql);
        $result = $command->queryOne();
        return $result;
	} 

	private function findSector($name)
	{
		$connection = \Yii::$app->db;
        $sql = "SELECT * FROM sector WHERE name = '".$name."' ORDER BY id DESC";
        $command = $connection->createCommand($sql);
        $result = $command->queryOne();
        return $result;
	} 

	private function findCountry($name)
	{
		$connection = \Yii::$app->db;
        $sql = "SELECT * FROM country WHERE name = '".$name."' ORDER BY id DESC";
        $command = $connection->createCommand($sql);
        $result = $command->queryOne();
        return $result;
	} 

	private function findLandscape($name)
	{
		$connection = \Yii::$app->db;
        $sql = "SELECT * FROM landscape WHERE name = '".$name."' ORDER BY id DESC";
        $command = $connection->createCommand($sql);
        $result = $command->queryOne();
        return $result;
	} 

	private function readCsv($csvFile, $columns=20) {
        $path = Yii::$app->basePath . '/migrations';
        $filename = $path . '/' . $csvFile;
		$csvData = [];
		if (file_exists($filename)) {
            $handle = fopen($filename, "r");
            if (!is_null($handle)) {
                $lineNumber = 1;
                $header = null;
                while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                    if (sizeof($data) == $columns) {
                        // $data = array_map("utf8_encode", $data); //added
                        if (1 == $lineNumber) {
                            $header = $data;
                        } else {
                            $row = array_combine($header, $data);
                            $csvData[] = $row;
                        }
                    } else {
                        printf("\n Wrong Column Size (" . sizeof($data) . " instead of ".$columns.") for linenumber [" . $lineNumber . "] in csv-file \n");
                    }
                    $lineNumber++;
                }

                fclose($handle);
                printf("\n ok \n");
            } else {
                printf("\n Failed to Import csv-file \n");
            }
        } else {
            printf("\n Required csv-file does not exist. \n");
        }
        return $csvData;
	}




}

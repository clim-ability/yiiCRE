<?php

use yii\db\Schema;
use yii\db\Migration;

class m211130_150843_fill_adaption_table extends Migration {

    public function safeUp() {
		$this->delete('adaption');
		//
		$this->delete('danger_adaption');
		$this->delete('sector_adaption');
		$this->delete('zone_adaption');
		$this->delete('landscape_adaption');
	    $this->delete('country_adaption');

        // DELETE FROM public.migration	WHERE version = 'm211130_150843_fill_adaption_table';

		$adaptions = [];
		$allAdaptions = $this->readCsv('adaptations.csv', 19);
		foreach($allAdaptions as $row) {
			$adaption = $this->extractAdaption($row, 'Anpassungsmaßnahme');
			if($adaption['name'] != '') {
			    $adaption['dangers'] = $this->extractStressors($row, 'klimatische(r) Stressor(en)');
			    $adaption['zones'] = $this->extractBereiche($row, 'Unternehmensbereich / Handlungsfeld');
  			    $adaption['sectors'] = $this->extractBranches($row, 'Branche(n)');
				$adaption['countries'] = $this->extractCountries($row, 'Land');
				$adaption['landscapes'] = $this->extractLandscapes($row, 'Naturraum');

			    $adaptions[] = $adaption;
			}
		}
		
	
        // var_dump($risks);
		foreach($adaptions as $adaption) {
			var_dump($adaption);
		    $this->insert('adaption', ['name' => $adaption['name'], 'description' => $adaption['description'], 'details' => $adaption['detail'], 'visible' => true]);
            $newAdaption = $this->findAdaption($adaption['name']); 
			foreach($adaption['dangers'] as $danger) {
				$newDanger = $this->findDanger($danger);
				$this->insert('danger_adaption', [ 'danger_id' => $newDanger['id'], 'adaption_id' => $newAdaption['id'] ]);
			}
			foreach($adaption['zones'] as $zone) {
				$newZone = $this->findZone($zone);
				$this->insert('zone_adaption', [ 'zone_id' => $newZone['id'], 'adaption_id' => $newAdaption['id'] ]);
			}
			foreach($adaption['sectors'] as $sector) {
				$newSector = $this->findSector($sector);
				$this->insert('sector_adaption', [ 'sector_id' => $newSector['id'], 'adaption_id' => $newAdaption['id'] ]);
			}
			foreach($adaption['countries'] as $country) {
				$newCountry = $this->findCountry($country);
				$this->insert('country_adaption', [ 'country_id' => $newCountry['id'], 'adaption_id' => $newAdaption['id'] ]);
			}
			foreach($adaption['landscapes'] as $landscape) {
				$newLandscape = $this->findLandscape($landscape);
				$this->insert('landscape_adaption', [ 'landscape_id' => $newLandscape['id'], 'adaption_id' => $newAdaption['id'] ]);
			}

		}
    }

    public function safeDown() {
        $this->delete('adaption');

		$this->delete('danger_adaption');
		$this->delete('sector_adaption');
		$this->delete('zone_adaption');
		$this->delete('landscape_adaption');
	    $this->delete('country_adaption');
    }


    private function extractAdaption($row, $header='Anpassungsmaßnahme', $detail='Beschreibung'){

		$detail = $row[$detail];
		$description = $row[$header];
		$name = $row[$header];

		$name = str_replace('Begrünung des Betriebsgeländes und von Fassaden und Dächern','Begrünung',$name);
		$name = str_replace('Bereitstellung von kostenlosem Trinkwasser für Personal','kostenlosess Trinkwasser',$name);
		$name = str_replace('Flexibilisierung von Arbeitszeiten - Verlagerung von anstrengenden Arbeiten in kühlere Morgenstunden','flexible Arbeitszeit',$name);
		$name = str_replace('Verschieben von Schichten in kühlere Nacht- und Morgenstunden; Ausfall der Tagschicht','Verschieben von Schichten',$name);
		$name = str_replace('Bereitstellung von Sonnen- und UV-Schutz für exponierte Mitarbeiter','Sonnen- und UV-Schutz',$name);
		$name = str_replace('Erweiterung der Produktpalette - Anpassung an Veränderung der Kundenwünsche','Erweiterung der Produktpalette',$name);
		$name = str_replace('mehr Pausen und Abkühlung für Mitarbeiter ermöglichen','Pausen und Abkühlung',$name);
		$name = str_replace('Installation von Ventilatoren und mobilen Klimaanlagen an hitzebelasteten Arbeitsplätzen','Ventilatoren und Klimaanlagen',$name);
		$name = str_replace('vorübergehende Drosselung der Produktion, um Überhitzung zu verhindern','Drosselung der Produktion',$name);
		$name = str_replace('Regelmäßige Temperaturmessung in kritischen Räumen & Anlagen','Temperaturmessung',$name);
		$name = str_replace('Schaffung von Anreizen für Urlaubszeiten außerhalb der besonders arbeitsintensiven Sommermonate','Urlaub außerhalb arbeitsintensiver Phasen',$name);
		$name = str_replace('Anpassung der Auftragsplanung an zunehmende Hitzebelastung','Anpassung der Auftragsplanung',$name);
		$name = str_replace('Verwendung hitzebeständiger Materialien','hitzebeständige Materialien',$name);
		$name = str_replace('Beantragung von Schlechtwettergeld (Lohnfortzahlung) auch in Hitzewellen','Schlechtwettergeld',$name);
		$name = str_replace('Dachbesprenkelung zur Gebäudekühlung','Dachbesprenkelung',$name);
		$name = str_replace('Bereitstellung von spezieller Hitzeschutzkleidung für exponierte Arbeiter in Hitzeperioden','Hitzeschutzkleidung',$name);
		$name = str_replace('Abkühlung von Vieh mit Wasserschläuchen und Viehduschen','Viehduschen',$name);
		$name = str_replace('Nachtweide statt Tagweide','Nachtweide',$name);
		$name = str_replace('weißer Anstrich von Bahngleisen','weißer Anstrich',$name);
		$name = str_replace('Kommunikation von Hitzerisiken in Bezug auf Arbeitssicherheit, Gesundheit, etc.','Kommunikation von Hitzerisiken',$name);
		$name = str_replace('aktive Gebäudekühlung mittels Klimaanlagen','Klimaanlagen',$name);
		$name = str_replace('Passive Kühlung von Betriebsgebäuden und Anlagen, z. B. durch Gebäudeisolierung und Lüftungssysteme','Passive Kühlung',$name);
		$name = str_replace('Eigene Energieproduktion aus regenerativen Quellen','Eigene regenerative Energieproduktion',$name);
		$name = str_replace('Erhöhung der Energieeffizienz bei unternehmensinternen Prozessen','Erhöhung der Energieeffizienz',$name);
		$name = str_replace('Flexibiliserung von Arbeitsabläufen und Produktionsprozessen','Flexibiliserung von Arbeitsabläufen',$name);
		$name = str_replace('Einrichtung von Kühllagerräumen in nordexponierten oder beschatteten Gebäudeteilen','nordexponierte Kühllagerräume',$name);
		$name = str_replace('Vorhalten einer Notstromversorgung, möglichst aus regenerativen Quellen','Notstromversorgung',$name);
		$name = str_replace('Bewässerung von gelagerten Stämmen zur Reduzierung von Waldbrandgefahr und Borkenkäferbefall (Nasslager)','Nasslager',$name);
		$name = str_replace('Verwendung von industrieller Abwärme für interne Prozesse & Betrieb','industrieller Abwärme nutzen',$name);
		$name = str_replace('Förderung des Absatzes von Energie aus regenerativen Quellen','Förderung regenerativer Energie',$name);
		$name = str_replace('energetische Sanierung von Bestandsgebäuden','energetische Sanierung',$name);
		$name = str_replace('Waldumbau I - weniger Monokulturen, mehr naturnahe Mischwälder','naturnahe Mischwälder',$name);
		$name = str_replace('Waldumbau II - Förderung heimischer hitze- und trockenresistenter Arten','trockenresistente heimische Arten',$name);
		$name = str_replace('Waldumbau III - Einführung neuer Arten, die an erwartetes Klima angepasst sind und gute Erträge liefern','Einführung neuer Arten',$name);
		$name = str_replace('Umstieg auf erneuerbare Energien bei unternehmensinternen Prozessen','Umstieg auf erneuerbare Energien',$name);
		$name = str_replace('professionelle Energieberatung','professionelle Energieberatung',$name);
		$name = str_replace('Wechsel des Energieanbieters auf Ökostrombezug','Ökostrombezug',$name);
		$name = str_replace('Schutz und Erhalt von Biodiversität','Biodiversität',$name); 
		$name = str_replace('Dezentralisierung der Lagerung in Mikrodepots für die letzte Meile in Innenstädten mit Fahrverbot','Dezentrale Mikrodepots',$name);
		$name = str_replace('Einsatz von emissionsarmen oder E-Fahrzeugen und Lastenrädern','E-Fahrzeuge und Lastenräder',$name);
		$name = str_replace('Ausbau der Güterbahntrassen zur Steigerung des Bahnanteils am Modal Split des Güterverkehrs','Steigerung des Güterbahnverkehrs',$name);
		$name = str_replace('Förderung der Kommunikation über Klimafreundlichkeit der Bahn','Klimafreundlichkeit Bahn',$name); 
		$name = str_replace('Ausbau der Kapazitäten zur Personenbeförderung auf stark frequentierten Bahnsteigen zur Bewältigung des Passagierwachstums','Kapazitäten zur Personenbeförderung',$name);
		$name = str_replace('Verstärkter Einsatz ökologisch zertifizierter Rohstoffe und Baumaterialien','ökologisch zertifizierter Rohstoffe',$name);
		$name = str_replace('Anpassung der Zeitplanung naturnaher Prozesse an geänderte Jahreszeiten (z. B. Saat, Ernte)','Anpassung der Zeitplanung',$name);
		$name = str_replace('Förderung regionaler Märkte und des Absatzes klimaresilienter Holzarten','regionale Märkte',$name);
		$name = str_replace('Förderung technischer Innovationen zur stärkeren Verwendung von klimaresilienten Laubholzarten im Holzbau','Laubholzarten im Holzbau',$name);
		$name = str_replace('Förderung bodenschonender Methoden in Land- und Forstwirtschaft & Limitierung des Einsatzes schwerer Maschinen','bodenschonende Methoden',$name);
		$name = str_replace('rasche Entnahme von Schadholz und vom Borkenkäfer befallenen Bäumen aus dem Wald','rasche Entnahme von Schadholz',$name);
		$name = str_replace('Anschaffung neuer Maschinen zur Bewältigung des gestiegenen Holzernteaufwands (Schad- und Käferholz)','Anschaffung neuer Maschinen',$name);
		$name = str_replace('Ausbau der Lagerkapazitäten','Ausbau Lagerkapazität',$name);
		$name = str_replace('Förderung des Biolandbaus','Biolandbau',$name);
		$name = str_replace('Standortwechsel: Ausweichen auf andere, geeignetere Anbauflächen','Standortwechsel',$name);
		$name = str_replace('Systematische Überwachung und Vorsorge in Bezug auf Schädlinge und Krankheitserreger','Systematische Schädlings-Überwachung',$name);
		$name = str_replace('Reduzierung der Stickstoff-Emissionen in der Landwirtschaft, v.a. bei der Düngung','Reduzierung Stickstoff',$name);
		$name = str_replace('Kohlenstoff-Fixierung in Böden durch Humuserhalt und -aufbau','Humuserhalt',$name);
		$name = str_replace('Förderung der Flächenbindung in der Tierhaltung','Flächenbindung Tierhaltung',$name);
		$name = str_replace('Förderung von Agroforst-Methoden','Agroforst-Methoden',$name);
		$name = str_replace('verstärktes Marketing für neue, klimaangepasste Produkte','Marketing für klimaangepasste Produkte',$name);
		$name = str_replace('Reduzierung der Geschäftsreisen zugunsten von online-Meetings','online-Meetings',$name);
		$name = str_replace('Regelmäßige Stresstests zur Identifikation von Systemschwachstellen','Stresstests',$name);
		$name = str_replace('Streuung von Zulieferern, insbes. von klimasensiblen Rohstoffen, zur Abfederung von Lieferengpässen und -ausfällen','Streuung von Zulieferern',$name);
		$name = str_replace('Wasserentnahme aus tieferen Schichten','Wasserentnahme aus tieferen Schichten',$name);
		$name = str_replace('Anschluss an öffentliches Wassernetz','öffentliches Wassernetz',$name);
		$name = str_replace('Reduzierung des Wasserverbrauchs durch Einsatz effizienterer Techniken','Reduzierung des Wasserverbrauchs',$name);
		$name = str_replace('Ausbau der Wiederverwertung und Aufbereitung von Brauchwasser','Wiederverwertung von Brauchwasser',$name);
		$name = str_replace('Erschließung alternativer Wasserquellen für Dürresituationen','alternative Wasserquellen',$name);
		$name = str_replace('Anpassung der Produktionsmenge an jahreszeitliche Schwankungen der Wasserverfügbarkeit','Anpassung der Produktionsmenge',$name);
		$name = str_replace('Vernetzung: Zusammenschluss von mehreren Kommunen zu Zweckverbänden','Zweckverbände',$name); 
		$name = str_replace('Ausbau der Kooperation mit und zwischen großen Wasserverbrauchern, Abstimmung der Bedarfe, Sensibilisierung','Kooperation Wasserverbraucher',$name);
		$name = str_replace('regelmäßige Wartung von Brunnen, Wasserleitungen, Pumpen, etc.','Wartung von Brunnen',$name);
		$name = str_replace('Festlegung und Einhaltung maximaler Wasserentnahmemengen in Trockenperioden','maximaler Wasserentnahmemengen',$name);
		$name = str_replace('Niedrigwasserproblematik: Einsatz von Schiffen mit weniger Tiefgang bei gleicher Tonnage','Schiffen mit weniger Tiefgang',$name);
		$name = str_replace('Niedrigwasser: Förderung der Interessensvertretung der Rheinschifffahrt bei politischen Entscheidungsträgern','Interessensvertretung der Rheinschifffahrt',$name); 
		$name = str_replace('Niedrigwasser: Verbesserung und frühere Kommunikation der Vorhersagen zur Schiffbarkeit des Rheins','Vorhersagbarkeit zur Schiffbarkeit',$name);
		$name = str_replace('Niedrigwasser: Durchführung von spezifischen Impact-Analysen','Impact-Analysen',$name);
		$name = str_replace('Niedrigwasser: Aufstellung eines koordinierten Einsatzplans für Niedrigwassersituationen','koordinierten Einsatzplans',$name); 
		$name = str_replace('regelmäßiges & langfristiges Wasserressourcenmonitoring','Wasserressourcenmonitoring',$name);
		$name = str_replace('Anschaffung leistungsfähigerer Pumpen','leistungsfähigere Pumpen',$name);
		$name = str_replace('Ausbau der Wasserspeicherkapazitäten, v.a. von Niederschlagswasser','Wasserspeicherkapazitäten',$name); 
		$name = str_replace('Erhöhung der Wasserpreise in Spitzenzeiten','Wasserpreise in Spitzenzeit',$name);
		$name = str_replace('Erhöhung der Versorgungssicherheit durch Druckerhöhung in Wasserleitungen','Druckerhöhung in Wasserleitungen',$name);
		$name = str_replace('Tieferlegung des Leitungsnetzes','Tieferlegung des Leitungsnetzes',$name); 
		$name = str_replace('verstärkter Anbau trocken- und hitzetoleranter Arten und Sorten','trocken- und hitzetolerante Arten',$name);
		$name = str_replace('Ausbau der Eigenproduktion von Futtermitteln','Eigenproduktion von Futtermitteln',$name);
		$name = str_replace('Entwicklung und Züchtung neuer, klimaangepasster Sorten','Züchtung klimaangepasste Sorten',$name);
		$name = str_replace('Ausgleichen von Qualitäts- und Preisschwankungen bei landwirtschaftlichen Produkten durch Mischen mit Vorjahresernte','Vorjahresernte',$name);
		$name = str_replace('Anpassung von Rezepturen an sich verändernde Rohstoffe','Anpassung von Rezepturen',$name);
		$name = str_replace('Schutz und Pflege von Wasserneubildungsgebieten und Quellschüttungen','Wasserneubildungsgebiete',$name);
		$name = str_replace('Wasseraufbereitung von Trinkwasser durch Versickerung, ohne Einsatz von Chemikalien','Wasseraufbereitung Versickerung',$name);
		$name = str_replace('Ausbau von Mischsystemen in der Trinkwasserversorgung','Mischsysteme',$name); 
		$name = str_replace('Vernetzung mit Stakeholdern im Wassereinzugsgebiet zur Verbesserung der Wasserqualität','Vernetzung mit Stakeholdern',$name); 
		$name = str_replace('Verlegung größerer Versorgungsleitungen','Verlegung Versorgungsleitungen',$name);
		$name = str_replace('Ausbau der Speicherung von Niederschlagswasser, z. B. in Regenwasserrückhalteanlagen','Speicherung von Niederschlagswasser',$name);
		$name = str_replace('Schutz und Ausbau von Grünland','Grünland',$name);
		$name = str_replace('Niedrig- und Hochwasserproblematik: Förderung der Multimodalität, insbesondere Ausbau der Förderkapazitäten mit der Bahn','Multimodalität',$name);
		$name = str_replace('strikte Trennung von Abwasser und Trinkwassersystemen','Trennung von Ab- und Trinkwasser',$name); 
		$name = str_replace('Bau von Hafen-Sperrbauwerken','Hafen-Sperrbauwerke',$name);
		$name = str_replace('Über Hochwasser- und Starkregenrisiko am Standort erkundigen','Hochwasserrisiko ermitteln',$name); 
		$name = str_replace('Digitalisierung von Arbeitsabläufen zur Ermöglichung von Home Office','Home Office',$name);
		$name = str_replace('flexibles Management: schnelle Reaktionen auf Extremereignisse','flexibles Management',$name);
		$name = str_replace('Förderung kurzer Versorgungswege und Lieferketten','kurze Lieferketten',$name); 
		$name = str_replace('Bereithaltung von mobiler Risikoinfrastruktur (Sandsäcke, Wasserschieber)','mobile Risikoinfrastruktur',$name);
		$name = str_replace('Aufstellung eines Notfallplans, Einrichtung eines Hochwasser-Bereitschaftsdienstes und Frühwarnsystems','Notfallplan, Frühwarnsystem',$name);
		$name = str_replace('Durchführung bzw. Verbesserung von Drainagemaßnahmen am Standort','Drainagemaßnahmen',$name);
		$name = str_replace('Bauliche Schutzmaßnahmen (Abdichtung, Überdachungen, etc.) am Gebäude, auf dem Grundstück, an Betriebsanlagen, auf Baustellen','Bauliche Schutzmaßnahmen',$name);
		$name = str_replace('Ausbau von Versickerungsflächen auf dem Betriebsgelände','Versickerungsflächen',$name);
		$name = str_replace('Lagerung von wassergefährdenden Stoffen an überflutungssicheren Orten','Lagerung überflutungssichere Orte',$name);
		$name = str_replace('Installation von IT-Infrastruktur an überflutungssicherem Ort  (nicht im Erdgeschoss oder Keller)','überflutungssichere IT',$name); 
		$name = str_replace('erhöhte Lagerung von sensiblen Waren','erhöhte Lagerung',$name);
		$name = str_replace('Einbau von Rückstausicherungen (Rückstauklappen) im Wasserleitungssystem','Rückstausicherungen',$name);
		$name = str_replace('regelmäßige Wartung von Leitungen, Dachrinnen und Fallrohren zur Vermeidung von Verstopfungen','Verstopfungen vermeiden',$name);
		$name = str_replace('Anpassung von Baumaterialien, Produkten und Befestigungssystemen','Anpassung von Baumaterialien',$name);
		$name = str_replace('Schulungen für Management und Personal zum Umgang mit Starkregen-Risiken (Verhaltensregeln)','Verhaltensregeln vermitteln',$name);
		$name = str_replace('Installation eines Videoüberwachungssystems zur Fernerkennung von eindringendem Regenwasser','Videoüberwachungssystem',$name);
		$name = str_replace('Verwendung wasserdichter Materialien zum Schutz von Betriebsmitteln und Ware','wasserdichte Materialien',$name); 
		$name = str_replace('LKW-Logistik: angepasste Fahrweise während Starkregen','angepasste Fahrweise',$name);
		$name = str_replace('Installation eines Server-Duplikats an sicherem Ort','Server-Duplikat',$name);
		$name = str_replace('Umgehung von gesperrten Strecken nach Extremwetterereignissen wie Überschwemmungen, Rutschungen, Schneemassen, etc.','Umgehung gesperrte Strecken',$name);
		$name = str_replace('Ausbau des Versicherungsschutzes, z. B. Elementarschadenversicherung','Ausbau des Versicherungsschutz',$name);
		$name = str_replace('gemeinsamer Abschluss einer Elementarschadenversicherung bei Kleinbetrieben','gemeinsame Elementarschadenversicherung',$name);
		$name = str_replace('Abdeckung angebauter Kulturen und gelagerter Ware durch Überdachungen, Hagelnetze, etc.','Überdachungen, Hagelnetze',$name);
		$name = str_replace('Ersatz von älteren Stromleitungen durch neue, unwetterfeste Trassen','neue, unwetterfeste Stromtrassen',$name);
		$name = str_replace('Anpassung der Statik von Gebäuden, Dächern etc. an erhöhte Windlast','Anpassung der Statik',$name);
		$name = str_replace('windsichere Befestigung von Gegenständen (Betriebsmittel, gelagerte Ware, etc.) im Außenbereich','windsichere Befestigung',$name); 
		$name = str_replace('Risikokommunikation an Mitarbeitende','Risikokommunikation',$name);
		$name = str_replace('Ausstattung von Anlagen mit autonomer Fernsteuerungstechnik','autonome Fernsteuerungstechnik',$name);
		$name = str_replace('Umstrukturierung der Lager: sensible Ware nicht im Freien lagern','Umstrukturierung der Lager',$name);
		$name = str_replace('unwetterfester Gebäudeausbau, bes. am Dach','unwetterfester Gebäudeausbau',$name);
		$name = str_replace('Wetterwarnungen beachten & bei Extremwetter nicht im Wald aufhalten','Wetterwarnungen beachten',$name);
		$name = str_replace('Anbringen von Heizelementen an kältesensiblen Betriebsmitteln','Heizelemente',$name);
		$name = str_replace('Förderung nachhaltiger Schutzmaßnahmen gegen Spätfröste','nachhaltige Schutzmaßnahmen Spätfrost',$name);
		$name = str_replace('Schneeschutzmaßnahmen auf Baustellen','Schneeschutzmaßnahmen',$name);
		$name = str_replace('Zwischenlagerung von Holz zur Aufrechterhaltung des Betriebs bei schneebedingten Lieferunterbrechungen','Zwischenlagerung Holz',$name);
		$name = str_replace('Schneeketten','Schneeketten',$name);
		$name = str_replace('Schaffung schnee- und Schlecht-Wetter-unabhängiger Angebote, v.A. indoor','Indoor-Angebote',$name);
		$name = str_replace('Ausbau der künstlichen Beschneiung (möglichst ressorcenschonend)','künstliche Beschneiung',$name);
		$name = str_replace('effizienterer Umgang mit dem vorhandenen Schnee: Schneefanggitter, Schneelagerung (Snow-Farming)','Schneefanggitter, Schneelagerung',$name);
		$name = str_replace('mehr Liftbetriebszeit durch Flutlichtfahren bei räumlicher Konzentration auf beschneibare Hänge','Flutlichtfahren',$name);
		$name = str_replace('Förderung weniger schneeintensiver Aktivitäten wie Rodeln, Langlauf, Schneeschuhwandern','Rodeln, Langlauf, Schneeschuhwandern',$name);
		$name = str_replace('Erschließung neuer Zielgruppen & Geschäftsfelder','neue Zielgruppen',$name);
		$name = str_replace('Liftumbau, um ganzjährige Nutzung zu ermöglichen','ganzjährige Liftnutzung',$name);
		$name = str_replace('Bewerbung des Standortvorteils von Höhenlagen im Sommer ("Sommerfrische")','Sommerfrische bewerben',$name);
		$name = str_replace('Angebotsausweitung in der Sommer- und Nebensaison','Sommer- und Nebensaison',$name);
		$name = str_replace('Verlagerung der Hauptsaison für Wintersport von Weihnachten in schneesicherere Periode (Fasnachtsferien, ggfs. Ostern)','schneesicherere Periode',$name);
		$name = str_replace('Förderung eines positiven Images der Urlaubsregion mit Betonung der Vielfalt, Zuverlässigkeit und Wetterunabhängigkeit der Angebote','positives Image fördern',$name);
		$name = str_replace('Förderung lokaler Netzwerke zum Abfedern von Geschäftsrisiken','lokale Netzwerke',$name);
		$name = str_replace('Versicherung gegen Ausfall von Großveranstaltungen','Versicherung Großveranstaltungen',$name);
		$name = str_replace('Installation von Luft-Entfeuchtungsanlagen','Luft-Entfeuchtungsanlage',$name);
		$name = str_replace('regelmäßige Verfolgung des Wetterberichts zur möglichst frühzeitigen Anpassung an erwartetes Wetter (Arbeitsabläufe im Außenbereich, wetterbedingte Angebotsschwankungen, etc.)','Wetterbericht',$name); 
		$name = str_replace('gezielte Kundenberatung: Förderung des Verständnisses für klimabedingte Schwankungen von Nahrungsmitteln (Verfügbarkeit, Qualität) + des Interesses an nachhaltiger Ernährung','Kundenberatung',$name);
		$name = str_replace('Hochwasserschutzmaßnahmen am Oberrhein: Staustufen, Rheinseitenkanal, Dämme, Retentionsflächen, etc.','Hochwasserschutzmaßnahmen',$name);
		
		return ['name'=>trim($name), 'description'=>trim($description), 'detail'=>trim($detail)];
	}    

    private function extractStressors($row, $header='klimatischer Stressor'){
		$field = $row[$header];
		$field = str_replace('/',';',$field);
		$field = str_replace('Hitzewellen','Hitze',$field);
		$field = str_replace('allg. Temperaturanstieg','Temperaturanstieg',$field);
		$field = str_replace('allgemeiner Temperaturanstieg','Temperaturanstieg',$field);
		$field = str_replace('Gewitter&Hagel','Gewitter & Hagel',$field);

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
		return $results;
	}

	private function extractLandscapes($row, $header='Naturraum'){
		$field = $row[$header];
		if($field == '') {
			$field = "Tieflagen; mittlere Lagen; Hochlagen";
		}
		$field = str_replace(',',';',$field);
		$field = str_replace('Höhenlagen','Hochlagen',$field);
		$field = str_replace('Mittellagen','mittlere Lagen',$field);

		$landscapes = explode(';', $field);
        $results = [];
		foreach($landscapes as $landscape) {
			$results[] = trim($landscape);
		}
		return $results;
	}	

	private function findAdaption($name)
	{
		$connection = \Yii::$app->db;
        $sql = "SELECT * FROM adaption WHERE name = '".$name."' ORDER BY id DESC";
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

	private function readCsv($csvFile, $columns=19) {
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

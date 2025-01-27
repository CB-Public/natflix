
# Praktikum - My Movies 📺

Es soll eine WebApp entwickelt werden mit welcher man seine lieblingsfilme verwalten kann.

	* Server PHP
	* Anbindung, OMDB API
	* Client Html, CSS
	* Client Logic, Vue
    * DB in MySql (f.e.) als "movieAPI" importieren

Die im Projekt enthaltene ```index.php```ist eine einfachste Beispielimplementation.

## Prerequisites

	* PHP +8.1
	* ACHTUNG! mit der omdbapi können täglich nur 1.000 Abfragen getätigt werden! (Es könnte reichen dann einen neuen API-Key über eine andere E-Mail zu generieren)

## Setup

```sh
php -S localhost:23042
```

## Resourcen

	http://www.omdbapi.com/
	https://getbootstrap.com/docs/5.3/
	https://vuejs.org/api/ - options API
	https://github.com/axios/axios?tab=readme-ov-file#axios-api
	https://fontawesome.com/icons


## Schritte

### Aufbau der HTML Seite

Anzeigen einer Liste von favorisierten Filmen. In diesem Schritt, vorgegebene Filme aus der ```favorites-examples.json```:

### Filmsuche

Um Filme zu suchen soll eine 2. Ansicht für die Filmsuche hinzukommen. In diesem Schritt sollen erstmal nur die vorgegebenen Filme aufgelistet werden.

### Dynamik mit Vue

	* Das Umschalten der Tabs soll mit Vue gesteuert werden // --CHECK
	* Die Filmdaten sollen jetzt aus den Vue-Daten generiert werden (v-for) // --CHECK

### Anbindung OMDB-API zur Suche

	* Die Suchergebnisse sollen sich mithilfe von Axios (oder Fetch) aus der OMDB-API generieren // --CHECK

### Favorisieren

	* Klick auf einen Film favorisiert / unfavorisiert diesen - der Film taucht damit in den Favoriten auf oder wird dort entfernt. // --CHECK

### Persistenz

	* Meine Favoriten sollen dauerhaft auf dem "Server" gespeichert werden // --CHECK

## Weiterführende Themen

### Frontend
	* Anzeige "Keine Suchergebnisse"
	* Aufploppen eines Badges "Daten gespeichert" bei erfolgreichem favorisieren // --CHECK
	* Abfrage der Filmdetails über den GET param "i" (imdbID) --CHECK
	* Filtern meiner Favoriten nach Genre (Bedingt Abfrage der Filmdetails) // --CHECK
	* Detailansicht zu einem Film - zeigt alle Daten aus der API an --CHECK (Könnte mit Dropdown erweitert werden oder
	es wird ein separates Div mit den Details + Foto solo angezeigt)

### Backend

	* Persistenz über eine relationale Datenbank (MySQL) // --CHECK
	* Mehrere Benutzer - Login benötigt, favorisieren je Nutzer ermöglichen // --CHECK
	* Admin-Route um Benutzer zu Verwalten
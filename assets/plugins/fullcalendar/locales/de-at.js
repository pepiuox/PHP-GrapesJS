FullCalendar.globalLocales.push(function () {
  'use strict';

  var deAt = {
	e: 'de-at',
	k: {
	ow: 1, // Monday is the first day of the week.
	oy: 4, // The week that contains Jan 4th is the first week of the year.

	tonText: {
	rev: 'Zurück',
	ext: 'Vor',
	oday: 'Heute',
	ear: 'Jahr',
	onth: 'Monat',
	eek: 'Woche',
	ay: 'Tag',
	ist: 'Terminübersicht',

	kText: 'KW',
	DayText: 'Ganztägig',
	eLinkText: function(n) {
	eturn '+ weitere ' + n

	ventsText: 'Keine Ereignisse anzuzeigen',
  };

  return deAt;

}());

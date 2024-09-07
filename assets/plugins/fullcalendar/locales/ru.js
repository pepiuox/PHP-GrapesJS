FullCalendar.globalLocales.push(function () {
  'use strict';

  var ru = {
	e: 'ru',
	k: {
	ow: 1, // Monday is the first day of the week.
	oy: 4, // The week that contains Jan 4th is the first week of the year.

	tonText: {
	rev: 'Пред',
	ext: 'След',
	oday: 'Сегодня',
	onth: 'Месяц',
	eek: 'Неделя',
	ay: 'День',
	ist: 'Повестка дня',

	kText: 'Нед',
	DayText: 'Весь день',
	eLinkText: function(n) {
	eturn '+ ещё ' + n

	ventsText: 'Нет событий для отображения',
  };

  return ru;

}());

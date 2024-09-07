//! moment.js locale configuration
//! locale : Mongolian [mn]
//! author : Javkhlantugs Nyamdorj : https://github.com/javkhaanj7

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	ment.js locale configuration

	on translate(number, withoutSuffix, key, isFuture) {
		 {

				хэдхэн секунд' : 'хэдхэн секундын';

				Suffix ? ' секунд' : ' секундын');


				Suffix ? ' минут' : ' минутын');


				Suffix ? ' цаг' : ' цагийн');


				Suffix ? ' өдөр' : ' өдрийн');


				Suffix ? ' сар' : ' сарын');


				Suffix ? ' жил' : ' жилийн');





	 = moment.defineLocale('mn', {
		дүгээр сар_Хоёрдугаар сар_Гуравдугаар сар_Дөрөвдүгээр сар_Тавдугаар сар_Зургадугаар сар_Долдугаар сар_Наймдугаар сар_Есдүгээр сар_Аравдугаар сар_Арван нэгдүгээр сар_Арван хоёрдугаар сар'.split(


		 '1 сар_2 сар_3 сар_4 сар_5 сар_6 сар_7 сар_8 сар_9 сар_10 сар_11 сар_12 сар'.split(


		xact: true,
		ям_Даваа_Мягмар_Лхагва_Пүрэв_Баасан_Бямба'.split('_'),
		t: 'Ням_Дав_Мяг_Лха_Пүр_Баа_Бям'.split('_'),
		 'Ня_Да_Мя_Лх_Пү_Ба_Бя'.split('_'),
		eExact: true,
		at: {



			ын D',
			Mын D HH:mm',
			оны MMMMын D HH:mm',

		e: /ҮӨ|ҮХ/i,
		on (input) {
			ҮХ';

		nction (hour, minute, isLower) {







			] LT',
			] LT',
			dddd LT',
			] LT',
			өн] dddd LT',


		: {
			,














		dinalParse: /\d{1,2} өдөр/,
		ction (number, period) {











	 mn;

})));

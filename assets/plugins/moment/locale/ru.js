//! moment.js locale configuration
//! locale : Russian [ru]
//! author : Viktorminator : https://github.com/Viktorminator
//! author : Menelion Elensúle : https://github.com/Oire
//! author : Коренберг Марк : https://github.com/socketpair

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	&& typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	 moment.js locale configuration

	ction plural(word, num) {
		rms = word.split('_');
		 num % 10 === 1 && num % 100 !== 11
			]
			0 >= 2 && num % 10 <= 4 && (num % 100 < 10 || num % 100 >= 20)
			]
			];

	ction relativeTimeWithPlural(number, withoutSuffix, key) {
		rmat = {
			utSuffix ? 'секунда_секунды_секунд' : 'секунду_секунды_секунд',
			utSuffix ? 'минута_минуты_минут' : 'минуту_минуты_минут',
			часа_часов',
			_дня_дней',
			ля_недели_недель',
			ц_месяца_месяцев',
			года_лет',

		y === 'm') {
			thoutSuffix ? 'минута' : 'минуту';
		 {
			mber + ' ' + plural(format[key], +number);


	 monthsParse = [
		i,
		i,
		i,
		i,
		я]/i,
		i,
		i,
		i,
		i,
		i,
		i,
		i,


	http://new.gramota.ru/spravka/rules/139-prop : § 103
	Сокращения месяцев: http://new.gramota.ru/spravka/buro/search-answer?s=242637
	CLDR data:		 		www.unicode.org/cldr/charts/28/summary/ru.html#1753
	 ru = moment.defineLocale('ru', {
		: {
			января_февраля_марта_апреля_мая_июня_июля_августа_сентября_октября_ноября_декабря'.split(


			e: 'январь_февраль_март_апрель_май_июнь_июль_август_сентябрь_октябрь_ноябрь_декабрь'.split(



		Short: {
			R именно "июл." и "июн.", но какой смысл менять букву на точку?
			янв._февр._мар._апр._мая_июня_июля_авг._сент._окт._нояб._дек.'.split(


			e: 'янв._февр._март_апр._май_июнь_июль_авг._сент._окт._нояб._дек.'.split(



		ys: {
			e: 'воскресенье_понедельник_вторник_среда_четверг_пятница_суббота'.split(


			воскресенье_понедельник_вторник_среду_четверг_пятницу_субботу'.split(


			 /\[ ?[Вв] ?(?:прошлую|следующую|эту)? ?] ?dddd/,

		ysShort: 'вс_пн_вт_ср_чт_пт_сб'.split('_'),
		ysMin: 'вс_пн_вт_ср_чт_пт_сб'.split('_'),
		Parse: monthsParse,
		nthsParse: monthsParse,
		onthsParse: monthsParse,

		ные названия с падежами, по три буквы, для некоторых, по 4 буквы, сокращения с точкой и без точки
		Regex: /^(январ[ья]|янв\.?|феврал[ья]|февр?\.?|марта?|мар\.?|апрел[ья]|апр\.?|ма[йя]|июн[ья]|июн\.?|июл[ья]|июл\.?|августа?|авг\.?|сентябр[ья]|сент?\.?|октябр[ья]|окт\.?|ноябр[ья]|нояб?\.?|декабр[ья]|дек\.?)/i,

		ия предыдущего
		ShortRegex: /^(январ[ья]|янв\.?|феврал[ья]|февр?\.?|марта?|мар\.?|апрел[ья]|апр\.?|ма[йя]|июн[ья]|июн\.?|июл[ья]|июл\.?|августа?|авг\.?|сентябр[ья]|сент?\.?|октябр[ья]|окт\.?|ноябр[ья]|нояб?\.?|декабр[ья]|дек\.?)/i,

		ные названия с падежами
		StrictRegex: /^(январ[яь]|феврал[яь]|марта?|апрел[яь]|ма[яй]|июн[яь]|июл[яь]|августа?|сентябр[яь]|октябр[яь]|ноябр[яь]|декабр[яь])/i,

		ажение, которое соответствует только сокращённым формам
		ShortStrictRegex: /^(янв\.|февр?\.|мар[т.]|апр\.|ма[яй]|июн[ья.]|июл[ья.]|авг\.|сент?\.|окт\.|нояб?\.|дек\.)/i,
		teFormat: {
			',
			m:ss',
			.YYYY',
			MM YYYY г.',
			MMM YYYY г., H:mm',
			dd, D MMMM YYYY г., H:mm',

		ar: {
			'[Сегодня, в] LT',
			'[Завтра, в] LT',
			'[Вчера, в] LT',
			 function (now) {
				() !== this.week()) {
					y()) {

							 dddd, [в] LT';



							 dddd, [в] LT';



							 dddd, [в] LT';


					=== 2) {
						 [в] LT';

						[в] LT';



			 function (now) {
				() !== this.week()) {
					y()) {

							ddd, [в] LT';



							ddd, [в] LT';



							ddd, [в] LT';


					=== 2) {
						 [в] LT';

						[в] LT';



			 'L',

		veTime: {
			через %s',
			 назад',
			лько секунд',
			iveTimeWithPlural,
			veTimeWithPlural,
			iveTimeWithPlural,

			iveTimeWithPlural,
			,
			iveTimeWithPlural,
			я',
			iveTimeWithPlural,
			',
			iveTimeWithPlural,

			iveTimeWithPlural,

		emParse: /ночи|утра|дня|вечера/i,
		function (input) {
			(дня|вечера)$/.test(input);

		em: function (hour, minute, isLower) {
			< 4) {
				';
			 (hour < 12) {
				';
			 (hour < 17) {
				;

				ра';


		onthOrdinalParse: /\d{1,2}-(й|го|я)/,
		l: function (number, period) {
			eriod) {



					 '-й';

					 '-го';


					 '-я';




		{
			/ Monday is the first day of the week.
			/ The week that contains Jan 4th is the first week of the year.



	urn ru;

})));

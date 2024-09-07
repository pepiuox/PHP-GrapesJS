//! moment.js locale configuration
//! locale : Macedonian [mk]
//! author : Borislav Mickov : https://github.com/B0k0
//! author : Sashko Todorov : https://github.com/bkyceh

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	&& typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	 moment.js locale configuration

	 mk = moment.defineLocale('mk', {
		: 'јануари_февруари_март_април_мај_јуни_јули_август_септември_октомври_ноември_декември'.split(


		Short: 'јан_фев_мар_апр_мај_јун_јул_авг_сеп_окт_ное_дек'.split('_'),
		ys: 'недела_понеделник_вторник_среда_четврток_петок_сабота'.split(


		ysShort: 'нед_пон_вто_сре_чет_пет_саб'.split('_'),
		ysMin: 'нe_пo_вт_ср_че_пе_сa'.split('_'),
		teFormat: {
			',
			m:ss',
			YYYY',
			MM YYYY',
			MMM YYYY H:mm',
			dd, D MMMM YYYY H:mm',

		ar: {
			'[Денес во] LT',
			'[Утре во] LT',
			 '[Во] dddd [во] LT',
			'[Вчера во] LT',
			 function () {
				.day()) {



						а] dddd [во] LT';




						т] dddd [во] LT';


			 'L',

		veTime: {
			за %s',
			ед %s',
			ку секунди',
			екунди',
			минута',
			инути',
			час',
			аса',
			ден',
			ена',
			месец',
			есеци',
			година',
			одини',

		onthOrdinalParse: /\d{1,2}-(ев|ен|ти|ви|ри|ми)/,
		l: function (number) {
			igit = number % 10,
				= number % 100;
			r === 0) {
				r + '-ев';
			 (last2Digits === 0) {
				r + '-ен';
			 (last2Digits > 10 && last2Digits < 20) {
				r + '-ти';
			 (lastDigit === 1) {
				r + '-ви';
			 (lastDigit === 2) {
				r + '-ри';
			 (lastDigit === 7 || lastDigit === 8) {
				r + '-ми';

				r + '-ти';


		{
			/ Monday is the first day of the week.
			/ The week that contains Jan 7th is the first week of the year.



	urn mk;

})));

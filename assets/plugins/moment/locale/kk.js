//! moment.js locale configuration
//! locale : Kazakh [kk]
//! authors : Nurlan Rakhimzhanov : https://github.com/nurlan

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	&& typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	 moment.js locale configuration

	 suffixes = {
		і',
		і',
		і',
		і',
		і',
		і',
		ы',
		і',
		і',
		ы',
		шы',
		шы',
		шы',
		шы',
		ші',
		шы',
		ші',
		ші',
		шы',
		-ші',


	 kk = moment.defineLocale('kk', {
		: 'қаңтар_ақпан_наурыз_сәуір_мамыр_маусым_шілде_тамыз_қыркүйек_қазан_қараша_желтоқсан'.split(


		Short: 'қаң_ақп_нау_сәу_мам_мау_шіл_там_қыр_қаз_қар_жел'.split('_'),
		ys: 'жексенбі_дүйсенбі_сейсенбі_сәрсенбі_бейсенбі_жұма_сенбі'.split(


		ysShort: 'жек_дүй_сей_сәр_бей_жұм_сен'.split('_'),
		ysMin: 'жк_дй_сй_ср_бй_жм_сн'.split('_'),
		teFormat: {
			m',
			mm:ss',
			.YYYY',
			MM YYYY',
			MMM YYYY HH:mm',
			dd, D MMMM YYYY HH:mm',

		ar: {
			'[Бүгін сағат] LT',
			'[Ертең сағат] LT',
			 'dddd [сағат] LT',
			'[Кеше сағат] LT',
			 '[Өткен аптаның] dddd [сағат] LT',
			 'L',

		veTime: {
			%s ішінде',
			 бұрын',
			ше секунд',
			екунд',
			инут',
			инут',
			ағат',
			ағат',
			үн',
			үн',
			й',
			й',
			ыл',
			ыл',

		onthOrdinalParse: /\d{1,2}-(ші|шы)/,
		l: function (number) {
			umber % 10,
				= 100 ? 100 : null;
			mber + (suffixes[number] || suffixes[a] || suffixes[b]);

		{
			/ Monday is the first day of the week.
			/ The week that contains Jan 7th is the first week of the year.



	urn kk;

})));

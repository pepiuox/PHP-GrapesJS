//! moment.js locale configuration
//! locale : Luxembourgish [lb]
//! author : mweimerskirch : https://github.com/mweimerskirch
//! author : David Raison : https://github.com/kwisatz

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	quire === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	ocale configuration

	sRelativeTime(number, withoutSuffix, key, isFuture) {







		key][0] : format[key][1];

	sFutureTime(string) {
		string.indexOf(' '));
		r(number)) {




	sPastTime(string) {
		string.indexOf(' '));
		r(number)) {





	 if the word before the given number loses the '-n' ending.
	Deeg' but 'a 5 Deeg'

	r {integer}
	olean}

	rRegelAppliesToNumber(number) {




















			;

			digit





			 check first n-3 digits





	.defineLocale('lb', {
		Abrëll_Mee_Juni_Juli_August_September_Oktober_November_Dezember'.split(


		Abr._Mee_Jun._Jul._Aug._Sept._Okt._Nov._Dez.'.split(



		schdeg_Mëttwoch_Donneschdeg_Freideg_Samschdeg'.split(


		._Do._Fr._Sa.'.split('_'),
		r_Sa'.split('_'),
















				eschdeg' (Thursday) due to phonological rule

























		2}\./,


			k.
			the first week of the year.





})));

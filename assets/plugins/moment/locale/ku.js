//! moment.js locale configuration
//! locale : Kurdish [ku]
//! author : Shahram Mebashar : https://github.com/ShahramMebashar

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	ment.js locale configuration

	mbolMap = {











		{


























	 = moment.defineLocale('ku', {
		hs,
		 months,
		ه‌كشه‌ممه‌_دووشه‌ممه‌_سێشه‌ممه‌_چوارشه‌ممه‌_پێنجشه‌ممه‌_هه‌ینی_شه‌ممه‌'.split(


		t: 'یه‌كشه‌م_دووشه‌م_سێشه‌م_چوارشه‌م_پێنجشه‌م_هه‌ینی_شه‌ممه‌'.split(


		 'ی_د_س_چ_پ_ه_ش'.split('_'),
		eExact: true,
		at: {




			HH:mm',
			M YYYY HH:mm',

		e: /ئێواره‌|به‌یانی/,
		on (input) {
			est(input);

		nction (hour, minute, isLower) {







			كاتژمێر] LT',
			 كاتژمێر] LT',
			اتژمێر] LT',
			اتژمێر] LT',
			اتژمێر] LT',


		: {


			ك',












		nction (string) {

				, function (match) {




		function (string) {

				 (match) {





			y is the first day of the week.
			ek that contains Jan 12th is the first week of the year.



	 ku;

})));

//! moment.js locale configuration
//! locale : Romanian [ro]
//! author : Vlad Gurdiga : https://github.com/gurdiga
//! author : Valentin Agachi : https://github.com/avaly
//! author : Emanuel Cepoi : https://github.com/cepem

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	ment.js locale configuration

	on relativeTimeWithPlural(number, withoutSuffix, key) {
		 {









		 100 >= 20 || (number >= 100 && number % 100 === 0)) {
			;

		r + separator + format[key];


	 = moment.defineLocale('ro', {
		uarie_februarie_martie_aprilie_mai_iunie_iulie_august_septembrie_octombrie_noiembrie_decembrie'.split(


		 'ian._feb._mart._apr._mai_iun._iul._aug._sept._oct._nov._dec.'.split(


		xact: true,
		uminică_luni_marți_miercuri_joi_vineri_sâmbătă'.split('_'),
		t: 'Dum_Lun_Mar_Mie_Joi_Vin_Sâm'.split('_'),
		 'Du_Lu_Ma_Mi_Jo_Vi_Sâ'.split('_'),
		at: {




			H:mm',
			M YYYY H:mm',


			 LT',
			a] LT',
			a] LT',
			] LT',
			 dddd [la] LT',


		: {
			,
			,
			',
			thPlural,

			thPlural,

			thPlural,

			thPlural,

			thPlural,

			thPlural,

			thPlural,


			is the first day of the week.
			k that contains Jan 7th is the first week of the year.



	 ro;

})));

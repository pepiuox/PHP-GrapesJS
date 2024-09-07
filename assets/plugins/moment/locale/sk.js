//! moment.js locale configuration
//! locale : Slovak [sk]
//! author : Martin Minka : https://github.com/k2s
//! based on work of petrbela : https://github.com/petrbela

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	&& typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	 moment.js locale configuration

	 months = 'január_február_marec_apríl_máj_jún_júl_august_september_október_november_december'.split(


		Short = 'jan_feb_mar_apr_máj_jún_júl_aug_sep_okt_nov_dec'.split('_');
	ction plural(n) {
		 n > 1 && n < 5;

	ction translate(number, withoutSuffix, key, isFuture) {
		sult = number + ' ';
		 (key) {
			 // a few seconds / in a few seconds / a few seconds ago
				utSuffix || isFuture ? 'pár sekúnd' : 'pár sekundami';
			: // 9 seconds / in 9 seconds / 9 seconds ago
				uffix || isFuture) {
					 (plural(number) ? 'sekundy' : 'sekúnd');

					 'sekundami';

			 // a minute / in a minute / a minute ago
				utSuffix ? 'minúta' : isFuture ? 'minútu' : 'minútou';
			: // 9 minutes / in 9 minutes / 9 minutes ago
				uffix || isFuture) {
					 (plural(number) ? 'minúty' : 'minút');

					 'minútami';

			 // an hour / in an hour / an hour ago
				utSuffix ? 'hodina' : isFuture ? 'hodinu' : 'hodinou';
			: // 9 hours / in 9 hours / 9 hours ago
				uffix || isFuture) {
					 (plural(number) ? 'hodiny' : 'hodín');

					 'hodinami';

			 // a day / in a day / a day ago
				utSuffix || isFuture ? 'deň' : 'dňom';
			: // 9 days / in 9 days / 9 days ago
				uffix || isFuture) {
					 (plural(number) ? 'dni' : 'dní');

					 'dňami';

			 // a month / in a month / a month ago
				utSuffix || isFuture ? 'mesiac' : 'mesiacom';
			: // 9 months / in 9 months / 9 months ago
				uffix || isFuture) {
					 (plural(number) ? 'mesiace' : 'mesiacov');

					 'mesiacmi';

			 // a year / in a year / a year ago
				utSuffix || isFuture ? 'rok' : 'rokom';
			: // 9 years / in 9 years / 9 years ago
				uffix || isFuture) {
					 (plural(number) ? 'roky' : 'rokov');

					 'rokmi';




	 sk = moment.defineLocale('sk', {
		: months,
		Short: monthsShort,
		ys: 'nedeľa_pondelok_utorok_streda_štvrtok_piatok_sobota'.split('_'),
		ysShort: 'ne_po_ut_st_št_pi_so'.split('_'),
		ysMin: 'ne_po_ut_st_št_pi_so'.split('_'),
		teFormat: {
			',
			m:ss',
			.YYYY',
			MMM YYYY',
			MMMM YYYY H:mm',
			dd D. MMMM YYYY H:mm',

		ar: {
			'[dnes o] LT',
			'[zajtra o] LT',
			 function () {
				.day()) {

						o] LT';


						o] LT';

						o] LT';

						k o] LT';

						o] LT';

						o] LT';


			'[včera o] LT',
			 function () {
				.day()) {

						deľu o] LT';


						ddd [o] LT';

						redu o] LT';


						ddd [o] LT';

						botu o] LT';


			 'L',

		veTime: {
			za %s',
			ed %s',
			ate,
			late,
			ate,
			late,
			ate,
			late,
			ate,
			late,
			ate,
			late,
			ate,
			late,

		onthOrdinalParse: /\d{1,2}\./,
		l: '%d.',
		{
			/ Monday is the first day of the week.
			/ The week that contains Jan 4th is the first week of the year.



	urn sk;

})));

//! moment.js locale configuration
//! locale : Montenegrin [me]
//! author : Miodrag Nikač <miodrag@restartit.me> : https://github.com/miodragnikac

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	&& typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	 moment.js locale configuration

	 translator = {
		 {
			nt grammatical cases
			und', 'sekunda', 'sekundi'],
			n minut', 'jednog minuta'],
			ut', 'minuta', 'minuta'],
			n sat', 'jednog sata'],
			', 'sata', 'sati'],
			', 'dana', 'dana'],
			sec', 'mjeseca', 'mjeseci'],
			ina', 'godine', 'godina'],

		tGrammaticalCase: function (number, wordKey) {
			mber === 1

				2 && number <= 4

				;

		ate: function (number, withoutSuffix, key) {
			ey = translator.words[key];
			ength === 1) {
				utSuffix ? wordKey[0] : wordKey[1];




					ectGrammaticalCase(number, wordKey)





	 me = moment.defineLocale('me', {
		: 'januar_februar_mart_april_maj_jun_jul_avgust_septembar_oktobar_novembar_decembar'.split(


		Short: 'jan._feb._mar._apr._maj_jun_jul_avg._sep._okt._nov._dec.'.split(


		ParseExact: true,
		ys: 'nedjelja_ponedjeljak_utorak_srijeda_četvrtak_petak_subota'.split(


		ysShort: 'ned._pon._uto._sri._čet._pet._sub.'.split('_'),
		ysMin: 'ne_po_ut_sr_če_pe_su'.split('_'),
		ysParseExact: true,
		teFormat: {
			',
			m:ss',
			.YYYY',
			MMM YYYY',
			MMMM YYYY H:mm',
			dd, D. MMMM YYYY H:mm',

		ar: {
			'[danas u] LT',
			'[sjutra u] LT',

			 function () {
				.day()) {

						lju] [u] LT';

						du] [u] LT';

						u] [u] LT';




						u] LT';


			'[juče u] LT',
			 function () {
				Days = [
					elje] [u] LT',
					edjeljka] [u] LT',
					rka] [u] LT',
					ede] [u] LT',
					vrtka] [u] LT',
					ka] [u] LT',
					te] [u] LT',

				eekDays[this.day()];

			 'L',

		veTime: {
			za %s',
			ije %s',
			iko sekundi',
			lator.translate,
			ator.translate,
			lator.translate,
			ator.translate,
			lator.translate,

			lator.translate,
			c',
			lator.translate,
			u',
			lator.translate,

		onthOrdinalParse: /\d{1,2}\./,
		l: '%d.',
		{
			/ Monday is the first day of the week.
			/ The week that contains Jan 7th is the first week of the year.



	urn me;

})));

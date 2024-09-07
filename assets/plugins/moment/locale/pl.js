//! moment.js locale configuration
//! locale : Polish [pl]
//! author : Rafal Hirsz : https://github.com/evoL

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	&& typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	 moment.js locale configuration

	 monthsNominative = 'styczeń_luty_marzec_kwiecień_maj_czerwiec_lipiec_sierpień_wrzesień_październik_listopad_grudzień'.split(


		Subjective = 'stycznia_lutego_marca_kwietnia_maja_czerwca_lipca_sierpnia_września_października_listopada_grudnia'.split(


		Parse = [













	ction plural(n) {
		 n % 10 < 5 && n % 10 > 1 && ~~(n / 10) % 10 !== 1;

	ction translate(number, withoutSuffix, key) {
		sult = number + ' ';
		 (key) {
			:
				t + (plural(number) ? 'sekundy' : 'sekund');

				utSuffix ? 'minuta' : 'minutę';
			:
				t + (plural(number) ? 'minuty' : 'minut');

				utSuffix ? 'godzina' : 'godzinę';
			:
				t + (plural(number) ? 'godziny' : 'godzin');
			:
				t + (plural(number) ? 'tygodnie' : 'tygodni');
			:
				t + (plural(number) ? 'miesiące' : 'miesięcy');
			:
				t + (plural(number) ? 'lata' : 'lat');



	 pl = moment.defineLocale('pl', {
		: function (momentToFormat, format) {
			ntToFormat) {
				sNominative;
			 (/D MMMM/.test(format)) {
				sSubjective[momentToFormat.month()];

				sNominative[momentToFormat.month()];


		Short: 'sty_lut_mar_kwi_maj_cze_lip_sie_wrz_paź_lis_gru'.split('_'),
		Parse: monthsParse,
		nthsParse: monthsParse,
		onthsParse: monthsParse,
		ys: 'niedziela_poniedziałek_wtorek_środa_czwartek_piątek_sobota'.split(


		ysShort: 'ndz_pon_wt_śr_czw_pt_sob'.split('_'),
		ysMin: 'Nd_Pn_Wt_Śr_Cz_Pt_So'.split('_'),
		teFormat: {
			m',
			mm:ss',
			.YYYY',
			MM YYYY',
			MMM YYYY HH:mm',
			dd, D MMMM YYYY HH:mm',

		ar: {
			'[Dziś o] LT',
			'[Jutro o] LT',
			 function () {
				.day()) {

						lę o] LT';


						 o] LT';


						] LT';


						o] LT';


						o] LT';


			'[Wczoraj o] LT',
			 function () {
				.day()) {

						niedzielę o] LT';

						środę o] LT';

						sobotę o] LT';

						 dddd [o] LT';


			 'L',

		veTime: {
			za %s',
			 temu',
			 sekund',
			late,
			ate,
			late,
			ate,
			late,
			eń',
			ni',
			eń',
			late,
			ąc',
			late,

			late,

		onthOrdinalParse: /\d{1,2}\./,
		l: '%d.',
		{
			/ Monday is the first day of the week.
			/ The week that contains Jan 4th is the first week of the year.



	urn pl;

})));

//! moment.js locale configuration
//! locale : Dutch [nl]
//! author : Joris Röling : https://github.com/jorisroling
//! author : Jacob Middag : https://github.com/middagj

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	&& typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	 moment.js locale configuration

	 monthsShortWithDots = 'jan._feb._mrt._apr._mei_jun._jul._aug._sep._okt._nov._dec.'.split(


		ShortWithoutDots = 'jan_feb_mrt_apr_mei_jun_jul_aug_sep_okt_nov_dec'.split(


		Parse = [


			rt.?$/i,


			?$/i,
			?$/i,






		Regex = /^(januari|februari|maart|april|mei|ju[nl]i|augustus|september|oktober|november|december|jan\.?|feb\.?|mrt\.?|apr\.?|ju[nl]\.?|aug\.?|sep\.?|okt\.?|nov\.?|dec\.?)/i;

	 nl = moment.defineLocale('nl', {
		: 'januari_februari_maart_april_mei_juni_juli_augustus_september_oktober_november_december'.split(


		Short: function (m, format) {

				sShortWithDots;
			 (/-MMM-/.test(format)) {
				sShortWithoutDots[m.month()];

				sShortWithDots[m.month()];



		Regex: monthsRegex,
		ShortRegex: monthsRegex,
		StrictRegex: /^(januari|februari|maart|april|mei|ju[nl]i|augustus|september|oktober|november|december)/i,
		ShortStrictRegex: /^(jan\.?|feb\.?|mrt\.?|apr\.?|mei|ju[nl]\.?|aug\.?|sep\.?|okt\.?|nov\.?|dec\.?)/i,

		Parse: monthsParse,
		nthsParse: monthsParse,
		onthsParse: monthsParse,

		ys: 'zondag_maandag_dinsdag_woensdag_donderdag_vrijdag_zaterdag'.split(


		ysShort: 'zo._ma._di._wo._do._vr._za.'.split('_'),
		ysMin: 'zo_ma_di_wo_do_vr_za'.split('_'),
		ysParseExact: true,
		teFormat: {
			m',
			mm:ss',
			-YYYY',
			MM YYYY',
			MMM YYYY HH:mm',
			dd D MMMM YYYY HH:mm',

		ar: {
			'[vandaag om] LT',
			'[morgen om] LT',
			 'dddd [om] LT',
			'[gisteren om] LT',
			 '[afgelopen] dddd [om] LT',
			 'L',

		veTime: {
			over %s',
			 geleden',
			aar seconden',
			econden',
			inuut',
			inuten',
			ur',
			ur',
			ag',
			agen',
			eek',
			eken',
			aand',
			aanden',
			aar',
			aar',

		onthOrdinalParse: /\d{1,2}(ste|de)/,
		l: function (number) {


				1 || number === 8 || number >= 20 ? 'ste' : 'de')


		{
			/ Monday is the first day of the week.
			/ The week that contains Jan 4th is the first week of the year.



	urn nl;

})));

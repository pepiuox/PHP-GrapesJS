//! moment.js locale configuration
//! locale : Occitan, lengadocian dialecte [oc-lnc]
//! author : Quentin PAGÈS : https://github.com/Quenty31

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	&& typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	 moment.js locale configuration

	 ocLnc = moment.defineLocale('oc-lnc', {
		: {
			e: 'genièr_febrièr_març_abril_mai_junh_julhet_agost_setembre_octòbre_novembre_decembre'.split(


			de genièr_de febrièr_de març_d'abril_de mai_de junh_de julhet_d'agost_de setembre_d'octòbre_de novembre_de decembre".split(


			 /D[oD]?(\s)+MMMM/,

		Short: 'gen._febr._març_abr._mai_junh_julh._ago._set._oct._nov._dec.'.split(


		ParseExact: true,
		ys: 'dimenge_diluns_dimars_dimècres_dijòus_divendres_dissabte'.split(


		ysShort: 'dg._dl._dm._dc._dj._dv._ds.'.split('_'),
		ysMin: 'dg_dl_dm_dc_dj_dv_ds'.split('_'),
		ysParseExact: true,
		teFormat: {
			',
			m:ss',
			/YYYY',
			MM [de] YYYY',
			M YYYY',
			MMM [de] YYYY [a] H:mm',
			MM YYYY, H:mm',
			dd D MMMM [de] YYYY [a] H:mm',
			d D MMM YYYY, H:mm',

		ar: {
			'[uèi a] LT',
			'[deman a] LT',
			 'dddd [a] LT',
			'[ièr a] LT',
			 'dddd [passat a] LT',
			 'L',

		veTime: {
			d'aquí %s",
			 %s',
			segondas',
			egondas',
			inuta',
			inutas',
			ra',
			ras',
			rn',
			orns',
			s',
			eses',
			',
			ns',

		onthOrdinalParse: /\d{1,2}(r|n|t|è|a)/,
		l: function (number, period) {
			t =









			d === 'w' || period === 'W') {
				;

			mber + output;

		{
			/ Monday is the first day of the week.




	urn ocLnc;

})));

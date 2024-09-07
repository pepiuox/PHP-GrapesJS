//! moment.js locale configuration
//! locale : Italian [it]
//! author : Lorenzo : https://github.com/aliem
//! author: Mattia Larentis: https://github.com/nostalgiaz
//! author: Marco : https://github.com/Manfre98

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	&& typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	 moment.js locale configuration

	 it = moment.defineLocale('it', {
		: 'gennaio_febbraio_marzo_aprile_maggio_giugno_luglio_agosto_settembre_ottobre_novembre_dicembre'.split(


		Short: 'gen_feb_mar_apr_mag_giu_lug_ago_set_ott_nov_dic'.split('_'),
		ys: 'domenica_lunedì_martedì_mercoledì_giovedì_venerdì_sabato'.split(


		ysShort: 'dom_lun_mar_mer_gio_ven_sab'.split('_'),
		ysMin: 'do_lu_ma_me_gi_ve_sa'.split('_'),
		teFormat: {
			m',
			mm:ss',
			/YYYY',
			MM YYYY',
			MMM YYYY HH:mm',
			dd D MMMM YYYY HH:mm',

		ar: {
			function () {


					 1 ? 'lle ' : this.hours() === 0 ? ' ' : "ll'") +



			function () {


					 1 ? 'lle ' : this.hours() === 0 ? ' ' : "ll'") +



			 function () {


					 1 ? 'lle ' : this.hours() === 0 ? ' ' : "ll'") +



			function () {


					 1 ? 'lle ' : this.hours() === 0 ? ' ' : "ll'") +



			 function () {
				.day()) {


							 +









							 +









			 'L',

		veTime: {
			tra %s',
			 fa',
			i secondi',
			econdi',
			nuto',
			inuti',
			a",
			re',
			orno',
			iorni',
			ettimana',
			ettimane',
			se',
			esi',
			no',
			nni',

		onthOrdinalParse: /\d{1,2}º/,
		l: '%dº',
		{
			/ Monday is the first day of the week.
			/ The week that contains Jan 4th is the first week of the year.



	urn it;

})));

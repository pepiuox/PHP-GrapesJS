//! moment.js locale configuration
//! locale : Cambodian [km]
//! author : Kruy Vanna : https://github.com/kruyvanna

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	&& typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	 moment.js locale configuration

	 symbolMap = {











		Map = {












	 km = moment.defineLocale('km', {
		: 'មករា_កុម្ភៈ_មីនា_មេសា_ឧសភា_មិថុនា_កក្កដា_សីហា_កញ្ញា_តុលា_វិច្ឆិកា_ធ្នូ'.split(


		Short: 'មករា_កុម្ភៈ_មីនា_មេសា_ឧសភា_មិថុនា_កក្កដា_សីហា_កញ្ញា_តុលា_វិច្ឆិកា_ធ្នូ'.split(


		ys: 'អាទិត្យ_ច័ន្ទ_អង្គារ_ពុធ_ព្រហស្បតិ៍_សុក្រ_សៅរ៍'.split('_'),
		ysShort: 'អា_ច_អ_ព_ព្រ_សុ_ស'.split('_'),
		ysMin: 'អា_ច_អ_ព_ព្រ_សុ_ស'.split('_'),
		ysParseExact: true,
		teFormat: {
			m',
			mm:ss',
			/YYYY',
			MM YYYY',
			MMM YYYY HH:mm',
			dd, D MMMM YYYY HH:mm',

		emParse: /ព្រឹក|ល្ងាច/,
		function (input) {
			put === 'ល្ងាច';

		em: function (hour, minute, isLower) {
			< 12) {
				ក';

				ច';


		ar: {
			'[ថ្ងៃនេះ ម៉ោង] LT',
			'[ស្អែក ម៉ោង] LT',
			 'dddd [ម៉ោង] LT',
			'[ម្សិលមិញ ម៉ោង] LT',
			 'dddd [សប្តាហ៍មុន] [ម៉ោង] LT',
			 'L',

		veTime: {
			%sទៀត',
			មុន',
			មានវិនាទី',
			ិនាទី',
			ទី',
			ាទី',
			ោង',
			៉ោង',
			ងៃ',
			្ងៃ',
			',
			ែ',
			នាំ',
			្នាំ',

		onthOrdinalParse: /ទី\d{1,2}/,
		l: 'ទី%d',
		se: function (string) {
			ring.replace(/[១២៣៤៥៦៧៨៩០]/g, function (match) {
				rMap[match];


		rmat: function (string) {
			ring.replace(/\d/g, function (match) {
				lMap[match];


		{
			/ Monday is the first day of the week.
			/ The week that contains Jan 4th is the first week of the year.



	urn km;

})));

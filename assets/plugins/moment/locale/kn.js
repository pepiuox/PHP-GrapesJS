//! moment.js locale configuration
//! locale : Kannada [kn]
//! author : Rajeev Naik : https://github.com/rajeevnaikte

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	&& typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	 moment.js locale configuration

	 symbolMap = {











		Map = {












	 kn = moment.defineLocale('kn', {
		: 'ಜನವರಿ_ಫೆಬ್ರವರಿ_ಮಾರ್ಚ್_ಏಪ್ರಿಲ್_ಮೇ_ಜೂನ್_ಜುಲೈ_ಆಗಸ್ಟ್_ಸೆಪ್ಟೆಂಬರ್_ಅಕ್ಟೋಬರ್_ನವೆಂಬರ್_ಡಿಸೆಂಬರ್'.split(


		Short: 'ಜನ_ಫೆಬ್ರ_ಮಾರ್ಚ್_ಏಪ್ರಿಲ್_ಮೇ_ಜೂನ್_ಜುಲೈ_ಆಗಸ್ಟ್_ಸೆಪ್ಟೆಂ_ಅಕ್ಟೋ_ನವೆಂ_ಡಿಸೆಂ'.split(


		ParseExact: true,
		ys: 'ಭಾನುವಾರ_ಸೋಮವಾರ_ಮಂಗಳವಾರ_ಬುಧವಾರ_ಗುರುವಾರ_ಶುಕ್ರವಾರ_ಶನಿವಾರ'.split(


		ysShort: 'ಭಾನು_ಸೋಮ_ಮಂಗಳ_ಬುಧ_ಗುರು_ಶುಕ್ರ_ಶನಿ'.split('_'),
		ysMin: 'ಭಾ_ಸೋ_ಮಂ_ಬು_ಗು_ಶು_ಶ'.split('_'),
		teFormat: {
			mm',
			:mm:ss',
			/YYYY',
			MM YYYY',
			MMM YYYY, A h:mm',
			dd, D MMMM YYYY, A h:mm',

		ar: {
			'[ಇಂದು] LT',
			'[ನಾಳೆ] LT',
			 'dddd, LT',
			'[ನಿನ್ನೆ] LT',
			 '[ಕೊನೆಯ] dddd, LT',
			 'L',

		veTime: {
			%s ನಂತರ',
			 ಹಿಂದೆ',
			 ಕ್ಷಣಗಳು',
			ೆಕೆಂಡುಗಳು',
			ನಿಮಿಷ',
			ಿಮಿಷ',
			ಗಂಟೆ',
			ಂಟೆ',
			ದಿನ',
			ಿನ',
			ತಿಂಗಳು',
			ಿಂಗಳು',
			ವರ್ಷ',
			ರ್ಷ',

		se: function (string) {
			ring.replace(/[೧೨೩೪೫೬೭೮೯೦]/g, function (match) {
				rMap[match];


		rmat: function (string) {
			ring.replace(/\d/g, function (match) {
				lMap[match];


		emParse: /ರಾತ್ರಿ|ಬೆಳಿಗ್ಗೆ|ಮಧ್ಯಾಹ್ನ|ಸಂಜೆ/,
		emHour: function (hour, meridiem) {
			=== 12) {


			iem === 'ರಾತ್ರಿ') {
				< 4 ? hour : hour + 12;
			 (meridiem === 'ಬೆಳಿಗ್ಗೆ') {

			 (meridiem === 'ಮಧ್ಯಾಹ್ನ') {
				>= 10 ? hour : hour + 12;
			 (meridiem === 'ಸಂಜೆ') {
				+ 12;


		em: function (hour, minute, isLower) {
			< 4) {
				ರಿ';
			 (hour < 10) {
				ಗ್ಗೆ';
			 (hour < 17) {
				ಾಹ್ನ';
			 (hour < 20) {
				';

				ರಿ';


		onthOrdinalParse: /\d{1,2}(ನೇ)/,
		l: function (number) {
			mber + 'ನೇ';

		{
			/ Sunday is the first day of the week.
			/ The week that contains Jan 6th is the first week of the year.



	urn kn;

})));

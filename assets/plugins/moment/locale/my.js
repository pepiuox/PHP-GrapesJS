//! moment.js locale configuration
//! locale : Burmese [my]
//! author : Squar team, mysquar.com
//! author : David Rossellat : https://github.com/gholadr
//! author : Tin Aung Lin : https://github.com/thanyawzinmin

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	&& typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	 moment.js locale configuration

	 symbolMap = {











		Map = {












	 my = moment.defineLocale('my', {
		: 'ဇန်နဝါရီ_ဖေဖော်ဝါရီ_မတ်_ဧပြီ_မေ_ဇွန်_ဇူလိုင်_သြဂုတ်_စက်တင်ဘာ_အောက်တိုဘာ_နိုဝင်ဘာ_ဒီဇင်ဘာ'.split(


		Short: 'ဇန်_ဖေ_မတ်_ပြီ_မေ_ဇွန်_လိုင်_သြ_စက်_အောက်_နို_ဒီ'.split('_'),
		ys: 'တနင်္ဂနွေ_တနင်္လာ_အင်္ဂါ_ဗုဒ္ဓဟူး_ကြာသပတေး_သောကြာ_စနေ'.split(


		ysShort: 'နွေ_လာ_ဂါ_ဟူး_ကြာ_သော_နေ'.split('_'),
		ysMin: 'နွေ_လာ_ဂါ_ဟူး_ကြာ_သော_နေ'.split('_'),

		teFormat: {
			m',
			mm:ss',
			/YYYY',
			MM YYYY',
			MMM YYYY HH:mm',
			dd D MMMM YYYY HH:mm',

		ar: {
			'[ယနေ.] LT [မှာ]',
			'[မနက်ဖြန်] LT [မှာ]',
			 'dddd LT [မှာ]',
			'[မနေ.က] LT [မှာ]',
			 '[ပြီးခဲ့သော] dddd LT [မှာ]',
			 'L',

		veTime: {
			လာမည့် %s မှာ',
			န်ခဲ့သော %s က',
			်.အနည်းငယ်',
			က္ကန့်',
			နစ်',
			ိနစ်',
			ရီ',
			ာရီ',
			်',
			က်',
			,
			',
			စ်',
			ှစ်',

		se: function (string) {
			ring.replace(/[၁၂၃၄၅၆၇၈၉၀]/g, function (match) {
				rMap[match];


		rmat: function (string) {
			ring.replace(/\d/g, function (match) {
				lMap[match];


		{
			/ Monday is the first day of the week.
			/ The week that contains Jan 4th is the first week of the year.



	urn my;

})));

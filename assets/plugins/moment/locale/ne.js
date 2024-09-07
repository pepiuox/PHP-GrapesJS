//! moment.js locale configuration
//! locale : Nepalese [ne]
//! author : suvash : https://github.com/suvash

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	 require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	s locale configuration

	p = {
























	ent.defineLocale('ne', {
		_मार्च_अप्रिल_मई_जुन_जुलाई_अगष्ट_सेप्टेम्बर_अक्टोबर_नोभेम्बर_डिसेम्बर'.split(


		._मार्च_अप्रि._मई_जुन_जुलाई._अग._सेप्ट._अक्टो._नोभे._डिसे.'.split(



		_मङ्गलबार_बुधबार_बिहिबार_शुक्रबार_शनिबार'.split(


		._मङ्गल._बुध._बिहि._शुक्र._शनि.'.split('_'),
		_बु._बि._शु._श.'.split('_'),
		,






			जे',

		ng) {
			g, function (match) {



		ring) {
			n (match) {



		ान|दिउँसो|साँझ/,
		hour, meridiem) {













		, minute, isLower) {





































			f the week.
			n 6th is the first week of the year.





})));

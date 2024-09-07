//! moment.js locale configuration
//! locale : Georgian [ka]
//! author : Irakli Janiashvili : https://github.com/IrakliJani

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
	 require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';

	s locale configuration

	ent.defineLocale('ka', {
		ლი_მარტი_აპრილი_მაისი_ივნისი_ივლისი_აგვისტო_სექტემბერი_ოქტომბერი_ნოემბერი_დეკემბერი'.split(


		რ_აპრ_მაი_ივნ_ივლ_აგვ_სექ_ოქტ_ნოე_დეკ'.split('_'),

			ი_ოთხშაბათი_ხუთშაბათი_პარასკევი_შაბათი'.split(


			თხშაბათს_ხუთშაბათს_პარასკევს_შაბათს'.split(




		სამ_ოთხ_ხუთ_პარ_შაბ'.split('_'),
		თ_ხუ_პა_შა'.split('_'),


















				, function (





























		/0|1-ლი|მე-\d{1,2}|\d{1,2}-ე/,
		r) {























})));

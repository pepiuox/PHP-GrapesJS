// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: https://codemirror.net/LICENSE


// SAS mode copyright (c) 2016 Jared Dean, SAS Institute
// Created by Jared Dean

// TODO
// indent and de-indent
// identify macro variables


//Definitions
//  comment -- text within * ; or /* */
//  keyword -- SAS language variable
//  variable -- macro variables starts with '&' or variable formats
//  variable-2 -- DATA Step, proc, or macro names
//  string -- text within ' ' or " "
//  operator -- numeric operator + / - * ** le eq ge ... and so on
//  builtin -- proc %macro data run mend
//  atom
//  def

(function(mod) {
  if (typeof exports == "object" && typeof module == "object") // CommonJS
	(require("../../lib/codemirror"));
  else if (typeof define == "function" && define.amd) // AMD
	ine(["../../lib/codemirror"], mod);
  else // Plain browser env
	(CodeMirror);
})(function(CodeMirror) {
  "use strict";

  CodeMirror.defineMode("sas", function () {
	 words = {};
	 isDoubleOperatorSym = {
	q: 'operator',
	t: 'operator',
	e: 'operator',
	t: 'operator',
	e: 'operator',
	in": 'operator',
	e: 'operator',
	r: 'operator'

	 isDoubleOperatorChar = /(<=|>=|!=|<>)/;
	 isSingleOperatorChar = /[=\(:\),{}.*<>+\-\/^\[\]]/;

	Takes a string of words separated by spaces and adds them as
	keys with the value of the first argument 'style'
	ction define(style, string, context) {
	f (context) {
		lit = string.split(' ');
		ar i = 0; i < split.length; i++) {
		s[split[i]] = {style: style, state: context};



	atastep
	ine('def', 'stack pgm view source debug nesting nolist', ['inDataStep']);
	ine('def', 'if while until for do do; end end; then else cancel', ['inDataStep']);
	ine('def', 'label format _n_ _error_', ['inDataStep']);
	ine('def', 'ALTER BUFNO BUFSIZE CNTLLEV COMPRESS DLDMGACTION ENCRYPT ENCRYPTKEY EXTENDOBSCOUNTER GENMAX GENNUM INDEX LABEL OBSBUF OUTREP PW PWREQ READ REPEMPTY REPLACE REUSE ROLE SORTEDBY SPILL TOBSNO TYPE WRITE FILECLOSE FIRSTOBS IN OBS POINTOBS WHERE WHEREUP IDXNAME IDXWHERE DROP KEEP RENAME', ['inDataStep']);
	ine('def', 'filevar finfo finv fipname fipnamel fipstate first firstobs floor', ['inDataStep']);
	ine('def', 'varfmt varinfmt varlabel varlen varname varnum varray varrayx vartype verify vformat vformatd vformatdx vformatn vformatnx vformatw vformatwx vformatx vinarray vinarrayx vinformat vinformatd vinformatdx vinformatn vinformatnx vinformatw vinformatwx vinformatx vlabel vlabelx vlength vlengthx vname vnamex vnferr vtype vtypex weekday', ['inDataStep']);
	ine('def', 'zipfips zipname zipnamel zipstate', ['inDataStep']);
	ine('def', 'put putc putn', ['inDataStep']);
	ine('builtin', 'data run', ['inDataStep']);


	roc
	ine('def', 'data', ['inProc']);

	flow control for macros
	ine('def', '%if %end %end; %else %else; %do %do; %then', ['inMacro']);

	verywhere
	ine('builtin', 'proc run; quit; libname filename %macro %mend option options', ['ALL']);

	ine('def', 'footnote title libname ods', ['ALL']);
	ine('def', '%let %put %global %sysfunc %eval ', ['ALL']);
	automatic macro variables http://support.sas.com/documentation/cdl/en/mcrolref/61885/HTML/default/viewer.htm#a003167023.htm
	ine('variable', '&sysbuffr &syscc &syscharwidth &syscmd &sysdate &sysdate9 &sysday &sysdevic &sysdmg &sysdsn &sysencoding &sysenv &syserr &syserrortext &sysfilrc &syshostname &sysindex &sysinfo &sysjobid &syslast &syslckrc &syslibrc &syslogapplname &sysmacroname &sysmenv &sysmsg &sysncpu &sysodspath &sysparm &syspbuff &sysprocessid &sysprocessname &sysprocname &sysrc &sysscp &sysscpl &sysscpl &syssite &sysstartid &sysstartname &systcpiphostname &systime &sysuserid &sysver &sysvlong &sysvlong4 &syswarningtext', ['ALL']);

	ootnote[1-9]? title[1-9]?

	ptions statement
	ine('def', 'source2 nosource2 page pageno pagesize', ['ALL']);

	roc and datastep
	ine('def', '_all_ _character_ _cmd_ _freq_ _i_ _infile_ _last_ _msg_ _null_ _numeric_ _temporary_ _type_ abort abs addr adjrsq airy alpha alter altlog altprint and arcos array arsin as atan attrc attrib attrn authserver autoexec awscontrol awsdef awsmenu awsmenumerge awstitle backward band base betainv between blocksize blshift bnot bor brshift bufno bufsize bxor by byerr byline byte calculated call cards cards4 catcache cbufno cdf ceil center cexist change chisq cinv class cleanup close cnonct cntllev coalesce codegen col collate collin column comamid comaux1 comaux2 comdef compbl compound compress config continue convert cos cosh cpuid create cross crosstab css curobs cv daccdb daccdbsl daccsl daccsyd dacctab dairy datalines datalines4 datejul datepart datetime day dbcslang dbcstype dclose ddfm ddm delete delimiter depdb depdbsl depsl depsyd deptab dequote descending descript design= device dflang dhms dif digamma dim dinfo display distinct dkricond dkrocond dlm dnum do dopen doptname doptnum dread drop dropnote dsname dsnferr echo else emaildlg emailid emailpw emailserver emailsys encrypt end endsas engine eof eov erf erfc error errorcheck errors exist exp fappend fclose fcol fdelete feedback fetch fetchobs fexist fget file fileclose fileexist filefmt filename fileref  fmterr fmtsearch fnonct fnote font fontalias  fopen foptname foptnum force formatted formchar formdelim formdlim forward fpoint fpos fput fread frewind frlen from fsep fuzz fwrite gaminv gamma getoption getvarc getvarn go goto group gwindow hbar hbound helpenv helploc hms honorappearance hosthelp hostprint hour hpct html hvar ibessel ibr id if index indexc indexw initcmd initstmt inner input inputc inputn inr insert int intck intnx into intrr invaliddata irr is jbessel join juldate keep kentb kurtosis label lag last lbound leave left length levels lgamma lib  library libref line linesize link list log log10 log2 logpdf logpmf logsdf lostcard lowcase lrecl ls macro macrogen maps mautosource max maxdec maxr mdy mean measures median memtype merge merror min minute missing missover mlogic mod mode model modify month mopen mort mprint mrecall msglevel msymtabmax mvarsize myy n nest netpv new news nmiss no nobatch nobs nocaps nocardimage nocenter nocharcode nocmdmac nocol nocum nodate nodbcs nodetails nodmr nodms nodmsbatch nodup nodupkey noduplicates noechoauto noequals noerrorabend noexitwindows nofullstimer noicon noimplmac noint nolist noloadlist nomiss nomlogic nomprint nomrecall nomsgcase nomstored nomultenvappl nonotes nonumber noobs noovp nopad nopercent noprint noprintinit normal norow norsasuser nosetinit  nosplash nosymbolgen note notes notitle notitles notsorted noverbose noxsync noxwait npv null number numkeys nummousekeys nway obs  on open	 o	 ordinal otherwise out outer outp= output over ovp p(1 5 10 25 50 75 90 95 99) pad pad2  paired parm parmcards path pathdll pathname pdf peek peekc pfkey pmf point poisson poke position printer probbeta probbnml probchi probf probgam probhypr probit probnegb probnorm probsig probt procleave prt ps  pw pwreq qtr quote r ranbin rancau random ranexp rangam range ranks rannor ranpoi rantbl rantri ranuni rcorr read recfm register regr remote remove rename repeat repeated replace resolve retain return reuse reverse rewind right round rsquare rtf rtrace rtraceloc s s2 samploc sasautos sascontrol sasfrscr sasmsg sasmstore sasscript sasuser saving scan sdf second select selection separated seq serror set setcomm setot sign simple sin sinh siteinfo skewness skip sle sls sortedby sortpgm sortseq sortsize soundex  spedis splashlocation split spool sqrt start std stderr stdin stfips stimer stname stnamel stop stopover sub subgroup subpopn substr sum sumwgt symbol symbolgen symget symput sysget sysin sysleave sysmsg sysparm sysprint sysprintfont sysprod sysrc system t table tables tan tanh tapeclose tbufsize terminal test then timepart tinv  tnonct to today tol tooldef totper transformout translate trantab tranwrd trigamma trim trimn trunc truncover type unformatted uniform union until upcase update user usericon uss validate value var  weight when where while wincharset window work workinit workterm write wsum xsync xwait yearcutoff yes yyq  min max', ['inDataStep', 'inProc']);
	ine('operator', 'and not ', ['inDataStep', 'inProc']);

	Main function
	ction tokenize(stream, state) {
	/ Finally advance the stream
	ar ch = stream.next();

	/ BLOCKCOMMENT
	f (ch === '/' && stream.eat('*')) {
		continueComment = true;
		 "comment";
	 else if (state.continueComment === true) { // in comment block
		ent ends at the beginning of the line
		 === '*' && stream.peek() === '/') {
		am.next();
		e.continueComment = false;
		 if (stream.skipTo('*')) { //comment is potentially later in line
		am.skipTo('*');
		am.next();
		stream.eat('/'))
			tinueComment = false;
		 {
		am.skipToEnd();

		 "comment";


	f (ch == "*" && stream.column() == stream.indentation()) {
		.skipToEnd()
		 "comment"


	/ DoubleOperator match
	ar doubleOperator = ch + stream.peek();

	f ((ch === '"' || ch === "'") && !state.continueString) {
		continueString = ch
		 "string"
	 else if (state.continueString) {
		ate.continueString == ch) {
		e.continueString = null;
		 if (stream.skipTo(state.continueString)) {
		uote found on this line
		am.next();
		e.continueString = null;
		 {
		am.skipToEnd();

		 "string";
	 else if (state.continueString !== null && stream.eol()) {
		.skipTo(state.continueString) || stream.skipToEnd();
		 "string";
	 else if (/[\d\.]/.test(ch)) { //find numbers
		 === ".")
		am.match(/^[0-9]+([eE][\-+]?[0-9]+)?/);
		f (ch === "0")
		am.match(/^[xX][0-9a-fA-F]+/) || stream.match(/^0[0-7]+/);

		am.match(/^[0-9]*\.?[0-9]*([eE][\-+]?[0-9]+)?/);
		 "number";
	 else if (isDoubleOperatorChar.test(ch + stream.peek())) { // TWO SYMBOL TOKENS
		.next();
		 "operator";
	 else if (isDoubleOperatorSym.hasOwnProperty(doubleOperator)) {
		.next();
		ream.peek() === ' ')
		rn isDoubleOperatorSym[doubleOperator.toLowerCase()];
	 else if (isSingleOperatorChar.test(ch)) { // SINGLE SYMBOL TOKENS
		 "operator";


	/ Matches one whole word -- even if the word is a character
	ar word;
	f (stream.match(/[%&;\w]+/, false) != null) {
		 ch + stream.match(/[%&;\w]+/, true);
		/.test(word)) return 'variable'
	 else {
		 ch;

	/ the word after DATA PROC or MACRO
	f (state.nextword) {
		.match(/[\w]+/);
		ch memname.libname
		ream.peek() === '.') stream.skipTo(' ');
		nextword = false;
		 'variable-2';


	ord = word.toLowerCase()
	/ Are we in a DATA Step?
	f (state.inDataStep) {
		rd === 'run;' || stream.match(/run\s;/)) {
		e.inDataStep = false;
		rn 'builtin';

		iable formats
		ord) && stream.next() === '.') {
		ther a format or libname.memname
		/\w/.test(stream.peek())) return 'variable-2';
		 return 'variable';

		we have a DATA Step keyword
		rd && words.hasOwnProperty(word) &&
			rd].state.indexOf("inDataStep") !== -1 ||
			rd].state.indexOf("ALL") !== -1)) {
		ckup to the start of the word
		stream.start < stream.pos)
			ckUp(stream.pos - stream.start);
		vance the length of the word and return
		(var i = 0; i < word.length; ++i) stream.next();
		rn words[word].style;


	/ Are we in an Proc statement?
	f (state.inProc) {
		rd === 'run;' || word === 'quit;') {
		e.inProc = false;
		rn 'builtin';

		we have a proc keyword
		rd && words.hasOwnProperty(word) &&
			rd].state.indexOf("inProc") !== -1 ||
			rd].state.indexOf("ALL") !== -1)) {
		am.match(/[\w]+/);
		rn words[word].style;


	/ Are we in a Macro statement?
	f (state.inMacro) {
		rd === '%mend') {
		stream.peek() === ';') stream.next();
		e.inMacro = false;
		rn 'builtin';

		rd && words.hasOwnProperty(word) &&
			rd].state.indexOf("inMacro") !== -1 ||
			rd].state.indexOf("ALL") !== -1)) {
		am.match(/[\w]+/);
		rn words[word].style;


		 'atom';

	/ Do we have Keywords specific words?
	f (word && words.hasOwnProperty(word)) {
		ates the initial next()
		.backUp(1);
		ually move the stream
		.match(/[\w]+/);
		rd === 'data' && /=/.test(stream.peek()) === false) {
		e.inDataStep = true;
		e.nextword = true;
		rn 'builtin';

		rd === 'proc') {
		e.inProc = true;
		e.nextword = true;
		rn 'builtin';

		rd === '%macro') {
		e.inMacro = true;
		e.nextword = true;
		rn 'builtin';

		itle[1-9]/.test(word)) return 'def';

		rd === 'footnote') {
		am.eat(/[1-9]/);
		rn 'def';


		urns their value as state in the prior define methods
		ate.inDataStep === true && words[word].state.indexOf("inDataStep") !== -1)
		rn words[word].style;
		ate.inProc === true && words[word].state.indexOf("inProc") !== -1)
		rn words[word].style;
		ate.inMacro === true && words[word].state.indexOf("inMacro") !== -1)
		rn words[word].style;
		rds[word].state.indexOf("ALL") !== -1)
		rn words[word].style;
		 null;

	/ Unrecognized syntax
	eturn null;


	urn {
	tartState: function () {
		 {
		taStep: false,
		oc: false,
		cro: false,
		word: false,
		inueString: null,
		inueComment: false

	,
	oken: function (stream, state) {
		ip the spaces, but regex will account for them either way
		ream.eatSpace()) return null;
		through the main process
		 tokenize(stream, state);
	,

	lockCommentStart: "/*",
	lockCommentEnd: "*/"


  });

  CodeMirror.defineMIME("text/x-sas", "sas");
});

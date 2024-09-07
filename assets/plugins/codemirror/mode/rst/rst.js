// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: https://codemirror.net/LICENSE

(function(mod) {
  if (typeof exports == "object" && typeof module == "object") // CommonJS
	(require("../../lib/codemirror"), require("../python/python"), require("../stex/stex"), require("../../addon/mode/overlay"));
  else if (typeof define == "function" && define.amd) // AMD
	ine(["../../lib/codemirror", "../python/python", "../stex/stex", "../../addon/mode/overlay"], mod);
  else // Plain browser env
	(CodeMirror);
})(function(CodeMirror) {
"use strict";

CodeMirror.defineMode('rst', function (config, options) {

  var rx_strong = /^\*\*[^\*\s](?:[^\*]*[^\*\s])?\*\*/;
  var rx_emphasis = /^\*[^\*\s](?:[^\*]*[^\*\s])?\*/;
  var rx_literal = /^``[^`\s](?:[^`]*[^`\s])``/;

  var rx_number = /^(?:[\d]+(?:[\.,]\d+)*)/;
  var rx_positive = /^(?:\s\+[\d]+(?:[\.,]\d+)*)/;
  var rx_negative = /^(?:\s\-[\d]+(?:[\.,]\d+)*)/;

  var rx_uri_protocol = "[Hh][Tt][Tt][Pp][Ss]?://";
  var rx_uri_domain = "(?:[\\d\\w.-]+)\\.(?:\\w{2,6})";
  var rx_uri_path = "(?:/[\\d\\w\\#\\%\\&\\-\\.\\,\\/\\:\\=\\?\\~]+)*";
  var rx_uri = new RegExp("^" + rx_uri_protocol + rx_uri_domain + rx_uri_path);

  var overlay = {
	en: function (stream) {

	f (stream.match(rx_strong) && stream.match (/\W+|$/, false))
		 'strong';
	f (stream.match(rx_emphasis) && stream.match (/\W+|$/, false))
		 'em';
	f (stream.match(rx_literal) && stream.match (/\W+|$/, false))
		 'string-2';
	f (stream.match(rx_number))
		 'number';
	f (stream.match(rx_positive))
		 'positive';
	f (stream.match(rx_negative))
		 'negative';
	f (stream.match(rx_uri))
		 'link';

	hile (stream.next() != null) {
		ream.match(rx_strong, false)) break;
		ream.match(rx_emphasis, false)) break;
		ream.match(rx_literal, false)) break;
		ream.match(rx_number, false)) break;
		ream.match(rx_positive, false)) break;
		ream.match(rx_negative, false)) break;
		ream.match(rx_uri, false)) break;


	eturn null;

  };

  var mode = CodeMirror.getMode(
	fig, options.backdrop || 'rst-base'
  );

  return CodeMirror.overlayMode(mode, overlay, true); // combine
}, 'python', 'stex');

///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

CodeMirror.defineMode('rst-base', function (config) {

  ///////////////////////////////////////////////////////////////////////////
  ///////////////////////////////////////////////////////////////////////////

  function format(string) {
	 args = Array.prototype.slice.call(arguments, 1);
	urn string.replace(/{(\d+)}/g, function (match, n) {
	eturn typeof args[n] != 'undefined' ? args[n] : match;

  }

  ///////////////////////////////////////////////////////////////////////////
  ///////////////////////////////////////////////////////////////////////////

  var mode_python = CodeMirror.getMode(config, 'python');
  var mode_stex = CodeMirror.getMode(config, 'stex');

  ///////////////////////////////////////////////////////////////////////////
  ///////////////////////////////////////////////////////////////////////////

  var SEPA = "\\s+";
  var TAIL = "(?:\\s*|\\W|$)",
  rx_TAIL = new RegExp(format('^{0}', TAIL));

  var NAME =
	:[^\\W\\d_](?:[\\w!\"#$%&'()\\*\\+,\\-\\.\/:;<=>\\?]*[^\\W_])?)",
  rx_NAME = new RegExp(format('^{0}', NAME));
  var NAME_WWS =
	:[^\\W\\d_](?:[\\w\\s!\"#$%&'()\\*\\+,\\-\\.\/:;<=>\\?]*[^\\W_])?)";
  var REF_NAME = format('(?:{0}|`{1}`)', NAME, NAME_WWS);

  var TEXT1 = "(?:[^\\s\\|](?:[^\\|]*[^\\s\\|])?)";
  var TEXT2 = "(?:[^\\`]+)",
  rx_TEXT2 = new RegExp(format('^{0}', TEXT2));

  var rx_section = new RegExp(
	[!'#$%&\"()*+,-./:;<=>?@\\[\\\\\\]^_`{|}~])\\1{3,}\\s*$");
  var rx_explicit = new RegExp(
	mat('^\\.\\.{0}', SEPA));
  var rx_link = new RegExp(
	mat('^_{0}:{1}|^__:{1}', REF_NAME, TAIL));
  var rx_directive = new RegExp(
	mat('^{0}::{1}', REF_NAME, TAIL));
  var rx_substitution = new RegExp(
	mat('^\\|{0}\\|{1}{2}::{3}', TEXT1, SEPA, REF_NAME, TAIL));
  var rx_footnote = new RegExp(
	mat('^\\[(?:\\d+|#{0}?|\\*)]{1}', REF_NAME, TAIL));
  var rx_citation = new RegExp(
	mat('^\\[{0}\\]{1}', REF_NAME, TAIL));

  var rx_substitution_ref = new RegExp(
	mat('^\\|{0}\\|', TEXT1));
  var rx_footnote_ref = new RegExp(
	mat('^\\[(?:\\d+|#{0}?|\\*)]_', REF_NAME));
  var rx_citation_ref = new RegExp(
	mat('^\\[{0}\\]_', REF_NAME));
  var rx_link_ref1 = new RegExp(
	mat('^{0}__?', REF_NAME));
  var rx_link_ref2 = new RegExp(
	mat('^`{0}`_', TEXT2));

  var rx_role_pre = new RegExp(
	mat('^:{0}:`{1}`{2}', NAME, TEXT2, TAIL));
  var rx_role_suf = new RegExp(
	mat('^`{1}`:{0}:{2}', NAME, TEXT2, TAIL));
  var rx_role = new RegExp(
	mat('^:{0}:{1}', NAME, TAIL));

  var rx_directive_name = new RegExp(format('^{0}', REF_NAME));
  var rx_directive_tail = new RegExp(format('^::{0}', TAIL));
  var rx_substitution_text = new RegExp(format('^\\|{0}\\|', TEXT1));
  var rx_substitution_sepa = new RegExp(format('^{0}', SEPA));
  var rx_substitution_name = new RegExp(format('^{0}', REF_NAME));
  var rx_substitution_tail = new RegExp(format('^::{0}', TAIL));
  var rx_link_head = new RegExp("^_");
  var rx_link_name = new RegExp(format('^{0}|_', REF_NAME));
  var rx_link_tail = new RegExp(format('^:{0}', TAIL));

  var rx_verbatim = new RegExp('^::\\s*$');
  var rx_examples = new RegExp('^\\s+(?:>>>|In \\[\\d+\\]:)\\s');

  ///////////////////////////////////////////////////////////////////////////
  ///////////////////////////////////////////////////////////////////////////

  function to_normal(stream, state) {
	 token = null;

	(stream.sol() && stream.match(rx_examples, false)) {
	hange(state, to_mode, {
		mode_python, local: CodeMirror.startState(mode_python)
	);
	lse if (stream.sol() && stream.match(rx_explicit)) {
	hange(state, to_explicit);
	oken = 'meta';
	lse if (stream.sol() && stream.match(rx_section)) {
	hange(state, to_normal);
	oken = 'header';
	lse if (phase(state) == rx_role_pre ||
			.match(rx_role_pre, false)) {

	witch (stage(state)) {
	ase 0:
		(state, to_normal, context(rx_role_pre, 1));
		.match(/^:/);
		= 'meta';

	ase 1:
		(state, to_normal, context(rx_role_pre, 2));
		.match(rx_NAME);
		= 'keyword';

		ream.current().match(/^(?:math|latex)/)) {
		e.tmp_stex = true;


	ase 2:
		(state, to_normal, context(rx_role_pre, 3));
		.match(/^:`/);
		= 'meta';

	ase 3:
		ate.tmp_stex) {
		e.tmp_stex = undefined; state.tmp = {
			e_stex, local: CodeMirror.startState(mode_stex)



		ate.tmp) {
		stream.peek() == '`') {
			ate, to_normal, context(rx_role_pre, 4));
			 = undefined;



		n = state.tmp.mode.token(stream, state.tmp.local);
		k;


		(state, to_normal, context(rx_role_pre, 4));
		.match(rx_TEXT2);
		= 'string';

	ase 4:
		(state, to_normal, context(rx_role_pre, 5));
		.match(/^`/);
		= 'meta';

	ase 5:
		(state, to_normal, context(rx_role_pre, 6));
		.match(rx_TAIL);

	efault:
		(state, to_normal);

	lse if (phase(state) == rx_role_suf ||
			.match(rx_role_suf, false)) {

	witch (stage(state)) {
	ase 0:
		(state, to_normal, context(rx_role_suf, 1));
		.match(/^`/);
		= 'meta';

	ase 1:
		(state, to_normal, context(rx_role_suf, 2));
		.match(rx_TEXT2);
		= 'string';

	ase 2:
		(state, to_normal, context(rx_role_suf, 3));
		.match(/^`:/);
		= 'meta';

	ase 3:
		(state, to_normal, context(rx_role_suf, 4));
		.match(rx_NAME);
		= 'keyword';

	ase 4:
		(state, to_normal, context(rx_role_suf, 5));
		.match(/^:/);
		= 'meta';

	ase 5:
		(state, to_normal, context(rx_role_suf, 6));
		.match(rx_TAIL);

	efault:
		(state, to_normal);

	lse if (phase(state) == rx_role || stream.match(rx_role, false)) {

	witch (stage(state)) {
	ase 0:
		(state, to_normal, context(rx_role, 1));
		.match(/^:/);
		= 'meta';

	ase 1:
		(state, to_normal, context(rx_role, 2));
		.match(rx_NAME);
		= 'keyword';

	ase 2:
		(state, to_normal, context(rx_role, 3));
		.match(/^:/);
		= 'meta';

	ase 3:
		(state, to_normal, context(rx_role, 4));
		.match(rx_TAIL);

	efault:
		(state, to_normal);

	lse if (phase(state) == rx_substitution_ref ||
			.match(rx_substitution_ref, false)) {

	witch (stage(state)) {
	ase 0:
		(state, to_normal, context(rx_substitution_ref, 1));
		.match(rx_substitution_text);
		= 'variable-2';

	ase 1:
		(state, to_normal, context(rx_substitution_ref, 2));
		ream.match(/^_?_?/)) token = 'link';

	efault:
		(state, to_normal);

	lse if (stream.match(rx_footnote_ref)) {
	hange(state, to_normal);
	oken = 'quote';
	lse if (stream.match(rx_citation_ref)) {
	hange(state, to_normal);
	oken = 'quote';
	lse if (stream.match(rx_link_ref1)) {
	hange(state, to_normal);
	f (!stream.peek() || stream.peek().match(/^\W$/)) {
		= 'link';

	lse if (phase(state) == rx_link_ref2 ||
			.match(rx_link_ref2, false)) {

	witch (stage(state)) {
	ase 0:
		tream.peek() || stream.peek().match(/^\W$/)) {
		ge(state, to_normal, context(rx_link_ref2, 1));
		 {
		am.match(rx_link_ref2);


	ase 1:
		(state, to_normal, context(rx_link_ref2, 2));
		.match(/^`/);
		= 'link';

	ase 2:
		(state, to_normal, context(rx_link_ref2, 3));
		.match(rx_TEXT2);

	ase 3:
		(state, to_normal, context(rx_link_ref2, 4));
		.match(/^`_/);
		= 'link';

	efault:
		(state, to_normal);

	lse if (stream.match(rx_verbatim)) {
	hange(state, to_verbatim);


	e {
	f (stream.next()) change(state, to_normal);


	urn token;
  }

  ///////////////////////////////////////////////////////////////////////////
  ///////////////////////////////////////////////////////////////////////////

  function to_explicit(stream, state) {
	 token = null;

	(phase(state) == rx_substitution ||
		.match(rx_substitution, false)) {

	witch (stage(state)) {
	ase 0:
		(state, to_explicit, context(rx_substitution, 1));
		.match(rx_substitution_text);
		= 'variable-2';

	ase 1:
		(state, to_explicit, context(rx_substitution, 2));
		.match(rx_substitution_sepa);

	ase 2:
		(state, to_explicit, context(rx_substitution, 3));
		.match(rx_substitution_name);
		= 'keyword';

	ase 3:
		(state, to_explicit, context(rx_substitution, 4));
		.match(rx_substitution_tail);
		= 'meta';

	efault:
		(state, to_normal);

	lse if (phase(state) == rx_directive ||
			.match(rx_directive, false)) {

	witch (stage(state)) {
	ase 0:
		(state, to_explicit, context(rx_directive, 1));
		.match(rx_directive_name);
		= 'keyword';

		ream.current().match(/^(?:math|latex)/))
		e.tmp_stex = true;
		f (stream.current().match(/^python/))
		e.tmp_py = true;

	ase 1:
		(state, to_explicit, context(rx_directive, 2));
		.match(rx_directive_tail);
		= 'meta';

		ream.match(/^latex\s*$/) || state.tmp_stex) {
		e.tmp_stex = undefined; change(state, to_mode, {
			e_stex, local: CodeMirror.startState(mode_stex)



	ase 2:
		(state, to_explicit, context(rx_directive, 3));
		ream.match(/^python\s*$/) || state.tmp_py) {
		e.tmp_py = undefined; change(state, to_mode, {
			e_python, local: CodeMirror.startState(mode_python)



	efault:
		(state, to_normal);

	lse if (phase(state) == rx_link || stream.match(rx_link, false)) {

	witch (stage(state)) {
	ase 0:
		(state, to_explicit, context(rx_link, 1));
		.match(rx_link_head);
		.match(rx_link_name);
		= 'link';

	ase 1:
		(state, to_explicit, context(rx_link, 2));
		.match(rx_link_tail);
		= 'meta';

	efault:
		(state, to_normal);

	lse if (stream.match(rx_footnote)) {
	hange(state, to_normal);
	oken = 'quote';
	lse if (stream.match(rx_citation)) {
	hange(state, to_normal);
	oken = 'quote';


	e {
	tream.eatSpace();
	f (stream.eol()) {
		(state, to_normal);
	 else {
		.skipToEnd();
		(state, to_comment);
		= 'comment';



	urn token;
  }

  ///////////////////////////////////////////////////////////////////////////
  ///////////////////////////////////////////////////////////////////////////

  function to_comment(stream, state) {
	urn as_block(stream, state, 'comment');
  }

  function to_verbatim(stream, state) {
	urn as_block(stream, state, 'meta');
  }

  function as_block(stream, state, token) {
	(stream.eol() || stream.eatSpace()) {
	tream.skipToEnd();
	eturn token;
	lse {
	hange(state, to_normal);
	eturn null;

  }

  ///////////////////////////////////////////////////////////////////////////
  ///////////////////////////////////////////////////////////////////////////

  function to_mode(stream, state) {

	(state.ctx.mode && state.ctx.local) {

	f (stream.sol()) {
		tream.eatSpace()) change(state, to_normal);
		 null;


	eturn state.ctx.mode.token(stream, state.ctx.local);


	nge(state, to_normal);
	urn null;
  }

  ///////////////////////////////////////////////////////////////////////////
  ///////////////////////////////////////////////////////////////////////////

  function context(phase, stage, mode, local) {
	urn {phase: phase, stage: stage, mode: mode, local: local};
  }

  function change(state, tok, ctx) {
	te.tok = tok;
	te.ctx = ctx || {};
  }

  function stage(state) {
	urn state.ctx.stage || 0;
  }

  function phase(state) {
	urn state.ctx.phase;
  }

  ///////////////////////////////////////////////////////////////////////////
  ///////////////////////////////////////////////////////////////////////////

  return {
	rtState: function () {
	eturn {tok: to_normal, ctx: context(undefined, 0)};


	yState: function (state) {
	ar ctx = state.ctx, tmp = state.tmp;
	f (ctx.local)
		{mode: ctx.mode, local: CodeMirror.copyState(ctx.mode, ctx.local)};
	f (tmp)
		{mode: tmp.mode, local: CodeMirror.copyState(tmp.mode, tmp.local)};
	eturn {tok: state.tok, ctx: ctx, tmp: tmp};


	erMode: function (state) {
	eturn state.tmp	  	tate: state.tmp.local, mode: state.tmp.mode}
	 state.ctx.mode ? {state: state.ctx.local, mode: state.ctx.mode}
	 null;


	en: function (stream, state) {
	eturn state.tok(stream, state);

  };
}, 'python', 'stex');

///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

CodeMirror.defineMIME('text/x-rst', 'rst');

///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

});

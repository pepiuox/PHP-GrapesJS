<script src="<?php echo SITE_PATH; ?>assets/js/image-scale.min.js" type="text/javascript"></script>
<style>

	.myinfo{
		list-style: none;
		margin: 0px;
		padding: 0px;
	}
	.myinfo li{
		margin: 0px;
		padding: 0px;
	}
	.myinfo img{
		width: 30px;
		height: 30px;
	}
	.cabout{
		margin-top: 60px;
	}
	.scrollpanel {
		margin: 0px;
		width: 100%;
		height: auto;
		float: left;
		overflow-x: hidden;
		overflow: auto;
		border-left: 1px solid #4d4d4d;
	}
	.sconte {
		font-family: 'proximanova-regular';
		width: 100%;
		height: 100%;
		font-size : 12px;
		font-weight: 100;
		font-family: 'proximanova-regular';
		text-align: justify;
		padding: 5px 10px;
		text-justify: inter-word;
	}
	@media screen
	and (min-device-width: 320px)
	and (max-device-width: 768px)
	{
		.scrollpanel {
			border: 0px;
		}
		.myinfo,
		.sconte{
			margin-top: 20px;
		}
	}

	form.br{
		font-size: 13px;
		color: #999999;
		padding-left: 20px;
		border-left: 1px solid #999999;
	}
	form.br label{
		color: #999999;
		font-size: 13px;
	}
	.infocontact{
		float: right;

	}
	.infocontact .imginfo{

		display: block;
		width: 100%;
		height: auto;
		margin:8px 0 10px 0;
	}
	.infocontact .imginfo img{
		width: 100%;
		height: auto;
	}
	.infocontact .tinfo{
		vertical-align: middle;
		padding: 0;
		margin: 2px 0px;
		display: block;
		width: 100%;
		height: auto;
	}
	.infocontact .tinfo img{
		display: inline;
		width: 15px;
		height: 15px;
		margin: 0;
		padding: 0;
	}
	.infocontact a{
		color: #222222;
	}
	.infocontact span{
		height: 15px;
		margin-bottom: 5px;
	}
	.infocontact p{
		font-size: 12px;
		color: #999999;
		text-align: left;
		margin-right: 10px;
	}
	.infocontact span img{
		vertical-align: middle;
		width: 15px;
		height: 15px;
	}
	.infcont{
		font-size: 13px;
		width: 100%;
		text-align: right;
		padding-right: 40px;
	}
	.error {
		font-size: 13px;
		color: #FF0000;
	}
</style>

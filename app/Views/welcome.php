<?php use \Lib\URI; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Crie seu QR Code</title>
	<?= tag_css("index.css"); ?>
	<?= tag_js("index.js"); ?>
</head>
<body>
	<h1>Crie seu QR Code</h1>
	<h3>Sistema desenvolvido por: Matheus Johann Araújo</h3>
	<hr>
	<div>
		<label for="texto">Link ou Texto: </label>
		<br>
		<textarea id="texto" cols="44" rows="4" placeholder="<?= site_url(); ?>"></textarea>
		<br><br>
		<label for="nivel">Redundância: </label>
		<br>
		<select id="nivel">
			<option value="1">Nível 1</option>
			<option value="2">Nível 2</option>
			<option value="3">Nível 3</option>
			<option value="4">Nível 4</option>
		</select>
		<br><br>
		<label for="pixels">Tamanho do Pixel: </label>
		<br>
		<select id="pixels">
			<?php
				for ($i = 4; $i <= 48; $i+=4) {
					?><option value="<?=$i;?>" <?= ($i == 8) ? "selected" : "" ?>><?=$i;?>px</option><?php
				}
			?>
		</select>
		<br><br>
		<label>Tipo da imagem: </label>
		<br>
		<label>
			<input type="radio" name="img" class="tipo" value="jpeg" checked="checked">
			JPEG
		</label>
		<label>
			<input type="radio" name="img" class="tipo" value="png">
			PNG
		</label>
		<br><br>
		<button type="button" id="btnGerar">Gerar QR Code</button>
	</div>
	<br>
	<?= tag_img("index.jpg", ["id" => "img"]); ?>
	<br>
	<hr>
	<p>
		Para utilizar o sistema como <b>API de QR Code</b>, é necessário realizar uma requisição usando o protocolo <b>HTTP</b>, podendo ser usado a tecnologia <b>HTTP REQUEST (AJAX)</b>.<br>
		Os métodos do <b>HTTP</b> compatíveis são: <b>GET, POST, PUT, PATCH, DELETE ou OPTIONS</b>.<br>
		Onde a resposta é uma imagem <b>(QR Code)</b>.<br>
		Informações referentes a cada <b>"key value" (chave valor)</b> do <b>JSON</b>.<br>
		<ul>
			<li><b>"text"</b> <= Texto ou Link: máximo de 2000 caracteres;<br></li>
			<li><b>"redundancy"</b> <= Redundância: 1 até 4;<br></li>
			<li><b>"pixelsize"</b> <= Tamanho do pixel: 4 até 48;<br></li>
			<li><b>"mimetype"</b> <= Tipo: jpeg ou png;<br></li>
			<li><b>"filename"</b> <= Nome do arquivo de imagem.<br></li>
		</ul>
		<hr>
		Para gerar o <b>QR Code</b> usando <b>JSON</b>, utilize a rota <b><?= URI::base(); ?>create</b><br><br>
		E no corpo da requisição coloque um <b>JSON</b> com as seguintes informações:<br>
		<b><pre>
{
   "text": "QRCodeAPI",
   "redundancy": 1,
   "pixelsize": 8,
   "mimetype": "jpeg",
   "filename": "imagem"
}
		</pre></b>		
	</p>
</body>
</html>

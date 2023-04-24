<?php use \Lib\URI; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>QRCode</title>
	<?= tag_css("index.css"); ?>
	<?= tag_js("index.js"); ?>
	<?= tag_favicon("index.jpg", "image/jpg"); ?>
</head>
<body>
	<h1>Crie seu QRCode</h1>
	<h3>Sistema desenvolvido por: Matheus Johann Araújo</h3>
	<hr>
	<div>
		<label for="texto">Link ou Texto: </label>
		<br>
		<textarea id="texto" cols="52" rows="4" placeholder="<?= site_url(); ?>"></textarea>
		<br><br>
		<label for="tamanho">Tamanho do QRCode: </label>
		<br>
		<input type="number" id="tamanho" value="200">
		<br><br>
		<label for="margem">Margem: </label>
		<br>
		<input type="number" id="margem" value="20">
		<br><br>
		<label for="rodape">Texto do rodapé: </label>
		<br>
		<input type="text" id="rodape" value="">
		<br><br>
		<label for="tamanhoRodape">Tamanho do texto do rodapé: </label>
		<br>
		<input type="number" id="tamanhoRodape" value="12">
		<br><br>
		<label for="chave">Chave criptográfica: </label>
		<br>
		<input type="text" id="chave" value="">
		<br><br>
		<button type="button" id="btnGerar">Gerar QR Code</button>
	</div>
	<br>
	<?= tag_img("index.jpg", ["id" => "img"]); ?>
	<br>
	<hr>
	<p>
		Para utilizar o sistema como <b>API de QR Code</b>, é necessário realizar uma requisição usando o protocolo <b>HTTP</b>, podendo ser usado a tecnologia <b>HTTP REQUEST (AJAX)</b>.<br>
		Usar o método <b>GET</b> ou <b>POST</b> do <b>HTTP</b>.<br>
		A resposta será uma imagem <b>(QR Code)</b>.<br>
		Informações referentes a cada <b>"key value" (chave valor)</b>.<br>
		<ul>
			<li><b>"text"</b> <= Texto ou Link, quantidade máxima de caracteres 1200 sem chave criptográfica ou 300 com chave criptográfica;<br></li>
			<li><b>"size"</b> <= Tamanho do QRCode, valor opcional (padrão 200);<br></li>
			<li><b>"margin"</b> <= Espaçamento ao redor do QRCode, valor opcional (padrão 10);<br></li>
			<li><b>"labelText"</b> <= Texto no rodapé do QRCode, valor opcional, (padrao null);<br></li>
			<li><b>"labelSize"</b> <= Tamanho do texto do rodapé do QRCode, valor opcional (padrão 12);<br></li>
			<li><b>"response"</b> <= Resposta da geração do QRCode, valor opcional (padrão "image", pode ser também "string").<br></li>
			<li><b>"key"</b> <= Chave criptográfica do texto do QRCode, valor opcional (padrão null).<br></li>
		</ul>
		<hr>
		<a href="<?= folder_public("QRCode.postman_collection.json"); ?>">Acessar JSON de configuração Postman (QR_Code.postman_collection.json)</a><br><br>
		Para gerar o <b>QR Code</b> através de <b>HTTP POST</b> use o <b>JSON</b> abaixo no seguinte endereço <b><?= route("qrcode.create"); ?></b><br><br>
		E no corpo da requisição coloque um <b>JSON</b> com as seguintes informações:<br>
		<b><pre>
{
	"text": "QRCodeAPI",
	"size": 200,
	"margin": 10,
	"labelText": "QRCodeAPI",
	"labelSize": 12,
	"response": "image",
	"key": "secret"
}
		</pre></b>
		<hr>
		Para gerar o <b>QR Code</b> através do <b>HTTP GET</b> ou <b>POST</b> com parâmetros, use o seguinte endereço <b><?= route("qrcode.create"); ?></b><br>
		Observação: Não é recomendado passar por parâmetros endereço (link), pois o mesmo pode não ser entendido corretamente no caso do uso do <b>HTTP GET</b>,<br>para evitar este tipo de problema faça o <b>Encode URL</b> antes de informar o referido link.<br><br>
		<a href="<?= route("qrcode.create"); ?>?text=QRCodeAPI&size=200&margin=10&labelText=QRCodeAPI&labelSize=12&response=image&key=secret">
			<?= route("qrcode.create"); ?><br>
			?text=QRCodeAPI<br>
			&size=200<br>
			&margin=10<br>
			&labelText=QRCodeAPI<br>
			&labelSize=12<br>
			&response=image
			&key=secret
		</a>
	</p>

	<script>
	window.addEventListener("load", function(){
		btnGerar.addEventListener("click", function() {
			if(texto.value == ""){
				texto.value = "Preencha este campo!"
				texto.style.background = "aqua"
				texto.focus()
				setTimeout(function() {
					texto.value = ""
					texto.style.background = "white"
					texto.focus()
				}, 1500);
				return
			}
			let uri = "<?= route("qrcode.create"); ?>";
			fetch(
				uri,
				{
					headers: {
						"Accept": "application/json",
						"Content-Type": "application/json"
					},
					method: "POST",
					body: JSON.stringify({
						"text": texto.value,
						"size": tamanho.value,
						"margin": margem.value,
						"labelText": rodape.value,
						"labelSize": tamanhoRodape.value,
						"key": chave.value,
						"response": "image"
					})
				}
			)
			.then(response => response.blob())
			.then(blob => {
				console.log(blob);
				img.src = URL.createObjectURL(blob)
			})
		})
	})
	</script>
</body>
</html>


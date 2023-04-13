window.addEventListener("load", function(){
    btnGerar.addEventListener("click", function(){
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
        let tipos = document.querySelectorAll(".tipo")
        let tipo = "jpeg"
        if(tipos[0].checked){
            tipo = "jpeg"
        }else if(tipos[1].checked){
            tipo = "png"
        }
        let uri = `${window.location.origin}${window.location.pathname}create`
        fetch(
            uri,
            {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                method: "POST",
                body: JSON.stringify({
                    "text": texto.value,
                    "redundancy": nivel.value,
                    "pixelsize": pixels.value,
                    "mimetype": tipo,
                    "filename": `imagem_${Math.random()}`
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

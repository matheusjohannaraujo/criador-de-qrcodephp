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
        let uri = `${window.location.origin}${window.location.pathname}create/json`
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
                    "size": tamanho.value
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

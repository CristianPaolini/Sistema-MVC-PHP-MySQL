const formularios_ajax = document.querySelectorAll(".FormularioAjax");

function enviar_formulario_ajax(e) {
    e.preventDefault();

}

formularios_ajax.forEach(formularios => {
    formularios.addEventListener("sumbmit", enviar_formulario_ajax);
});

function alertas_ajax(alerta) {
    if (alerta.Alerta === "simple") {
        Swal.fire({
            title: alerta.Titulo,
            text: alerta.Texto,
            type: alerta.Tipo,
            confirmButtonText: 'Aceptar'
          });
    } else if (alerta.Alerta === "recargar") {
        Swal.fire({
            title: alerta.Titulo,
            text: alerta.Texto,
            type: alerta.Tipo,
            confirmButtonText: 'Aceptar'
          }).then((result) => {
            if (result.value) {
              location.reload();
            }
          });
    } else if (alerta.Alerta === "limpiar") {
        Swal.fire({
            title: alerta.Titulo,
            text: alerta.Texto,
            type: alerta.Tipo,
            confirmButtonText: 'Aceptar'
          }).then((result) => {
            if (result.value) {
              document.querySelector(".FormularioAjax").reset();
            }
          });
    } else if (alerta.Alerta === "redireccionar") {
        window.location.href = alerta.URL;
    }
}
<script>
    function buscar_cliente() {
        let input_cliente = document.querySelector('#input_cliente').value;

        input_cliente = input_cliente.trim();

        if (input_cliente != "") {
            let datos = new FormData();
            datos.append("buscar_cliente", input_cliente);

        fetch("<?php echo SERVERURL; ?>ajax/prestamoAjax.php", {
            method: 'POST',
            body: datos
        })
        .then(respuesta => respuesta.text())
        .then(respuesta => {
            let tabla_clientes = document.querySelector('#tabla_clientes');
            tabla_clientes.innerHTML = respuesta;
        });
        } else {
            Swal.fire({
            title: 'Ocurrió un error',
            text: 'Debe introducir al menos uno de los siguientes valores: DNI, NOMBRE, APELLIDO, TELÉFONO. ',
            type: 'error',
            confirmButtonText: 'Aceptar'
          });
        }
    }
</script>
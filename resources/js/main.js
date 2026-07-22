const table_usuario = $("#table_usuarios").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: "/apiUsuarios",
        type: "GET", // o POST si tu ruta lo requiere
        data: (d) => {
            // Sobrescribes el valor de búsqueda global de DataTables
            d.search = { value: $("#documentoUsuario").val() || "" };
        },
    },
    // Debe haber 5 columnas porque tienes 5 <th>
    columns: [
        {
            data: "checkbox",
            orderable: false,
            searchable: false,
            className: "text-center",
        },
        {
            data: null, // usamos null para que no busque en la base de datos
            render: function (data, type, row, meta) {
                // meta.row es el índice actual (0,1,2,...)
                // sumamos el número de inicio de la página actual
                return meta.row + meta.settings._iDisplayStart + 1;
            },
            className: "text-center",
            orderable: false,
        },
        { data: "documento" },
        { data: "nombre_completo" },
        { data: "descripcion" },
        {
            data: "estado",
            render: function (data, type, row) {
                var estado = row.estado;
                if (estado == 1) {
                    return (
                        '<span class="badge badge-success">' +
                        "ACTIVO" +
                        "</span>"
                    );
                } else {
                    return (
                        '<span class="badge badge-danger">' +
                        "INACTIVO" +
                        "</span>"
                    );
                }
            },
        },
    ],
    order: [[0, "desc"]],
    responsive: true,
    lengthMenu: [
        [10, 25, 50, 100],
        [10, 25, 50, 100],
    ],
    dom:
        "<'row'<'col-sm-12'tr>>" +
        "<'row mt-5'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
});

// búsqueda
let t;
$("#documentoUsuario").on("keyup", function () {
    clearTimeout(t);
    t = setTimeout(() => table_usuario.ajax.reload(), 250);
});

let currenUsuarioId = null;

$(".btn_editarUsuarios").on("click", function () {
    var $checkedCheckboxes = $("#table_usuarios").find(".idusuario:checked");

    if ($checkedCheckboxes.length === 0) {
        swal.fire({
            text: "Seleccionar al menos un registro",
            icon: "warning",
            buttonsStyling: false,
            confirmButtonText: "CANCELAR",
            customClass: {
                confirmButton: "btn btn-primary",
            },
        });
    } else if ($checkedCheckboxes.length > 1) {
        swal.fire({
            text: "Por favor, selecciona solo una casilla",
            icon: "warning",
            buttonsStyling: false,
            confirmButtonText: "CANCELAR",
            customClass: {
                confirmButton: "btn btn-primary",
            },
        });
    } else {
        $("#modal_usuariosEditar").modal("show");

        const modalFormUsuario = document.querySelector("#form_usuarioEditar");

        currenUsuarioId = $checkedCheckboxes.first().data("id");

        const url = "/usuarios";
        const action = url + "/" + currenUsuarioId + "/" + "edit";

        axios.get(action).then(function (response) {
            console.log(response.data);

            const urlForm = document.querySelector(".urlUsuarios");
            urlForm.setAttribute("action", `${response.data.action}`);

            const documentoUsuarioBuscar = modalFormUsuario.querySelector(
                "#documentoUsuarioBuscar"
            );
            documentoUsuarioBuscar.value = `${response.data.usuario.documento}`;

            const nombresUsuario =
                modalFormUsuario.querySelector("#nombresUsuario");
            nombresUsuario.value = `${response.data.usuario.nombres}`;

            const apellidosUsuario =
                modalFormUsuario.querySelector("#apellidosUsuario");
            apellidosUsuario.value = `${response.data.usuario.apellidos}`;

            const nickname = modalFormUsuario.querySelector("#nickname");
            nickname.value = `${response.data.usuario.nickname}`;

            const tipoUsuario_id =
                modalFormUsuario.querySelector("#tipoUsuario_id");

            var opcionesT = "<option value=''>Tipo Usuario</option>";
            for (let i in response.data.tipos) {
                if (
                    response.data.tipos[i].id ==
                    response.data.usuario.tipoUsuario_id
                ) {
                    opcionesT +=
                        '<option value="' +
                        response.data.tipos[i].id +
                        '" selected>' +
                        response.data.tipos[i].descripcion +
                        "</option>";
                } else {
                    opcionesT +=
                        '<option value="' +
                        response.data.tipos[i].id +
                        '">' +
                        response.data.tipos[i].descripcion +
                        "</option>";
                }
            }

            tipoUsuario_id.innerHTML = opcionesT;
        });
    }
});

const form_usuarioEditar = document.querySelector("#form_usuarioEditar");

if (form_usuarioEditar) {
    form_usuarioEditar.addEventListener("submit", (e) => {
        e.preventDefault();

        const urlupdate = "/usuarios";
        const actionUpdate = urlupdate + "/" + currenUsuarioId;
        const formDataT = new FormData(form_usuarioEditar);

        axios.post(actionUpdate, formDataT).then(function (response) {
            const respuesta = response.data;

            const inputs =
                form_usuarioEditar.querySelectorAll(".validate_modal");

            inputs.forEach((input) => {
                // Eliminar errores previos

                input.classList.remove("is-invalid");
                // Buscar el contenedor padre correcto
                const wrapper =
                    input.closest(".fv-row") || input.closest(".input-group");
                const feedback = wrapper
                    ? wrapper.querySelector(".invalid-feedback")
                    : null;
                if (feedback) feedback.innerHTML = "";
                // Si hay errores de validación
                if (respuesta.errorForm && respuesta.errores[input.name]) {
                    input.classList.add("is-invalid"); // Si es un input-group, también marcamos el grupo i
                    if (input.closest(".input-group")) {
                        input
                            .closest(".input-group")
                            .classList.add("is-invalid");
                    }
                    if (feedback) {
                        feedback.innerHTML = respuesta.errores[input.name];
                    }
                }
            });

            if (!respuesta.errorForm) {
                if (respuesta) {
                    $("#modal_usuariosEditar").modal("hide");
                    table_usuario.ajax.reload();

                    //LimpiarFormularioAporte();
                }
            }
        });
    });
}

const form_usuarios = document.querySelector("#form-usuarioGuardar");

if (form_usuarios) {
    form_usuarios.addEventListener("submit", (e) => {
        e.preventDefault();

        const action = "/usuarios";
        const formDataT = new FormData(form_usuarios);

        axios.post(action, formDataT).then(function (response) {
            const respuesta = response.data;

            const inputs = form_usuarios.querySelectorAll(".validate_modal");

            inputs.forEach((input) => {
                input.classList.remove("is-invalid");
                // Buscar el contenedor padre correcto
                const wrapper =
                    input.closest(".fv-row") || input.closest(".input-group");
                const feedback = wrapper
                    ? wrapper.querySelector(".invalid-feedback")
                    : null;
                if (feedback) feedback.innerHTML = "";
                // Si hay errores de validación
                if (respuesta.errorForm && respuesta.errores[input.name]) {
                    input.classList.add("is-invalid"); // Si es un input-group, también marcamos el grupo i
                    if (input.closest(".input-group")) {
                        input
                            .closest(".input-group")
                            .classList.add("is-invalid");
                    }
                    if (feedback) {
                        feedback.innerHTML = respuesta.errores[input.name];
                    }
                }
            });

            if (!respuesta.errorForm) {
                if (respuesta) {
                    $("#modal_usuarios").modal("hide");
                    table_usuario.ajax.reload();
                }
            }
        });
    });
}

const consultaUsuario = document.querySelector("#consultaUsuario");

if (consultaUsuario) {
    consultaUsuario.addEventListener("click", (event) => {
        //console.log("hola mundo");

        const documentoBuscar = document.querySelector(
            "#documentoUsuarioBuscar"
        ).value;
        //  console.log(documentoBuscar);

        if (documentoBuscar === "") {
            $("#resp-dniRegistrarVacio").html(
                '<div class="alert alert-custom alert-notice cerrarAlerta alert-light-danger fade show mb-5" role="alert"><div class="alert-icon"><i class="fas fa-bell"></i></div><div class="alert-text">Por favor, Documento Requerido</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="ki ki-close"></i></span></button></div></div>'
            );
            event.preventDefault(); // Evita que se realice la acción de búsqueda
            return;
        }

        consultaUsuario.setAttribute("data-kt-indicator", "on");

        const inputapellidos = document.querySelector("#apellidosUsuario");
        const inputNombres = document.querySelector("#nombresUsuario");
        const dni = document.querySelector("#documentoUsuarioBuscar");

        axios({
            method: "POST",
            url: "/BuscarDocumentoUsuario",
            data: {
                documento: documentoBuscar,
            },
        }).then((response) => {
            const data = response.data;

            //console.log(data);

            if (data.estados == 200) {
                inputapellidos.value =
                    data.datos["aPa"] + " " + data.datos["aMa"];
                inputNombres.value = data.datos["nom"];

                dni.value = data.datos["dni"];

                consultaUsuario.removeAttribute("data-kt-indicator");
            } else if (data.estados == 400) {
                $("#resp-dniRegistrar").html(
                    '<div class="alert alert-custom alert-notice alert-light-danger cerrarAlerta fade show mb-5" role="alert"><div class="alert-icon"><i class="fas fa-bell"></i></div><div class="alert-text">Solo debe tener 8 digitos</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="ki ki-close"></i></span></button></div></div>'
                );

                consultaUsuario.removeAttribute("data-kt-indicator");
            } else if (data.situacion == false) {
                $("#resp-dniRegistrarVacio").html(
                    '<div class="alert alert-custom alert-notice cerrarAlerta alert-light-danger fade show mb-5" role="alert"><div class="alert-icon"><i class="fas fa-bell"></i></div><div class="alert-text">El campo Documento es requerido</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="ki ki-close"></i></span></button></div></div>'
                );

                consultaUsuario.removeAttribute("data-kt-indicator");
            }
        });
    });
}


const tipo_tramiteRadio = document.querySelectorAll(".tipo_tramiteRadio");

tipo_tramiteRadio.forEach((radio) => {

    radio.addEventListener("change", function (e) {

        const secNatural = document.querySelector(".destino_input");

       console.log(e.target);

        if (e.target.value == "SIN_TUPA") {

            secNatural.classList.remove("hidden");

            const inputs = secNatural.querySelectorAll("input, select");

            inputs.forEach((input) => {
                input.value = "";
            });

        } else if(e.target.value == "TUPA") {

            secNatural.classList.add("hidden");
         
            const inputs = secNatural.querySelectorAll("input, select");

            inputs.forEach((input) => {
                input.value = "";
            });
        }

    });

});
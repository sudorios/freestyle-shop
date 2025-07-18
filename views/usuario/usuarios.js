function abrirModal() {
    document.getElementById('modalEditar').classList.remove('hidden');
    document.getElementById('modalBackground').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modalEditar').classList.add('hidden');
    document.getElementById('modalBackground').classList.add('hidden');
}

function abrirModalPassword() {
    document.getElementById('modalBackground').classList.remove('hidden');
    document.getElementById('modalPassword').classList.remove('hidden');
}

function cerrarModalPassword() {
    document.getElementById('modalBackground').classList.add('hidden');
    document.getElementById('modalPassword').classList.add('hidden');
}

function initEditarUsuario() {
    document.querySelectorAll('.btn-editar').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const nombre = this.dataset.nombre;
            const email = this.dataset.email;
            const nickname = this.dataset.nickname;
            const telefono = this.dataset.telefono;
            const direccion = this.dataset.direccion;
            const rol = this.dataset.rol;
            const estado = this.dataset.estado;

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_nickname').value = nickname;
            document.getElementById('edit_telefono').value = telefono;
            document.getElementById('edit_direccion').value = direccion;

            abrirModal();
        });
    });
}

function initCambiarPassword() {
    document.querySelectorAll('.btn-cambiar-password').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const nombre = this.dataset.nombre;

            document.getElementById('password_id').value = id;
            document.getElementById('modalPassword').querySelector('h3').textContent = `Cambiar Contraseña - ${nombre}`;

            abrirModalPassword();
        });
    });
}

function initCerrarModal() {
    document.getElementById('modalEditar').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModal();
        }
    });
}

function initCerrarModalPassword() {
    document.getElementById('modalPassword').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModalPassword();
        }
    });
}

function initValidarPassword() {
    document.getElementById('formCambiarPassword').addEventListener('submit', function(e) {
        const password = document.getElementById('password_nueva').value;
        const confirmar = document.getElementById('password_confirmar').value;

        if (password !== confirmar) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
        }
    });
}

function usuariosInit() {
    initEditarUsuario();
    initCambiarPassword();
    initCerrarModal();
    initCerrarModalPassword();
    initValidarPassword();
}

document.addEventListener('DOMContentLoaded', usuariosInit);
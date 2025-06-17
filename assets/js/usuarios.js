function abrirModal() {
    document.getElementById('modalEditar').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modalEditar').classList.add('hidden');
}

function abrirModalPassword() {
    document.getElementById('modalPassword').classList.remove('hidden');
}

function cerrarModalPassword() {
    document.getElementById('modalPassword').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
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
            document.getElementById('edit_rol').value = rol;
            document.getElementById('edit_estado').value = estado;

            abrirModal();
        });
    });

    document.querySelectorAll('.btn-cambiar-password').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const nombre = this.dataset.nombre;

            document.getElementById('password_id').value = id;
            document.getElementById('modalPassword').querySelector('h3').textContent = `Cambiar Contraseña - ${nombre}`;

            abrirModalPassword();
        });
    });

    document.getElementById('modalEditar').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModal();
        }
    });

    document.getElementById('modalPassword').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModalPassword();
        }
    });

    document.getElementById('formCambiarPassword').addEventListener('submit', function(e) {
        const password = document.getElementById('password_nueva').value;
        const confirmar = document.getElementById('password_confirmar').value;

        if (password !== confirmar) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
        }
    });
}); 
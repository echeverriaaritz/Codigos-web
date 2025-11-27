document.addEventListener("DOMContentLoaded", function() {
    getAll(); // Nada más cargar, pedimos todos los usuarios para mostrar la lista

    // Añadimos eventos a los botones:
    document.getElementById('btnInsert').addEventListener('click', insertUser);
    document.getElementById('btnDel').addEventListener('click', deleteId);
});

// Función para obtener y mostrar todos los usuarios
function getAll() {
    $.ajax({
        url: "./PHP/getAll/index.php", // URL del PHP que devuelve la lista de usuarios
        dataType: "json",              // Esperamos que la respuesta sea JSON
        success: function(response) {  
            document.getElementById('idContainerGet').innerHTML = "<ul id='idUsers'></ul>";

            for (let i = 0; i < response.data.length; i++) {
                document.getElementById('idUsers').innerHTML +=
                    "<li>id:" + response.data[i].id +
                    " Username:" + response.data[i].username +
                    " Company:" + response.data[i].company + "</li>";
            }
        },
        error: function(xhr) {
            alert("An AJAX error occurred: " + xhr.status + " " + xhr.statusText);
        }
    });
}

// Función para insertar un nuevo usuario
function insertUser() {
    var paramData = {
        username: document.getElementById('inUsername').value,
        password: document.getElementById('inPassword').value,
        company:  document.getElementById('inCompany').value
    };

    $.ajax({
        url: "./PHP/insert/index.php",
        type: 'POST',
        dataType: 'json',
        data: { param: JSON.stringify(paramData) },
        success: function(response) {
            alert(response.msg);
            getAll();
        },
        error: function(xhr) {
            alert("An AJAX error occurred: " + xhr.status + " " + xhr.statusText);
        }
    });
}

// Función para borrar un usuario por ID
function deleteId() {
    var paramData = {
        id: document.getElementById('inUserIdDel').value
    };

    $.ajax({
        url: "./PHP/deleteId/index.php",
        type: 'POST',
        dataType: 'json',
        data: { param: JSON.stringify(paramData) },
        success: function(response) {
            alert(response.msg);
            getAll();
        },
        error: function(xhr) {
            alert("An AJAX error occurred: " + xhr.status + " " + xhr.statusText);
        }
    });
}

$(document).ready(function () {
    $.get("http://localhost:8080/api/v1/authorize?profile=admin&email=admin@admin.ru", function (token) {
        // Сохраняем токен в сессии
        saveTokenToSession(token);

    });
});
var JWT = getTokenFromSession();

// Сохранение токена в cookie
function saveTokenToSession(token) {
    // Устанавливаем срок действия cookie (например, 2 часа)
    var expires = new Date();
    expires.setTime(expires.getTime() + (2 * 60 * 60 * 1000));

    // Записываем токен в cookie
    document.cookie = "session_token=" + token + ";expires=" + expires.toUTCString() + ";path=/";
}

// Получение токена из cookie
function getTokenFromSession() {
    // Разбиваем cookie на отдельные значения
    var cookies = document.cookie.split(";");

    // Ищем нужное значение в cookie
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].trim();
        if (cookie.startsWith("session_token=")) {
            return cookie.substring("session_token=".length, cookie.length);
        }
    }

    return null; // Если токен не найден
}


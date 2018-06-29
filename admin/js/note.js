var note = function(set) {

    // стандартные настройки
    var set = Object.assign({}, {
        width: false,
        customClass: false,
        content: "",
        time: 4.5,
        type: "info"
    }, set);

    // функция создания элементов
    var create = function(name, attr, content, append) {
        var node = document.createElement(name);
        for (var val in attr) {
            node.setAttribute(val.toString(), attr[val]);
        }
        if (content) node.insertAdjacentHTML("afterbegin", content);
        if (append) append.appendChild(node);
        return node;
    }

    // вывод случайного числа из промежутка
    var random = function(min, max) {
        return Math.floor(Math.random() * max) + min;
    }

    // родительский элемент для уведомлений
    var alertBox = document.getElementById("alert-box") || create("div", {
        id: "alert-box"
    }, false, document.body);

    // вывод только одного уведомления при нескольких вызовах
    if (alertBox.hasChildNodes()) {
        while (alertBox.firstChild) {
            alertBox.removeChild(alertBox.firstChild);
        }
    }

    // элемент уведомления
    var alertItem = create("div", {
        "id": "alert-item" + random(1, 999),
        "role": "alert",
        "data-type": set.type,
        "class": "alert-item alert-item-hidden"
    }, set.content);

    // указанная ширина и классы для элемента уведомления
    if (set.width) alertItem.width = set.width;
    if (set.customClass) alertItem.classList.add(set.customClass);

    // функция удаления уведомления
    var remove = function() {
        alertItem.classList.add("alert-item-hidden");
        window.setTimeout(function() {
            alertItem.remove();
        }, 250);
    }

    // вывод уведомления в его родительский элемент
    alertBox.appendChild(alertItem);
    window.getComputedStyle(alertItem).opacity;
    alertItem.classList.remove("alert-item-hidden");

    // удаление уведомления по истечению заданного времени
    window.setTimeout(remove, set.time * 1000);
}
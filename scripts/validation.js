document.getElementById("input-Y").onblur = function() {
    let reg = /^[+-]?\d+[.,]?\d*$/
    if (!reg.test(this.value)) {
        setError("Ошибка: в поле 'Координата Y' не число!", this);
        return false;
    } else {
        let value = parseFloat(this.value);
        if (value < -3.0 || value > 5.0) {
            setError("Ошибка: в поле 'Координата Y' недопустимое число!", this);
            return false;
        } else {
            unsetError(this);
        }
    }
    return true;
};

function setError(text, elem) {
    let button = document.getElementById("button-submit");
    let error = document.querySelector("span.error");

    button.disabled = true;
    error.classList.remove("hidden");
    error.innerHTML = text;
    elem.classList.add("error");
}

function unsetError(elem) {
    let button = document.getElementById("button-submit");
    let error = document.querySelector("span.error");

    button.disabled = false;
    error.classList.add("hidden");
    elem.classList.remove("error");
}

function onclickButton(button) {
    let elements = document.getElementsByName("button-R");
    for (let i = 0; i < elements.length; i++) {
        if (elements[i].classList.contains("pushed"))
            elements[i].classList.remove("pushed");
    }

    (button.classList.contains("pushed"))
        ? button.classList.remove("pushed")
        : button.classList.add("pushed");
    document.getElementById("input-R").value = button.innerHTML;
}

let elements = document.getElementsByName("button-R");
for (let i = 0; i < elements.length; i++) {
    elements[i].onclick = function() { onclickButton(elements[i]) };
}

document.getElementsByName("param")[0].onsubmit = function() {
    return document.getElementById("input-Y").blur();
}


window.onerror = function(msg, url, line) {
    alert("Ошибка: " + msg + "\n" + url + ":" + line);
    return true;
};


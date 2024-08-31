let filtro = document.getElementById("filtro");
console.log(localStorage.getItem("filtro"));

if (localStorage.getItem("filtro") == null) {
    localStorage.setItem("filtro", "asc");
}

if (localStorage.getItem("filtro") == "asc") {
    filtro.setAttribute("name", "desc");
    filtro.setAttribute("value", "Ascendente");
    localStorage.setItem("filtro", "desc");
    console.log("aa");
} else {
    filtro.setAttribute("name", "asc");
    filtro.setAttribute("value", "Descendente");
    localStorage.setItem("filtro", "asc");
    console.log("bb");
}
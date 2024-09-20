document.addEventListener('DOMContentLoaded', function() {
    const check = document.getElementById("cidr-check");
    const cidr = document.getElementById("cidr");
    const maskInputs = [
        document.getElementById("mask1"),
        document.getElementById("mask2"),
        document.getElementById("mask3"),
        document.getElementById("mask4")
    ];

    function toggleFields() {
        if (check.checked) {
            cidr.classList.remove("hidden");
            maskInputs.forEach(input => input.classList.add("hidden"));
        } else {
            cidr.classList.add("hidden");
            maskInputs.forEach(input => input.classList.remove("hidden"));
        }
    }

    // Ejecutar la función al cargar la página
    toggleFields();

    // Agregar el evento de clic
    check.addEventListener("click", toggleFields);
});
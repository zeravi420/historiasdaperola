document.addEventListener("DOMContentLoaded", function () { 
 
    const menuToggle = document.getElementById("menuToggle"); 
    const mainNav = document.getElementById("mainNav"); 
 
    if (menuToggle && mainNav) { 
 
        // Abre e fecha pelo botão dos três risquinhos
        menuToggle.addEventListener("click", function (event) { 
            event.stopPropagation();

            mainNav.classList.toggle("open"); 
            menuToggle.classList.toggle("active"); 
        }); 


        // Impede que clicar dentro do menu faça ele fechar
        mainNav.addEventListener("click", function (event) {
            event.stopPropagation();
        });


        // Fecha quando clicar fora do menu
        document.addEventListener("click", function () {
            mainNav.classList.remove("open");
            menuToggle.classList.remove("active");
        });

    } 
 
});
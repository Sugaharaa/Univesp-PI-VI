
<style>
.popup {
    display: none;
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    opacity: 1;
    transition: opacity 0.5s ease-out;
}
</style>
<div id="popup" class="popup">Salvo!</div>


<script>
function showPopup() {
    var popup = document.getElementById("popup");
    popup.style.display = "block";
    
    setTimeout(function() {
        popup.style.opacity = "0";
        setTimeout(() => {
            popup.style.display = "none";
            popup.style.opacity = "1";  // Reseta a opacidade para usos futuros
        }, 500);
    }, 2000);
}

// Exemplo de acionamento do popup
document.addEventListener("DOMContentLoaded", function() {
    showPopup();  // Chama a função ao carregar a página
});

</script>
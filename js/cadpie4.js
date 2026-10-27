function validarFormulario() {
    const isChecked = document.getElementById("termo").checked;
    const senhaValida = verificasenha();

    if (!isChecked) {
        alert("É necessário aceitar os termos para cadastrar!");
        return false;
    }

    if (!senhaValida) {
        alert("As senhas não conferem!");
        return false;
    }
    return true;
}

function verificasenha() {
    let senha1 = document.getElementById("senha1").value;
    let senha2 = document.getElementById("senha2").value;

    if (senha1 !== senha2) {
        document.getElementById("verificasenha").innerText = "Senhas não conferem";
        return false;
    } else {
        document.getElementById("verificasenha").innerText = "";
        return true;
    }
}

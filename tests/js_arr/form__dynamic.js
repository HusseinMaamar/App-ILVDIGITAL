/* login form script js */
document.addEventListener('DOMContentLoaded', function () {
    setActiveTab('login');
});

function validateformAndLoader() {
    showLoader() 
    validateForm() 
}

function changeTab(tabName) {
    setActiveTab(tabName);
}

function setActiveTab(tabName) {
    var tabs = document.querySelectorAll('.tab');
    tabs.forEach(function (tab) {
        tab.classList.remove('active');
    });

    document.querySelector('.' + tabName).classList.add('active');
}

function showLoader() {
    document.getElementById("loaderlogin").style.display = "block"; // Afficher le loader
    setTimeout(function() {
        document.getElementById("loaderlogin").style.display = "none"; // Masquer le loader après 3 secondes
    }, 3000); // Délai de 3 secondes
}

function validateForm() {
    var email = document.getElementById('email').value;
    var inputEmail = document.getElementById('email');
    var password = document.getElementById('mdp').value;
    var inputPwd = document.getElementById('mdp');
    var msg = document.getElementById('messagejs');
    var message = "";

    if (email === "" && password === "") {
        message = "Saisissez votre E-mail et votre mot de passe.";
        inputEmail.style.border='2px solid #721c24'
        inputPwd .style.border='2px solid #721c24'
    } else if (email === "") {
        message = "Saisissez votre E-mail.";
        inputEmail.style.border='2px solid #721c24'
    } else if (password === "") {
        message = "Saisissez votre mot de passe.";
        inputPwd .style.border='2px solid #721c24'
    }

    if (message !== "") {
        msg.style.display ='block';
        msg.innerHTML = message ;
    } else {
        // Soumettre le formulaire si tout est valide
        document.getElementById('loginForm').submit();
    }
}
/* end login form script */
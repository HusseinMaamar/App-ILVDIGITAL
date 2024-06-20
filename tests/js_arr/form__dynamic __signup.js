/* signup form script js */
document.addEventListener('DOMContentLoaded', function () {
    setActiveTab('signup');
});

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


function submitForm() {
    // Afficher le loader
    document.getElementById("loadersignup").style.display = "block";

    // Envoyer les données du formulaire en utilisant AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "", true); // Assurez-vous que l'URL est correcte
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
    
        if (xhr.readyState === 4  && xhr.status === 200) {
            setTimeout(function() {
                // Masquer le loader
                document.getElementById("loadersignup").style.display = "none";
            }, 4000); // Délai de 4 secondes
        }
    };
    
    // Vous devez adapter cette ligne selon les champs de votre formulaire
    var formData = "email=" + encodeURIComponent(document.getElementById("email").value) +
                    "&mdp=" + encodeURIComponent(document.getElementById("mdp").value);
            
    xhr.send(formData);
}
/* end signup form script */
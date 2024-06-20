/* login form script js */
document.addEventListener('DOMContentLoaded', function () {
    /*   showForm('login');  */// Affiche le formulaire de login par défaut
      setActiveTab('login');
  });
  
  function changeTab(tabName) {
    /*   showForm(tabName); */
      setActiveTab(tabName);
  }
  
  /* function showForm(formName) {
      var forms = document.querySelectorAll('.form');
      forms.forEach(function (form) {
          form.style.display = 'none';
      });
  
      document.getElementById(formName + 'Form').style.display = 'flex';
  }
   */
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
         // Afficher l'alerte
      }, 3000); // Délai de 3 secondes
  }
  /* end login form script */
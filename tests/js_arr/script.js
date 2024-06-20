function openTab(tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
  }
  document.getElementById(tabName).style.display = "block";

  // Stocker l'onglet actif dans un cookie
  document.cookie = "activeTab=" + tabName + "; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
}

document.addEventListener("DOMContentLoaded", function () {
  // Fonction pour récupérer la valeur d'un cookie par son nom
  function getCookie(name) {
      var match = document.cookie.match(new RegExp(name + '=([^;]+)'));
      return match ? match[1] : null;
  }

  // Récupérer l'onglet actif depuis le cookie
  var activeTab = getCookie("activeTab");

  // Afficher l'onglet actif s'il existe
  if (activeTab) {
      openTab(activeTab);
  }
});

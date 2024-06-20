document.addEventListener('DOMContentLoaded', function () {
    
    const storedActiveTab = localStorage.getItem('activeTab');

    if (storedActiveTab) {
        activateTab(storedActiveTab);
    } else {
        const activeTab = getActiveTab();
        if (activeTab) {
            activateTab(activeTab);
        }
    }

    
    const tabEl = document.querySelector('.nav-tabs');
    if(tabEl != null){
    tabEl.addEventListener('show.bs.tab', function (event) {
        // Stockez l'onglet actif dans le localStorage
        const activeTab = event.target.id;
        localStorage.setItem('activeTab', activeTab);
     });
    }
    });

    function getActiveTab() {
    const activeTabElement = document.querySelector('.nav-link.active');
    return activeTabElement ? activeTabElement.id : null;
    }

    function activateTab(tabId) {
    const tabElement = document.querySelector(`#${tabId}`);
    if (tabElement) {
        const bsTab = new bootstrap.Tab(tabElement);
        bsTab.show();
     }
     }

        function showUpdateForm(plaqueId) {
            // Cacher tous les formulaires de mise à jour
            document.querySelectorAll('[id^="updateForm"]').forEach(form => form.style.display = 'none');
            // Afficher le formulaire de mise à jour spécifique
            document.getElementById('updateForm' + plaqueId).style.display = 'block';
    }

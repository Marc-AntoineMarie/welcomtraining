document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('table-body');
    const suggestions = document.getElementById('suggestions');
    const paginationContainer = document.getElementById('pagination');

    const filterEtat = document.getElementById('filter-etat');
    const filterClasse = document.getElementById('filter-classe');
    const searchInput = document.getElementById('search-name');

    let currentPage = 1;

    // Fonction pour récupérer et afficher les données
    function fetchData(page = 1) {
        const etat = filterEtat.value;
        const classe = filterClasse.value;
        const search = searchInput.value;

        fetch(`fetch_users.php?page=${page}&etat=${etat}&classe=${classe}&search=${search}`)
            .then(response => response.json())
            .then(data => {
                // Mettre à jour le tableau
                tableBody.innerHTML = '';
                data.users.forEach(user => {
                    tableBody.innerHTML += `
                        <tr>
                            <td>${user.iduser}</td>
                            <td>${user.Identifiant}</td>
                            <td>${user.Mail}</td>
                            <td>${user.Etat}</td>
                            <td>${user.NomClasse}</td>
                            <td>
                                <!-- Actions -->
                                <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.iduser})">Supprimer</button>
                            </td>
                        </tr>
                    `;
                });

                // Mettre à jour la pagination
                renderPagination(data.total_pages, page);
            });
    }

    // Fonction pour afficher la pagination
    function renderPagination(totalPages, currentPage) {
        paginationContainer.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            paginationContainer.innerHTML += `
                <button class="btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-secondary'}"
                    onclick="changePage(${i})">${i}</button>
            `;
        }
    }

    // Changer de page
    window.changePage = (page) => {
        currentPage = page;
        fetchData(page);
    };

    // Suppression utilisateur (exemple simple)
    window.deleteUser = (id) => {
        if (confirm('Supprimer cet utilisateur ?')) {
            fetch(`delete_user.php?id=${id}`, { method: 'POST' })
                .then(() => fetchData(currentPage));
        }
    };

    // Recherche dynamique des suggestions
    searchInput.addEventListener('input', () => {
        const query = searchInput.value;
        if (query.length > 1) {
            fetch(`search_suggestions.php?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    suggestions.innerHTML = '';
                    data.forEach(name => {
                        const suggestionItem = document.createElement('a');
                        suggestionItem.href = '#';
                        suggestionItem.classList.add('list-group-item', 'list-group-item-action');
                        suggestionItem.textContent = name;
                        suggestionItem.onclick = () => {
                            searchInput.value = name;
                            suggestions.innerHTML = '';
                            fetchData(1);
                        };
                        suggestions.appendChild(suggestionItem);
                    });
                });
        } else {
            suggestions.innerHTML = '';
        }
    });

    // Recharger les données quand un filtre change
    [filterEtat, filterClasse].forEach(filter => {
        filter.addEventListener('change', () => fetchData(1));
    });

    // Charger les données initiales
    fetchData();
});

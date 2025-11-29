const container = document.getElementById('ingredients-container');
const resultsList = document.getElementById("results-list");
const selectedList = document.getElementById('selected-ingredients');
let searchBarVisible = false;

// Crée la barre de recherche
function createSearchBar() {
    const divSearch = document.createElement("div");
    divSearch.classList.add("input-group", "mb-2");

    const inputSearch = document.createElement("input");
    inputSearch.type = "text";
    inputSearch.name = "query";
    inputSearch.classList.add("form-control");
    inputSearch.placeholder = "Ex: chocolat, pomme, ...";
    inputSearch.autocomplete = "off";

    divSearch.appendChild(inputSearch);
    container.appendChild(divSearch);

    searchBarVisible = true;

    // Événement Enter pour lancer la recherche
    inputSearch.addEventListener("keydown", async (event) => {
        if (event.key === "Enter") {
            event.preventDefault();
            const query = inputSearch.value.trim();
            if (!query) return;

            const response = await fetch(`/admin/ingredients/search?q=${encodeURIComponent(query)}`);
            const results = await response.json();

            displayResults(results, inputSearch);
        }
    });
}

// Affiche les résultats sous la barre de recherche
function displayResults(results, inputSearch) {
    resultsList.innerHTML = '';

    if (results.length === 0) {
        resultsList.innerHTML = '<li class="list-group-item text-muted">Aucun ingrédient trouvé</li>';
        return;
    }

    results.forEach(ingredient => {
        const li = document.createElement('li');
        li.classList.add('list-group-item', 'list-group-item-action', 'd-flex', 'justify-content-between', 'align-items-center');
        li.textContent = ingredient.name;

        // Clic pour ajouter l'ingrédient sélectionné
        li.addEventListener('click', () => {
            addSelectedIngredient(ingredient);
            resultsList.innerHTML = ''; // vide les résultats
            inputSearch.value = ''; // vide la recherche
        });

        resultsList.appendChild(li);
    });
}

// Ajoute un ingrédient sélectionné avec quantité et unité
function addSelectedIngredient(ingredient) {
    // Vérifie si déjà ajouté
    if (document.querySelector(`#selected-ingredients li[data-id='${ingredient.id}']`)) return;

    const li = document.createElement('li');
    li.classList.add('list-group-item', 'd-flex', 'gap-2', 'align-items-center', "mb-2");
    li.dataset.id = ingredient.id;

    // Nom de l'ingrédient
    const nameInput = document.createElement('input');
    nameInput.type = 'text';
    nameInput.name = 'ingredients_names[]';
    nameInput.value = ingredient.name;
    nameInput.readOnly = true;
    nameInput.classList.add('form-control');

    // Quantité
    const quantityInput = document.createElement('input');
    quantityInput.type = 'text';
    quantityInput.name = 'quantites[]';
    quantityInput.placeholder = 'Quantité';
    quantityInput.classList.add('form-control');

    // Unité
    const unitInput = document.createElement('input');
    unitInput.type = 'text';
    unitInput.name = 'unit[]';
    unitInput.placeholder = 'Unité';
    unitInput.classList.add('form-control');

    // Hidden pour l'ID
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'ingredients[]';
    hiddenInput.value = ingredient.id;

    // Bouton supprimer
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.textContent = '❌';
    removeBtn.classList.add('btn', 'btn-secondary');
    removeBtn.addEventListener('click', () => li.remove());

    // Ajout des éléments au <li>
    li.appendChild(nameInput);
    li.appendChild(quantityInput);
    li.appendChild(unitInput);
    li.appendChild(hiddenInput);
    li.appendChild(removeBtn);

    selectedList.appendChild(li);
}

// Fonction principale appelée par le bouton "+ Ajouter vos ingrédients"
function addIngredient() {
    if (!searchBarVisible) {
        createSearchBar();
        
    }
}

// Initialisation du bouton
const btnAddIngredient = document.getElementById("btnAddIngredient");
btnAddIngredient.addEventListener("click", addIngredient);
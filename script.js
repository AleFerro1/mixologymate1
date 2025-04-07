const searchInput = document.getElementById('search-input');
const suggestionsContainer = document.getElementById('suggestions-container');
const selectedIngredients = document.getElementById('selected-ingredients');
const searchButton = document.getElementById('search-button');
let ingredientsList = [];

    // Mostra i suggerimenti haskjfhaskhowsahjo
    function showSuggestions(suggestions) {
        suggestionsContainer.innerHTML = '';
        suggestions.forEach(suggestion => {// per ogni ingrediente fa sto ciclo
            const div = document.createElement('div');
            div.className = 'suggestion-item';
            div.textContent = suggestion;
            div.onclick = () => selectIngredient(suggestion);
            suggestionsContainer.appendChild(div);//questo per mette il nuovo div sotto 
        });
        suggestionsContainer.style.display = suggestions.length ? 'block' : 'none'; 
    }

    // Funzione per selezionare un ingrediente
    function selectIngredient(ingredient) {
        if (!ingredientsList.includes(ingredient)) {
            ingredientsList.push(ingredient); //aggiunge alle selezioni questo ingrediente 
            updateSelectedIngredients();
        }
        searchInput.value = '';
        suggestionsContainer.style.display = 'none';
    }

    // Aggiorna la lista degli ingredienti selezionati e mette sotto il cosetto per indica l'ingrediente
    function updateSelectedIngredients() {
        selectedIngredients.innerHTML = '';//questo è per evitare che ingredienti vengano ripetuti
        ingredientsList.forEach(ingredient => {
            const span = document.createElement('span');
            span.className = 'selected-ingredient';
            span.textContent = ingredient;
            span.onclick = () => removeIngredient(ingredient);
            selectedIngredients.appendChild(span);//aggiunge lo span 
        });
    }

    // Rimuovi ingrediente
    function removeIngredient(ingrediente) {
        ingredientsList = ingredientsList.filter(item => item !== ingrediente);//questo mette tutto tranne che l'ingrediente
        updateSelectedIngredients();
    }

    // Evento input sulla barra di ricerca
    /*In pratica prende ogni input che si fa sulla barra di ricerca e esegue la funzione
    e controlla se è lungo abbastanza da inizia la ricerca nel database
    usiamo fetch visto che non posso usa get visto che javascript è un tumore*/
    searchInput.addEventListener('input', function(e) {
        const parola = e.target.value.trim();
        if (parola.length > 2) {
            fetch(`MixologyMate-main/fetch_ingredienti.php?parola=${parola}`)// questo è tipo un get ma più tumorale
                .then(response => response.json())//questo prende il json che manda fetch_ingredienti
                .then(data => showSuggestions(data));//questo manda i dati alla funzione per i suggerimenti
        } else {
            suggestionsContainer.style.display = 'none';
        }
    });

    // Evento click sul bottone cerca
    searchButton.addEventListener('click', function() {
        if (ingredientsList.length > 0) {
            const ingredientsParam = JSON.stringify(ingredientsList); //prende la lista de ingredienti e la fa diventa una stringa
            window.location.href = `MixologyMate-main/risultati.php?ingredients=${ingredientsParam}`;//javascript è una merda
        } else {
            alert('Seleziona almeno un ingrediente!');
        }
    });

    // Chiudi suggerimenti quando si clicca fuori
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target)) {//in pratica se clicchi in un punto che non è la barra de ricerca ti chiude i suggerimenti
            suggestionsContainer.style.display = 'none';
        }
    });

//Funzione per i menu a tendina sulla barra sopra
function toggleDropdown(id) {
    document.getElementById(id).classList.toggle("show");
}

// Chiudi i dropdown se si clicca fuori
window.onclick = function(event) {
    if (!event.target.matches('.menu-button')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

// Funzioni per le recensionisafdjhdghjksaaaaaamannaggia

function checkScroll() {
    const scroller = document.getElementById('reviewsScroller');
    const leftArrow = document.querySelector('.scroll-left');
    const rightArrow = document.querySelector('.scroll-right');
    
    leftArrow.classList.toggle('hidden', scroller.scrollLeft <= 10);
    const maxScroll = scroller.scrollWidth - scroller.clientWidth;
    rightArrow.classList.toggle('hidden', scroller.scrollLeft >= maxScroll - 10);
}

function scrollReviews(scrollAmount) {
    const scroller = document.getElementById('reviewsScroller');
    scroller.scrollBy({ left: scrollAmount, behavior: 'smooth' });
}

// Funzioni per i drink
function checkDrinksScroll() {
    const scroller = document.getElementById('drinksScroller');
    const leftArrow = document.querySelector('.scroll-left-drinks');
    const rightArrow = document.querySelector('.scroll-right-drinks');
    
    leftArrow.classList.toggle('hidden', scroller.scrollLeft <= 10);
    const maxScroll = scroller.scrollWidth - scroller.clientWidth;
    rightArrow.classList.toggle('hidden', scroller.scrollLeft >= maxScroll - 10);
}

function scrollDrinks(scrollAmount) {
    const scroller = document.getElementById('drinksScroller');
    scroller.scrollBy({ left: scrollAmount, behavior: 'smooth' });
}

// Inizializzazione eventi
window.addEventListener('DOMContentLoaded', () => {
    checkScroll();
    checkDrinksScroll();
});

window.addEventListener('resize', () => {
    checkScroll();
    checkDrinksScroll();
});
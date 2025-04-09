errors = 0;

let cardList = [];

// Build a card object for each foreign and native word
wordPairs.forEach((pair, index) => {
  cardList.push({
    id: index,
    type: "foreign",
    word: pair.ForeignWord,
  });

  cardList.push({
    id: index,
    type: "native",
    word: pair.NativeWord,
  });
});

cardSet = undefined;
board = [];
rows = 4;
columns = 5;

card1Selected = undefined;
card2Selected = undefined;

window.onload = function () {
  shuffleCards(cardList);
  startGame();

  restartButton = document.getElementById("button");
  restartButton.addEventListener("click", restartGame);

  cardsContainerDiv = document.getElementById("CardsContainer");
};

function shuffleCards(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
}

function startGame() {
  shuffleCards(cardList);

  cardList.forEach((cardObj, i) => {
    const card = document.createElement("div");
    card.classList.add("card");
    card.dataset.id = cardObj.id;
    card.dataset.word = cardObj.word;

    // Set the back side of the card to the image
    card.style.backgroundImage = "url('img/back.jpg')";
    card.style.backgroundSize = "cover"; // Cover the entire card
    card.style.backgroundPosition = "center"; // Center the image

    // Set the initial content to be empty
    card.innerText = ""; // No text initially

    // Add event listener to reveal the word
    card.addEventListener("click", selectCard);
    document.getElementById("board").appendChild(card);
  });
}

function hideCards() {
  for (r = 0; r < rows; r++) {
    for (c = 0; c < columns; c++) {
      card = document.getElementById(r.toString() + "-" + c.toString());
      card.innerText = "Back";
    }
  }
}

function selectCard() {
  if (this.innerText !== "") return; // Carta già rivelata

  // Rimuovi l'immagine di sfondo quando la carta viene cliccata
  this.style.backgroundImage = "none"; // Rimuovi l'immagine di sfondo
  this.innerText = this.dataset.word; // Rivelare la parola

  // Gestisci la selezione delle carte
  if (!card1Selected) {
    card1Selected = this;
  } else if (!card2Selected && this !== card1Selected) {
    card2Selected = this;
    setTimeout(update, 500);
  }
}

function update() {
  if (card1Selected.dataset.id !== card2Selected.dataset.id) {
    // Not a match
    card1Selected.style.backgroundImage = "url('img/back.jpg')"; // Ripristina l'immagine di sfondo
    card2Selected.style.backgroundImage = "url('img/back.jpg')"; // Ripristina l'immagine di sfondo
    card1Selected.innerText = ""; // Nascondi il testo
    card2Selected.innerText = ""; // Nascondi il testo

    errors++;
    document.getElementById("errors").innerText = errors;
  } else {
    // It's a match!
    card1Selected.removeEventListener("click", selectCard);
    card2Selected.removeEventListener("click", selectCard);
  }

  card1Selected = null;
  card2Selected = null;
}

function restartGame() {
  window.location.href = "/PlayMemory?corso=" + corso;
}

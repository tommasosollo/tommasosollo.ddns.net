errors = 0;
cardList = [
  "img/darkness",
  "img/double",
  "img/fairy",
  "img/fighting",
  "img/fire",
  "img/grass",
  "img/lightning",
  "img/metal",
  "img/psychic",
  "img/water",
];

cardSet = undefined;
board = [];
rows = 4;
columns = 5;

card1Selected = undefined;
card2Selected = undefined;

window.onload = function () {
  shuffleCards();
  startGame();

  restartButton = document.getElementById("button");
  restartButton.addEventListener("click", restartGame);

  cardsContainerDiv = document.getElementById("container");
};

function shuffleCards() {
  cardSet = cardList.concat(cardList); //two of each card
  //shuffle cards
  for (i = 0; i < cardSet.length; i++) {
    j = Math.floor(Math.random() * cardSet.length);
    //swap
    temp = cardSet[i];
    cardSet[i] = cardSet[j];
    cardSet[j] = temp;
  }
}

function startGame() {
  //arrange board
  for (r = 0; r < rows; r++) {
    row = [];
    for (c = 0; c < columns; c++) {
      cardImg = cardSet.pop();
      row.push(cardImg);

      card = document.createElement("img");
      card.id = r.toString() + "-" + c.toString();
      card.src = cardImg + ".jpg";
      card.classList.add("card");
      card.addEventListener("click", selectCard);
      document.getElementById("board").appendChild(card);
    }
    board.push(row);
  }
  setTimeout(hideCards, 1000);
}

function hideCards() {
  for (r = 0; r < rows; r++) {
    for (c = 0; c < columns; c++) {
      card = document.getElementById(r.toString() + "-" + c.toString());
      card.src = "img/back.jpg";
    }
  }
}

function selectCard() {
  if (this.src.includes("back")) {
    if (!card1Selected) {
      card1Selected = this;

      cords = card1Selected.id.split("-");
      r = parseInt(cords[0]);
      c = parseInt(cords[1]);

      card1Selected.src = board[r][c] + ".jpg";
    } else if (!card2Selected && this != card1Selected) {
      card2Selected = this;

      cords = card2Selected.id.split("-");
      r = parseInt(cords[0]);
      c = parseInt(cords[1]);

      card2Selected.src = board[r][c] + ".jpg";
      setTimeout(update, 1000);
    }
  }
}

function update() {
  //if not the same flip back
  if (card1Selected.src != card2Selected.src) {
    card1Selected.src = "img/back.jpg";
    card2Selected.src = "img/back.jpg";
    errors++;
    document.getElementById("errors").innerText = errors;
  } else if (card1Selected.src == card2Selected.src) {
    card1Selected.removeEventListener("click", selectCard);
    card2Selected.removeEventListener("click", selectCard);

    cardsContainerDiv.style = "display: none;";
  }

  card1Selected = null;
  card2Selected = null;
}

function restartGame() {
  window.location.href = "/PlayMemory";
}

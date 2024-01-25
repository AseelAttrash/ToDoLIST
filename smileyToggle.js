function toggleSmiley(checkbox) {
  const smiley = checkbox.nextElementSibling;
  const taskRow = checkbox.closest('tr');
  const cells = Array.from(taskRow.getElementsByTagName('td'));

  if (checkbox.checked) {
    smiley.src = "IMG/smile.png";
    smiley.alt = "Happy smiley";

    // Add strikethrough effect to each cell except the last one
    for (let i = 0; i < cells.length - 1; i++) {
      cells[i].classList.add('strikethrough');
    }
  } else {
    smiley.src = "IMG/sad.png";
    smiley.alt = "Sad smiley";

    // Remove strikethrough effect from all cells
    cells.forEach(cell => cell.classList.remove('strikethrough'));
  }
}

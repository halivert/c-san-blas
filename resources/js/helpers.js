function addClass(element, nameClass) {
  if (!element) return;

  var arr = element.className.split(" ");
  if (arr.indexOf(nameClass) == -1) {
    arr.push(nameClass);
  }
  element.className = arr.join(" ");
}

function removeClass(element, nameClass) {
  if (!element) return;

  var classes = element.className.split(" ");
  var i = classes.indexOf(nameClass);

  if (i >= 0) {
    classes.splice(i, 1);
    element.className = classes.join(" ");
  }
}

function toggleClass(element, nameClass) {
  if (!element) return;

  element.classList.toggle(nameClass);
}

const getNumberOfHeader = header => {
  return header.split(" ")[1];
};

export { addClass, removeClass, toggleClass, getNumberOfHeader };

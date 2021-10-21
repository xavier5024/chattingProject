
function btnSpinner (btnObj, status, message, style) {
  btnObj.value = message
  btnObj.disabled = status
  btnObj.style = style
}

export {
  btnSpinner
};
  
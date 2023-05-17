
let checkbox = document.getElementById("deadlineCheckbox")

let deadline = document.getElementById("deadline")

function checkboxFunction() {
    if (checkbox.checked) {
        deadline.classList.remove("d-none")
        deadline.classList.add("d-block")
    }
    else{
        deadline.classList.remove("d-block")
        deadline.classList.add("d-none")
    }
}
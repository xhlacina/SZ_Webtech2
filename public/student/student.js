const btn = document.getElementById('edit');


function edit(button){
	type=button.parentElement.parentElement.childNodes[0].textContent
	number=button.parentElement.parentElement.childNodes[1].textContent
	window.location = 'assignment.php?type=' + type + '&number=' + number;
};
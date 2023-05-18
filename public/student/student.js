const btn = document.getElementById('edit');


function edit(button){
	type=button.parentElement.parentElement.childNodes[0].textContent
	number=button.parentElement.parentElement.childNodes[1].textContent
	console.log(number)
	var currentURL = window.location.href;

	// Create a URLSearchParams object from the current URL
	var searchParams = new URLSearchParams(currentURL);
  
	// Get the value of a specific parameter
	var langValue = searchParams.get('lang');
	window.location = "assignment.php?type=" + type + '&number=' + number +'&lang=' + langValue;
};
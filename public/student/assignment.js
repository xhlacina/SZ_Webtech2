const mf = document.getElementById('formula');
const btn = document.getElementById('submitFormula');
const gf = document.getElementById('givenFormula');

btn.addEventListener('click', (ev) => {
  	const value = mf.getValue('latex');
	console.log(value);
	gf.value = value;
});
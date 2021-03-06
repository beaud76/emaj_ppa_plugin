function checkSelect(action, form_id) {

	var inputs = document.getElementById(form_id).getElementsByTagName('input');

	for (var i=0; i<inputs.length; i++) {
		if (inputs[i].type == 'checkbox') {
			if (action == 'all') inputs[i].checked = true;
			if (action == 'none') inputs[i].checked = false;
			if (action == 'invert') inputs[i].checked = !(inputs[i].checked);
		}
	}
}
function countChecked(form_id) {

	// count checked
	var inputs = document.getElementById(form_id).getElementsByTagName('input');
	var cnt = 0;
	for (var i=0; i<inputs.length; i++) {
		if (inputs[i].type == 'checkbox' && inputs[i].checked) {
			cnt++;
		}
	}
	// insert the counter into the selectcounter th of the form
	var textTitle = document.getElementById(form_id).getElementsByTagName('th');
	for (var i=0; i<textTitle.length; i++) {
		if (textTitle[i].id == 'selectedcounter') {
			textTitle[i].innerHTML = textTitle[i].innerHTML.replace(/\(\d+\)/,"("+cnt+")");
		}
	}
	// if the counter is 0, disable all buttons of the multiactions table
	var buttons = document.getElementById(form_id).getElementsByTagName('button');
	for (var i=0; i<buttons.length; i++) {
		buttons[i].disabled = (cnt == 0);
	}
}

//console.log("Töötab");

let fileSizeLimit = 1024 * 1024; //deklareerin

window.onload = function(){
	document.querySelector("#photo_submit").disabled = true;
	document.querySelector("#photo_input").addEventListener("change", checkSize); //siin funktsioonil sulge pole
}

function checkSize(){
	if(document.querySelector("#photo_input").files[0].size <= fileSizeLimit){ //massiiv files
		document.querySelector("#photo_submit").disabled = false;
		document.querySelector("#notice").innerHTML = "";
	} else {
		document.querySelector("#photo_submit").disabled = true;
		document.querySelector("#notice").innerHTML = "Valitud fail on <strong>liiga suure</strong> mahuga!";
	}
}
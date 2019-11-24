'use strict'

//// Seach appearance
let searchForm = document.getElementById("search_form")
searchForm.addEventListener("focusin", openFilters)
searchForm.addEventListener("focusout", closeFilters)


function openFilters(){
	document.getElementById("filters").style.display = "grid"
}

function closeFilters(event){
	if (searchForm.contains(event.relatedTarget))
		return;
	document.getElementById("filters").style.display = "none"
}
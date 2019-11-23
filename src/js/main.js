//// Seach appearance
let searchForm = document.getElementById("search_form")
searchForm.addEventListener("focusin", openFilters)

function openFilters(){
	document.getElementById("filters").style.display = "block"
}

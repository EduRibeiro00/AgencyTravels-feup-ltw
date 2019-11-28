//Sticky sideBar_Fast reservation

window.onload=function(){
	let img_elem=document.getElementById('carousel_container')
	
	let someElement = document.getElementById('Pop_UP_Fast_Reservation')
	const value= img_elem.offsetHeight+navbar.offsetHeight-window.screenY
	console.log(img_elem.clientHeight)
	console.log(navbar.clientHeight)
	console.log(value)

	window.addEventListener('scroll', function(){
	
		if(window.pageYOffset >= (value-100)){
			console.log(window.pageYOffset)
			someElement.style.top="4em"
		}
		else{
			console.log(window.pageYOffset)
			someElement.style.top="0"
		}
			
	})
}
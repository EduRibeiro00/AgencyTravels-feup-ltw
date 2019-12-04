//Sticky sideBar_Fast reservation

window.onload=function(){
	let img_elem=document.getElementById('carousel_container')
	
	let someElement = document.getElementById('Pop_UP_Fast_Reservation')
	const value= img_elem.offsetHeight+navbar.offsetHeight-window.screenY
	let bottom_element=document.getElementById('reviews')
	const value_bottom =bottom_element.offsetTop
	
	
	
	window.addEventListener('scroll', function(){
	
		if(window.pageYOffset >= value-10&&window.pageYOffset<=value_bottom){
			someElement.style.top="4em"
			someElement.style.position="sticky"
		}
		else if(window.pageYOffset>value_bottom){
			someElement.style.top="-4em"
			
		}
		else{
			someElement.style.top="value_bottom"
			someElement.style.position="static"
		}
			
	})
}
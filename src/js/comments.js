'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------
// Resets display of review form section
window.addEventListener('load', function() {
    let reviewAddSection = document.getElementById('add-review-section');
    if(reviewAddSection != null)
        reviewAddSection.style.display = "block";
})

// Review submitting
let reviewForm = document.querySelector('form#review-form');
if(reviewForm != null) {

    reviewForm.addEventListener('submit', function(event) {
        event.preventDefault();

    	let request = new XMLHttpRequest();
        request.open("POST", "../api/api_add_review.php", true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        let reviewPlaceholder = document.getElementById('add-review-placeholder').firstElementChild;

        request.addEventListener('load', function () {
        let reply = JSON.parse(this.responseText);
        let message = reply.message;
        switch(message) {
            case 'yes':
                let newReviews = reply.reviews;
                for(let i = 0; i < newReviews.length; i++) {
                    let newReview = newReviews[i];
                    let newReviewContainer = reviewPlaceholder.cloneNode(true);
                    newReviewContainer.setAttribute('data-reviewID', newReview.reviewID);
                        
                    let imageLink = newReviewContainer.querySelector('header a');
                    imageLink.setAttribute('href', '../pages/profile_page.php?userID=' + newReview.userID);

                    let image = newReviewContainer.querySelector('header a img');
                    image.setAttribute('src', '../assets/images/users/small/' + newReview.image);

                    let usernameP = newReviewContainer.querySelector('header p');
                    usernameP.innerHTML = newReview.username;

                    let starsDiv = newReviewContainer.querySelector('div.front-stars');
                    starsDiv.style.width = newReview.stars * 20.0 + '%';

                    let commentP = newReviewContainer.querySelector('header + p');
                    commentP.innerHTML = newReview.comment;

                    let dateP = newReviewContainer.querySelector('footer p');
                    dateP.innerHTML = "Published: " + newReview.date;
                        
                    document.querySelector('article#reviews').insertBefore(newReviewContainer, null);
                    document.getElementById('add-review-section').style.display = "none";
                }

                // updating the star rating on the page
                let starsSideRating = document.querySelector('#fr_card .front-stars');
                starsSideRating.style.width = reply.newRating * 20.0 + '%';

                let reviewsBeginningRating = document.querySelector('#reviews .front-stars');
                reviewsBeginningRating.style.width = reply.newRating * 20.0 + '%';
                break;

            case 'no':
                // console.log("Error on adding the new review");
                break;
                    
            default:
                console.log(message);
                break;
        }

        });

        let reservationID = document.querySelector('input[name="reservationID"]').value;
        let stars = document.querySelector('input[name="review-stars"]').value;
        let comment = document.querySelector('textarea[name="review-desc"]').value;
        let placeID = document.querySelector('input[name="placeID"]').value;
        let lastReviewID = document.querySelector('article#reviews article.review:last-of-type').getAttribute('data-reviewID');

        request.send(encodeForAjax({reservationID: reservationID, stars: stars, comment: comment, placeID: placeID, lastReviewID: lastReviewID}));
    });
}
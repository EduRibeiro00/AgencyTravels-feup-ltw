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
                        
                    let imageLink = newReviewContainer.querySelector('.user_card .user_img');
                    imageLink.setAttribute('href', '../pages/profile_page.php?userID=' + newReview.userID);

                    let image = newReviewContainer.querySelector('.user_card a img');
                    image.setAttribute('src', '../assets/images/users/small/' + newReview.image);

                    let usernameLink = newReviewContainer.querySelector('.user_card .user_username');
                    usernameLink.setAttribute('href', '../pages/profile_page.php?userID=' + newReview.userID);
                    usernameLink.innerHTML = newReview.username;

                    let starsDiv = newReviewContainer.querySelector('div.front-stars');
                    starsDiv.style.width = newReview.stars * 20.0 + '%';

                    let commentP = newReviewContainer.querySelector('header + p');
                    commentP.innerHTML = newReview.comment;

                    let dateP = newReviewContainer.querySelector('footer p');
                    dateP.innerHTML = "Published: " + newReview.date;
                        
                    document.querySelector('article#reviews').insertBefore(newReviewContainer, null);
                    document.getElementById('add-review-section').style.display = "none";

                    if(newReviewContainer.querySelector('.reply-form') != null) {
                        newReviewContainer.querySelector('.reply-form').addEventListener('submit', replyFormFunction);
                    }
                }

                // updating the star rating on the page
                let starsSideRating = document.querySelector('#fr_card .front-stars');
                starsSideRating.style.width = reply.newRating * 20.0 + '%';

                let reviewsBeginningRating = document.querySelector('#reviews .front-stars');
                reviewsBeginningRating.style.width = reply.newRating * 20.0 + '%';
                break;

            case 'no':
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
        let lastReviewID;
        if(document.querySelector('article#reviews article.review:last-of-type') != null) {
            lastReviewID = document.querySelector('article#reviews article.review:last-of-type').getAttribute('data-reviewID');
        }
        else {
            lastReviewID = -1;
        }

        request.send(encodeForAjax({reservationID: reservationID, stars: stars, comment: comment, placeID: placeID, lastReviewID: lastReviewID}));
    });
}

// ----------------------------
// Replies

let replyForms = document.querySelectorAll('.reply-form');
for(let i = 0; i < replyForms.length; i++) {
    let replyForm = replyForms[i];

    replyForm.addEventListener('submit', replyFormFunction);
}

function replyFormFunction(event) {

        let reviewArticle = event.target.parentElement.parentElement;

        event.preventDefault();
        let request = new XMLHttpRequest();
        request.open("POST", "../api/api_add_reply.php", true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        request.addEventListener('load', function () {
        let reply = JSON.parse(this.responseText);
        let message = reply.message;
        switch(message) {
            case 'yes':

                let newReplies = reply.replies;
                for(let i = 0; i < newReplies.length; i++) {
                    let newReply = newReplies[i];

                    let newReplyContainer = document.createElement('article');
                    newReplyContainer.classList.add('reply');
                    newReplyContainer.setAttribute('data-replyID', newReply.replyID);

                    newReplyContainer.innerHTML = '<header class="row">' +
                                                    '<a href="../pages/profile_page.php?userID=' + newReply.userID + '">' +
                                                        '<img class="reply-author-img circular-img" src="../assets/images/users/small/' + newReply.image + '">' + 
                                                    '</a>' + 
                                                    '<a href="../pages/profile_page.php?userID=' + newReply.userID + '">' +
                                                        '<p>' + newReply.username + '</p>' +
                                                    '</a>' +
                                                  '</header>' +
                                                  '<p>' + escapeHtml(newReply.comment) + '</p>' + 
                                                  '<footer>' +
                                                    '<p>' + 'Published: ' + newReply.date + '</p>' +
                                                  '</footer>';

                    reviewArticle.querySelector('.comment-replies').insertBefore(newReplyContainer, null);
                }

                break;

            case 'error':
                showDialog("An error occurred because some inputs are invalid. Please try again.")
                break;

            case 'not logged in':
                showDialog("Login in order to reply to a comment");
                break;

            default:
                console.log(message);
                break;
        }

        });

        let reviewID = reviewArticle.getAttribute('data-reviewID');
        let comment = event.target.querySelector('textarea[name="reply-desc"]').value;
        let lastReplyID;
        if(reviewArticle.querySelector('.comment-replies article.reply:last-of-type') != null) {
            lastReplyID = reviewArticle.querySelector('.comment-replies article.reply:last-of-type').getAttribute('data-replyID');
        }
        else {
            lastReplyID = -1;
        }

        request.send(encodeForAjax({comment: comment, reviewID: reviewID, lastReplyID: lastReplyID}));

        event.target.querySelector('textarea[name="reply-desc"]').value = "";
}

// -------------------

let entityMap = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': '&quot;',
    "'": '&#39;',
    "/": '&#x2F;'
  };

function escapeHtml(string) {
    return String(string).replace(/[&<>"'\/]/g, function (s) {
      return entityMap[s];
    });
}
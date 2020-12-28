import $ from 'jquery'

class Like{
    constructor(){
      this.events()
    }

    events(){
        $(".like-box").on("click", this.ourClickDispatcher.bind(this))
    }

    ourClickDispatcher(e){ // on click event above passes info into the argument here

        // finds closest like-box element when clicking inside it - e.g. if you click on the number or the icon, it will select the like box element
        let currentLikeBox = $(e.target).closest(".like-box")
        
        // better than currentLikeBox.data("exists") since this would only run once when page loads, so cannot be toggled within Jquery
        if(currentLikeBox.attr("data-exists") == "yes"){
            this.deleteLike(currentLikeBox)
        } else {
            this.createLike(currentLikeBox)
        }
    }
    // methods
    createLike(currentLikeBox){
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce); // gets the "nonce" value to ensure you have permissions to do this
            },
            url: universityData.root_url + '/wp-json/university/v1/manageLike', // the "university/v1" part is what we defined in inc/like-route.php
            type: 'POST',
            data: {'professorId': currentLikeBox.data('professor') }, // this bit basically modifies the above url by adding on '?professorID=123' where 123 is data=professor attribute in single-professor.php
            success: (response) => {
                currentLikeBox.attr('data-exists', 'yes');
                let likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10)  // convert string to integer in base 10
                likeCount++ // increment like counter by 1
                currentLikeBox.find(".like-count").html(likeCount)
                currentLikeBox.attr("data-like", response) // response is ID number of new post (which is automatically returned)
                console.log(response)},
            error: (response) => {console.log(response)}
        });
    }
    deleteLike(currentLikeBox){
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce); // gets the "nonce" value to ensure you have permissions to do this
            },
            url: universityData.root_url + '/wp-json/university/v1/manageLike', // the "university/v1" part is what we defined in inc/like-route.php
            data: {'like': currentLikeBox.attr('data-like') },
            type: 'DELETE',
            success: (response) => {
                currentLikeBox.attr('data-exists', 'no');
                let likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10)  // convert string to integer in base 10
                likeCount-- // increment like counter by 1
                currentLikeBox.find(".like-count").html(likeCount)
                currentLikeBox.attr("data-like", '') // response is ID number of new post (which is automatically returned)    
                console.log(response)},
            error: (response) => {console.log(response)}
        });
    }
}

export default Like
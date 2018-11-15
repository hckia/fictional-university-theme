import $ from 'jquery';

class Like {
    constructor(){
        this.events();
    }

    events() {
        $(".like-box").on("click", this.ourClickDispatcher.bind(this));
    }

    //methods
    ourClickDispatcher(e){
        // whatever element gets clicked on, find its closest ancestor (parent or child) and check for the like-box attribute.
        var currentLikeBox = $(e.target).closest(".like-box");
        // we could use currentLikeBox.data('exists'), but the data function only checks upon load. in this case, currentLikeBox.attr('data-exists') is better
        if(currentLikeBox.attr('data-exists') == 'yes'){
            this.deleteLike(currentLikeBox);
        }
        else {
            this.createLike(currentLikeBox);
        }
    }

    createLike(currentLikeBox){
        $.ajax({
            beforeSend: (xhr) => {
                // we created a nonce within functions.php for this purpose towards line 90
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/university/v1/manageLike',
            type: 'POST',
            // this is the same as adding to /wp-json/university/v1/manageLike ?professorId=789
            data: { 'professorId': currentLikeBox.data('professor')},
            success: (res) => {
                currentLikeBox.attr('data-exists', 'yes');
                var likeCount = parseInt(currentLikeBox.find(".like-count").html(),10);
                likeCount++;
                currentLikeBox.find(".like-count").html(likeCount);
                currentLikeBox.attr("data-like", res);
                console.log(res);
            },
            error: (res) => {
                console.log(res);
            }
        });
    }

    deleteLike(currentLikeBox){
        $.ajax({
            beforeSend: (xhr) => {
                // we created a nonce within functions.php for this purpose towards line 90
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/university/v1/manageLike',
            data: {'like': currentLikeBox.attr('data-like')},
            type: 'DELETE',
            success: (response) => {
                currentLikeBox.attr('data-exists', 'no');
                var likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10);
                likeCount--;
                currentLikeBox.find(".like-count").html(likeCount);
                currentLikeBox.attr("data-like", '');
                console.log(response);
            },
            error: (response) => {
                console.log(response);
            }
        });
    }
    
}

export default Like;
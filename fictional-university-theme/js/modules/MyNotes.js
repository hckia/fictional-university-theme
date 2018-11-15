import $ from 'jquery';

class MyNotes {
    constructor(){
        // parantheses executes this;
        this.events();
        /* diverting from course: creating state in case user hits cancel. Before if the user hit cancel, but made changes, 
        it would leave changes in place until refresh */
        this.state = {
            title: '',
            content: '',
            inUse: false,
            previousNote: ''
        }
    }

    events() {
        //when the delete button is clicked on my-notes
        /* 
            we target #my-notes first because it will always exists no matter what dynamic data is represented on the pages current state.
            Consider it an anchor, and then, as a middle argument between "click" and or function matches a selector (example .delete-note), then
            fire a function (example: fire this.deleteNote.bind(this))
        */
        $("#my-notes").on("click", ".delete-note",this.deleteNote.bind(this));
        //when the edit button is clicked on my-notes
        $("#my-notes").on("click", ".edit-note", this.editNote.bind(this));
        //when the save button is clicked on my-notes
        $("#my-notes").on("click", ".update-note", this.updateNote.bind(this));
        //when the submit button is clicked on my-notes
        $(".submit-note").on("click", this.createNote.bind(this));
    }

    //Methods will go here
    editNote(e) {
        var thisNote = $(e.target).parents('li');
        if (this.state.inUse && thisNote.find('.note-title-field').val() != this.state.title){
            this.makeNoteReadOnly(this.state.previousNote);
        }
        if(thisNote.find(".edit-note").hasClass('js-cancel')){
            //restore original state of title and content
            thisNote.find('.note-title-field').val(this.state.title);
            thisNote.find('.note-body-field').val(this.state.content);
            this.state = {
                title: '',
                content: '',
                inUse: false,
                previousNote: ''
            }
        }
        if(thisNote.data("state") == "editable"){
            // make read only
            this.makeNoteReadOnly(thisNote);
        } else {
            //make editable
            this.makeNoteEditable(thisNote);
        }
    }

    makeNoteEditable(thisNote){
        // save state
        this.state = {
            title: thisNote.find('.note-title-field').val(),
            content: thisNote.find('.note-body-field').val(),
            inUse: true,
            previousNote: thisNote
        };
        console.log("Title State: ", this.state.title)
        thisNote.find(".edit-note").addClass("js-cancel");
        //When edit is clicked, change the edit to 'cancel'
        thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i>Cancel');
        // remove the readonly attribute so title and textarea can be edited
        thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
        // make the save button visible.
        thisNote.find(".update-note").addClass("update-note--visible");
        thisNote.data("state", "editable");
    }

    makeNoteReadOnly(thisNote){
        thisNote.find(".edit-note").removeClass("js-cancel");
        this.state = {
            title: '',
            content: '',
            inUse: false,
            previousNote: ''
        }
        //When cancel/save is clicked, change the cancel to 'edit'
        thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i>Edit');
        // add the readonly attribute so title and textarea cant be edited
        thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
        // make the save button invisible.
        thisNote.find(".update-note").removeClass("update-note--visible");
        thisNote.data("state", "cancel");
    }

    deleteNote(e){
        var thisNote = $(e.target).parents('li');
        $.ajax({
            beforeSend: (xhr) => {
                // we created a nonce within functions.php for this purpose towards line 90
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),
            type: 'DELETE',
            success: (res) => {
                thisNote.slideUp();
                console.log("Congrats");
                console.log(res);
                if(res.userNoteCount < 4) {
                    $(".note-limit-message").removeClass("active");
                }
            },
            error: (res) => {
                console.log("sorry");
                console.log(res);
            }
        });
    }

    updateNote(e){
        var thisNote = $(e.target).parents('li');
        var ourUpdatedPost = {
            'title': thisNote.find('.note-title-field').val(),
            'content': thisNote.find('.note-body-field').val()
        }
        $.ajax({
            beforeSend: (xhr) => {
                // we created a nonce within functions.php for this purpose towards line 90
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),
            type: 'POST',
            data: ourUpdatedPost,
            success: (res) => {
                this.makeNoteReadOnly(thisNote);
                console.log("Congrats");
                console.log(res);
            },
            error: (res) => {
                console.log("sorry");
                console.log(res);
            }
        });
    }
    createNote(e){
        var ourNewPost = {
            'title': $(".new-note-title").val(),
            'content': $(".new-note-body").val(),
            /* status is to tell WordPress to publish our Post request, by default it will be set to 'draft' and we would have to manually go into our
               WordPress dashboard and publish our post request.
               However, this leaves the posts exposed via rest api. Anyone who sees the endpoint can gain access to private notes.
               so instead of 'publish' we place 'private'
               this is similar to going into wp-admin as administrator, clicking on a post > edit > and setting visibility to 'private'
               this is not enough though, as a malicious user could modify this to 'public' we need to go further...
            */
            'status': 'private'
        }
        $.ajax({
            beforeSend: (xhr) => {
                // we created a nonce within functions.php for this purpose towards line 90
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/wp/v2/note/',
            type: 'POST',
            data: ourNewPost,
            success: (res) => {
                $(".new-note-title, .new-note-body").val('');
                $(`
                <li data-id="${res.id}">
                <!-- the readonly attribute makes it so we cant simply click on and update our input or textarea -->
                    <input readonly class="note-title-field" value="${res.title.raw}"/>
                    <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i>Edit</span>
                    <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</span>
                    <textarea readonly class="note-body-field">${res.content.raw}</textarea>
                    <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i>Save</span>
                </li>
                `).prependTo("#my-notes").hide().slideDown();
                console.log("Congrats");
                console.log(res);
            },
            error: (res) => {
                if (res.responseText == "You have reached your note limit."){
                    $(".note-limit-message").addClass("active");
                }
                console.log("sorry");
                console.log(res);
            }
        });       
    }
}

export default MyNotes;
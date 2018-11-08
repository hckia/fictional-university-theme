import $ from 'jquery';

class Search {
  // 1. describe and create/initiate our object
  constructor() {
    this.addSearchHTML();
    this.resultsDiv = $("#search-overlay__results");
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.previousValue;
    this.searchField = $("#search-term");
    //this attribute will get its value in typingLogic
    this.typigTimer;
    this.events();
    /* 
    Boolean used to ensure overlay only fires when overlay is not open
    */
    this.isOverlayOpen = false;
    //for our loading spinner icon in typingLogic
    this.isSpinnerVisible = false;
  }

  // 2. events
  events() {
    this.openButton.on("click", this.openOverlay.bind(this));
    /*
    keyup fires when a key is released
    keydown fires when a key is pressed, BUT it will continue firing until the key has been released. keyPressDispatcher 
    function explains how to get around this
    */
    $(document).on("keydown", this.keyPressDispatcher.bind(this));
    this.closeButton.on("click", this.closeOverlay.bind(this));
    /* we could have used JQuery here, but that would force JavaScript to traverse the DOM over and over again. this is much more efficient.
     in order for our spinner to work we actually have to use keyup, because the keydown event value will not update fast enough for instnaces
     such as pushing the back arrow button.
    */
    this.searchField.on("keyup", this.typingLogic.bind(this));
  }
  

  // 3. methods (function, action...)
    typingLogic() {
        if (this.searchField.val() != this.previousValue){
            // our property can be based to clearTimeout so that the timer resets everytime there's a keystroke, instead of firing every key stroke.
            clearTimeout(this.typigTimer);
            if(this.searchField.val()){
                if (!this.isSpinnerVisible) {
                    // as soon as someone types they'll still get an immediate response with this.
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }
                // 1000 is 1 second
                //this.getResults() will allow us to find our getResults method. bind(this) will allow us to access our objects properties and methods
                this.typigTimer = setTimeout(this.getResults.bind(this), 750);
            } else {
                this.resultsDiv.html('');
                this.isSpinnerVisible = false;
            }
        }
        this.previousValue = this.searchField.val();
    }

    getResults(){
        $.getJSON(universityData.root_url + '/wp-json/university/v1/search?term=' + this.searchField.val(), (results) => {
            this.resultsDiv.html(`
                <div class="row">
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">General Information</h2>
                        ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No General information matches that search</p>'}
                        <!-- template literals do not allow us to add traditional control statements, but we can use ternary operators... -->
                        ${results.generalInfo.map(result => `<li><a href="${result.permalink}">${result.title}</a> ${result.postType == 'post' ? `by ${result.authorName}` : ''}</li>`).join('')}
                        ${results.generalInfo.length ? '</ul>' : ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Programs</h2>
                        ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No Program matches that search. <a href="${universityData.root_url}/programs">View all programs</a></p>`}
                        <!-- template literals do not allow us to add traditional control statements, but we can use ternary operators... -->
                        ${results.programs.map(result => `<li><a href="${result.permalink}">${result.title}</a></li>`).join('')}
                        ${results.programs.length ? '</ul>' : ''}
                        <h2 class="search-overlay__section-title">Professors</h2>
                        ${results.professors.length ? '<ul class="professor-cards">' : '<p>No Professor matches that search.</p>'}
                        <!-- template literals do not allow us to add traditional control statements, but we can use ternary operators... -->
                        ${results.professors.map(result => `
                            <li class="professor-card__list-item">
                            <a class="professor-card" href="${result.permalink}">
                            <img class="professor-card__image" src="${result.image}">
                            <span class="professor-card__name">${result.title}</span>
                            </a>
                            </li>
                        `).join('')}
                        ${results.professors.length ? '</ul>' : ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Campuses</h2>
                        ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No Campus matches that search.<a href="${universityData.root_url}/campuses"> View all Campuses</a></p>`}
                        <!-- template literals do not allow us to add traditional control statements, but we can use ternary operators... -->
                        ${results.campuses.map(result => `<li><a href="${result.permalink}">${result.title}</a></li>`).join('')}
                        ${results.campuses.length ? '</ul>' : ''}
                        <h2 class="search-overlay__section-title">Events</h2>
                        ${results.events.length ? '' : `<p>No Events matches that search. <a href="${universityData.root_url}/events">View all Events</a></p>`}
                        <!-- template literals do not allow us to add traditional control statements, but we can use ternary operators... -->
                        ${results.events.map(result => `
                            <div class="event-summary">
                                <a class="event-summary__date t-center" href="${result.permalink}">
                                <!-- 
                                    the_field is a function provided by the plugin Advanced Custom Fields ( ACF ). event-date is a custom field built within that 
                                    plugin. 
                                    However, that's only if we use it independently, and display the entire date format represented within our Custom Field. If we
                                    only want a portion of the Custom Fields data we can use the php DateTime class, and pass the ACF fucntion 'get_field' to return
                                    the string of data, and then echo out formatted text.
                                -->
                                <span class="event-summary__month">${result.month}</span>
                                <!-- Taking our work from above, and formatting the day. D for day name (Fri) d for day number (20th) -->
                                <span class="event-summary__day">${result.day}</span>  
                                </a>
                                <div class="event-summary__content">
                                <h5 class="event-summary__title headline headline--tiny"><a href="${result.permalink}">${result.title}</a></h5>
                                <!-- -->
                                <p>${result.description}<a href="${result.permalink}" class="nu gray">Learn more</a></p>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `);
        });
        //previous method marked in get-json-asynchronous-method.md

    }

    keyPressDispatcher(e) {
        // find the keyCode fired
        // console.log("key: ", e.keyCode);
        // we could set our second condition to this.isOverlayOpen == false, but !this.isOverlayOpen is the shorter syntax for the same thing.
        //this also prevents it from running repeatedly
        if (e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(":focus")) {
            this.openOverlay();
        }
        if (e.keyCode == 27 && this.isOverlayOpen) {
            this.closeOverlay();
        }
    }


  openOverlay() {
    this.searchOverlay.addClass("search-overlay--active");
    $("body").addClass("body-no-scroll");
    //clean input field so it doesn't show oldie
    this.searchField.val('');
    // focus, but wait a lil so focus succeeds
    setTimeout(() => this.searchField.focus(),301)
    // console.log("our open method just ran!");
    /* 
    Boolean used to ensure overlay only fires when overlay is not open
    */
    this.isOverlayOpen = true;
    // since our search icon is a link, we need to return false so the link is ignored IF JavaScript is running
    return false;
  }

  closeOverlay() {
    this.searchOverlay.removeClass("search-overlay--active");
      $("body").removeClass("body-no-scroll");
      //console.log("our close method just ran!");
      /* 
      Boolean used to ensure overlay only fires when overlay is not open
      */
      this.isOverlayOpen = false;
  }

  addSearchHTML(){
      $("body").append(`
        <!-- When a user selects the search icon the overlay below will appear.
        CSS class hook JS will use to make it appear is search-overlay--active 
        -->
        <div class="search-overlay">
            <div class="search-overlay__top">
                <div class="container">
                <!-- we use the i element for font awesome 
                aria-hidden <-- screen reader will not try to read this out to the visitor.
                -->
                <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                <input type="text" class="search-term" placeholder="what are you looking for?" id="search-term">
                <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                </div>
            </div>
            <div class="container">
                <div id="search-overlay__results">
                        
                </div>
            </div>
        </div>
      `);
  }

}

export default Search;
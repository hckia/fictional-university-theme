previously in js/Search.js around line 102...

```

        // take this code and place it in an example file later on
        /*
        We use the ES6 syntax primarily for one reason other than the fact that is less verbose.
        With the ES6 function this does not get bound to $.getJSON, so when we use this.resultsDiv, it knows we are referring to OUR objects
        property, and not that of whatever api we are referencing.
        Alternatively we could have used the older syntax and bound this to the function like so...
        function(posts){

        }.bind(this)

        we also need to use the when/then functions from JQuery, since these will allow us to make two requests (post and pages) asynchronously...
        */
        $.when(
            //unveristyData.root_url is from functions.php in our university_Files function
            $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val()), 
                $.getJSON(universityData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val())
            ).then((posts, pages) => {
            /* 
            Using string to output html is problematic since it requires us to stay on one line, or concatinate each line to the next. 
            Instead of single or double quotes, we can use something called a 'template literal'
            referenced with two backticks (``) to output our html on multiple lines.
            if we want to reference our posts object we can use ${} which in this context does NOT refer to JQuery. this is a native ES syntax
            that informs JavaScript that what we are about to type within ${} should be referenced as JavaScript code.
            */
            /* 
            In JavaScript Arrays have access to a function called map that allows us to loop through each item within an array.
            this by default adds a comma at the end of each item, to remove this we can append .join('') to the end of that function. An empty join
            tells JS we do not want any deliminator.
            for example...
            var testAarray = ['red','orange','yellow'];
             this.resultsDiv.html(`
                 <h2 class="search-overlay__section-title">General Information</h2>
                 <ul class="link-list min-list">
                 ${testAarray.map(post => `<li>${post}</li>`).join('')}
                 </ul>
             `);
 
             The result of this would be...
             red
             orange
             yellow
            */
            /* ternary operator
                $ { condition ? true : false }
            */
            /* 
             arrays have a concat function that allows you to combine it with different arrays concat is kind of like appending. in this case we are
             getting the results for searching posts, and pages, in two getJSON functions, and then combining our results
             the posts and pages array now carry much information in regards to our asynchronous methods (call back functions). Since we only want to
             combine data, we are focusing on this first array.
            */
            var combinedResults = posts[0].concat(pages[0]);
            this.resultsDiv.html(`
                <h2 class="search-overlay__section-title">General Information</h2>
                ${combinedResults.length ? '<ul class="link-list min-list">' : '<p>No General information matches that search</p>'}
                <!-- template literals do not allow us to add traditional control statements, but we can use ternary operators... -->
                ${combinedResults.map(result => `<li><a href="${result.link}">${result.title.rendered}</a> ${result.type == 'post' ? `by ${result.authorName}` : ''}</li>`).join('')}
                ${combinedResults.length ? '</ul>' : ''}
            `);
            this.isSpinnerVisible = false;            
        }, () => {
            this.resultsDiv.html('<p>Unexpected error. Please try again</p>');
        } );

```

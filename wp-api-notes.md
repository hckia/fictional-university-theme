# My WordPress API notes

[WordPress REST API Website](https://developer.wordpress.org/rest-api/)

### endpoint

/wp-json/wp/v2/


### pages endpoint

this will return the last 10 by default
```
/wp-json/wp/v2/pages
```

### post endpoint

this will return the last 10 by default

```
/wp-json/wp/v2/posts
```

### query endpoints

search for something (no { })
```
/wp-json/wp/v2/posts?search={searchParam}
```

specify number of pages to return
```
/wp-json/wp/v2/posts?per_page=1
```

request data about a specific post (post id of 7)

```
/wp-json/wp/v2/posts/7
```

### example of using JQuery to access WP API...

```
    getResults(){
        $.getJSON('http://localhost:3000/wp-json/wp/v2/posts?search=' + this.searchField.val(),(posts)=>{
            alert(posts[0].title.rendered);
        })
        //this.resultsDiv.html("imagine REAL search results here...");
        //this.isSpinnerVisible = false;
    }
```

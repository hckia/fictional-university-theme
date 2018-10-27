# Advanced Custom Field notes

These are notes i've taken to work with the Advanced custom fields plugin. It's primarily to ensure I understand how people use ACF, and what compatibility issues might exist with Gutenberg. Since this overall repo is a project on WordPress development regarding a Fictional University, the notes here will be more like examples to utilize it within the scope of that fictional site.

#### After installing Activating ACF...

'Custom Fields' will appaer within the WordPress Dashboard.

1. Click on Custom FIelds
2. Click 'Add New'
3. Click 'Add Field' (example: Event Date)
4. Field Label is the human readable name, FIeld Name is a more computer friendly name that will be automatically populated after filling in your
field label. This field name is what we will utilize when coding ACF into our front end.
5. Field Type (example: for event date we would chose JQuery: Date Picker *shudder*)
6. Add Instructions for the user if necessary
7. Add Required if necessary
8. Default value
10. Placeholder text
11. etc
12. Under Location: Usually Custom Fields are attached to a Custom Post Type. In this case our Custom Field will appear when the Post Type is equal
to Event. So we set it as such.
13. make sure its active
14. Before hitting save take Note that tye Style is set (by default) to Standard (WP metabox). This will work in Gutenberg, but at the time of writing these notes it will be pushed down to the bottom of a Custom Posts Page. See the [following article by ACF as an example](https://www.advancedcustomfields.com/blog/the-state-of-acf-in-a-gutenberg-world/)

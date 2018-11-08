<!--
    method of get will make sure the contents end up at the end of a url 
    action will make sure this form completes at the root of our url (site_url('/'). We encapsulate this function in esc_url for security purposes.__
    This is moreso for the protection of site visitors than yourself in the event your site has been hacked, or an admin has gone rogue.
        https://codex.wordpress.org/Function_Reference/esc_url
-->
    <form class="search-form" method="get" action="<?php echo esc_url(site_url('/')); ?>">
        <!-- By default WordPress' static search function is something.com?s={param}, so we can create a search form with the name s to append that to our 
            query string.
        -->
        <!--for in label should match the id in an input -->
        <label class="headline headline--medium" for="s">Perform a New Search:</label>
        <div class="search-form-row">
        <input placeholder="what are you looking for?" class="s" id="s" type="search" name="s" />
        <input class="search-submit" type="submit" value="Search" />
        </div>
    </form>
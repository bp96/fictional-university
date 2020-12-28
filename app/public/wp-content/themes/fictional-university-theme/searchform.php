<form class="search-form"method="get" action="<?php echo esc_url(site_url('/')) ?>"> 
        <!-- esc_url is security best practice -->
        <label class="headline headline--medium" for="s">Perform a new search</label>
            <div class="search-form-row">
              <input placeholder="What are you looking for?" class="s" id="s" type="search" name="s">
              <input class="search-submit" type="submit" value="Search">
              
            </div>
        </form>

<!-- this is what is used for the get_search_form() calls -->
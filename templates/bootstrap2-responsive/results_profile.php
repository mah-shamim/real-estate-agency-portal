                <ul class="thumbnails agent-property">
                {agent_estates}
                      <li class="span4">
                        <div class="thumbnail f_{is_featured}">
                          <h3>{option_10}&nbsp;</h3>
                          <img alt="300x200" data-src="holder.js/300x200" style="width: 300px; height: 200px;" src="{thumbnail_url}" />
                          {has_option_38}
                          <div class="badget"><img src="<?php echo _ch($item['badget']); ?>" alt="{option_38}"/></div>
                          {/has_option_38}
                          {has_option_4}
                          <div class="purpose-badget fea_{is_featured}">{option_4}</div>
                          {/has_option_4}
                          {has_option_54}
                          <div class="ownership-badget fea_{is_featured}">{option_54}</div>
                          {/has_option_54}
                          <img class="featured-icon" alt="Featured" src="assets/img/featured-icon.png" />
                          <a href="{url}" class="over-image"> </a>
                          <div class="caption">
                            <p class="bottom-border"><strong>{address}</strong></p>
                            <p class="bottom-border">{options_name_2} <span>{option_2}</span></p>
                            <p class="bottom-border">{options_name_3} <span>{option_3}</span></p>
                            <p class="bottom-border">{options_name_19} <span>{option_19}</span></p>
                            <p class="prop-description"><i>{option_chlimit_8}</i></p>
                            <p>
                            <a class="btn btn-info" href="{url}">
                            {lang_Details}
                            </a>
                            {has_option_36}
                            <span class="price">{options_prefix_36} {option_36} {options_suffix_36}</span>
                            {/has_option_36}
                            </p>
                          </div>
                        </div>
                      </li>
                {/agent_estates}
                </ul>
                
                
                <div class="pagination-ajax-results pagination" rel="ajax_results">
                {pagination_links_agent}
                </div>
/* ===================================================
 * budkit-slider.js v0.0.1
 * http://budkit.org/docs/editor
 * ===================================================
 * Copyright 2012 The BudKit Team
 *
 * This source file is subject to version 3.01 of the GNU/GPL License
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/gpl.txt  If you did not receive a copy of
 * the GPL License and are unable to obtain it through the web, please
 * send a note to support@stonyhillshq.com so we can mail you a copy immediately.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */
!function($) {
    "use strict"
    var BKSlider = function(object, options) {
        this.options = options
        this.$element = $(object);

        this.href = this.$element.attr("href"),
            this.remote = !/#/.test(this.href) && this.href;
        //Get the item: AJAX,
        //determine its type!
        //create into #budkit-slider
        console.log(this.remote);

        //display #budkit-slider
        this.remote && $("#budkit-slider").find('.budkit-slider-content:first').empty().load(this.remote + ".raw");
        //$("#budkit-slider").modal({ remote: !/#/.test(href) && href });
        $("#budkit-slider").modal("show")

    };
    //Uploader Class
    BKSlider.prototype = {};
    //Plugin Defintion
    $.fn.bkslider = function(option) {
        return this.each(function() {
            var $this = $(this)
                , options = typeof option == 'object' && option;
            //I probably should not be doing this but hey?
            $this.data('bkslider', (new BKSlider(this, options)))
        });
    };
    $.fn.bkslider.defaults = {};
    $.fn.bkslider.Constructor = BKSlider;

    //Plugin data api
    $(document).on('click.bkslider', '[data-target="budkit-slider"]', function(e) {
        e.preventDefault();
        $(this).bkslider();
    })
}(window.jQuery);

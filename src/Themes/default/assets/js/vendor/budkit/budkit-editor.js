/* ===================================================
 * budkit-editor.js v0.0.1
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
    //Class Definition
    var BKEditor = function(element, options) {

        var $editor = this;
        this.range = 0;

        this.options    = $.extend({}, $.fn.bkeditor.defaults, options)
        this.element    = $(element);

    };
    BKEditor.prototype = {

        Constructor: BKEditor,


    };

    //Plugin Defintion
    $.fn.bkeditor = function(option) {
        return this.each(function() {
            var $this = $(this)
                , data = $this.data('bkeditor')
                , options = typeof option == 'object' && option
            ;
            if (!data)
                $this.data('bkeditor', (data = new BKEditor(this, options)));
        });
    };
    $.fn.bkeditor.defaults = {
        component: ".page-feature", //a page feature
        repository: ".page-features" //a list of all available page features
    };
    $.fn.bkeditor.Constructor = BKEditor;


    $(function() {
        $('[data-toggle="budkit-editor"]').bkeditor();
    });

}(window.jQuery);

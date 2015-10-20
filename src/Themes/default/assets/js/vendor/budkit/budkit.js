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

define("jquery.budkit", ["jquery"], function ($) {

    // Create the defaults once
    var defaults = {
        propertyName: "value"
    };

    // The actual plugin constructor
    function Budkit( element, options ) {
        this.element = element;
        this.options = $.extend( {}, defaults, options) ;
        this._defaults = defaults;

        this.init();
    }

    function BudkitToolbar(){}
    function BudkitWysiwyg(){}
    function BudkitSocket(){}


    Budkit.prototype.init = function () {
        // Place initialization logic here
        // You already have access to the DOM element and
        // the options via the instance, e.g. this.element
        // and this.options
       console.log(ace);
       var editor = ace.edit(this.element);
        //editor.setTheme("ace/theme/monokai");
        editor.getSession().setMode("ace/mode/markdown");
        //editor.getSession().setMode("ace/mode/javascript");
    };

    return Budkit;
});


require(["jquery.budkit"], function(Budkit){
    //console.log(budkit);

    // A really lightweight
    // plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn.budkit = function ( options ) {
        return this.each(function () {
            if (!$.data(this, '_budkit')) {
                $.data(this, '_budkit',
                    new Budkit( this, options));
            }
        });
    }
    // BUDKIT DATA-API
    // ===============
    $(document).ready( function(){ $('[data-api="budkit"]').budkit() });
});





